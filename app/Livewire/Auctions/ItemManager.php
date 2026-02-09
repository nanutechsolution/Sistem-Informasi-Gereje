<?php

namespace App\Livewire\Auctions;

use App\Models\Auction;
use App\Models\AuctionEvent;
use App\Models\AuctionPayment;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemManager extends Component
{
    use WithPagination;

    public AuctionEvent $event;
    public $search = '';
    
    // State Modal
    public $isModalOpen = false;
    public $isPaymentModalOpen = false;
    public $isHistoryModalOpen = false;
    public $editId = null;

    // Field Form Barang
    public $nama_barang, $donatur_nama, $pemenang_nama, $harga_jadi = 0;

    // Field Form Pembayaran
    public $selectedAuctionId;
    public $nominal_bayar, $tanggal_bayar, $catatan_bayar, $ref_account_id;

    public $paymentHistory = [];
    public $activeItemName = '';

    public function mount(AuctionEvent $event)
    {
        $this->event = $event;
        $this->tanggal_bayar = date('Y-m-d');
    }

    // --- MANAJEMEN BARANG ---
    public function create()
    {
        $this->reset(['nama_barang', 'donatur_nama', 'pemenang_nama', 'harga_jadi', 'editId']);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate(['nama_barang' => 'required', 'harga_jadi' => 'required']);
        $cleanHarga = (float) str_replace('.', '', $this->harga_jadi);

        Auction::updateOrCreate(['id' => $this->editId], [
            'uuid' => (string) Str::uuid(),
            'auction_event_id' => $this->event->id,
            'nama_barang' => $this->nama_barang,
            'donatur_nama' => $this->donatur_nama,
            'pemenang_nama' => $this->pemenang_nama,
            'harga_jadi' => $cleanHarga,
        ]);

        $this->dispatch('notify', message: 'Barang berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    // --- MANAJEMEN PEMBAYARAN (FIX POS & KAS) ---
    public function openPaymentModal($id)
    {
        $this->selectedAuctionId = $id;
        $item = Auction::findOrFail($id);
        $this->activeItemName = $item->nama_barang;
        $this->nominal_bayar = number_format($item->sisa_piutang, 0, ',', '.');

        // OTOMATIS PILIH KAS BERDASARKAN TUJUAN EVENT
        $keyword = $this->event->tujuan_kas == 'pembangunan' ? 'Pembangunan' : 'Umum';
        $acc = RefAccount::where('nama', 'like', "%$keyword%")->first();
        $this->ref_account_id = $acc->id ?? null;

        $this->isPaymentModalOpen = true;
    }

    public function savePayment()
    {
        $this->validate(['nominal_bayar' => 'required', 'ref_account_id' => 'required']);

        DB::transaction(function () {
            $item = Auction::findOrFail($this->selectedAuctionId);
            $cleanNominal = (float) str_replace('.', '', $this->nominal_bayar);
            
            // OTOMATIS CARI POS ANGGARAN (Audit 20 Thn)
            $kodePos = $this->event->tujuan_kas == 'pembangunan' ? '1.21.1' : '1.11';
            $pos = RefBudgetPost::where('kode', $kodePos)->first();

            if (!$pos) {
                throw new \Exception("Pos Anggaran Lelang ($kodePos) belum dibuat di Master Data.");
            }

            // 1. Catat Jurnal Kas Masuk
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $this->event->fiscal_year_id,
                'tanggal' => $this->tanggal_bayar,
                'jenis' => 'masuk',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $pos->id, // MASUK SESUAI POS
                'nominal' => $cleanNominal,
                'keterangan' => "Terima Lelang: {$item->nama_barang} ({$item->pemenang_nama})",
                'user_id' => Auth::id(),
            ]);

            // 2. Hubungkan ke Riwayat Lelang
            AuctionPayment::create([
                'uuid' => (string) Str::uuid(),
                'auction_id' => $item->id,
                'transaction_id' => $trx->id,
                'nominal' => $cleanNominal,
                'tanggal_bayar' => $this->tanggal_bayar,
                'keterangan' => $this->catatan_bayar,
            ]);

            $item->update(['total_terbayar_cache' => $item->payments()->sum('nominal')]);
        });

        $this->dispatch('notify', message: 'Pembayaran lelang masuk ke Jurnal Kas.', type: 'success');
        $this->isPaymentModalOpen = false;
    }

    public function openHistoryModal($id)
    {
        $item = Auction::with(['payments.transaction.account'])->findOrFail($id);
        $this->activeItemName = $item->nama_barang;
        $this->paymentHistory = $item->payments;
        $this->isHistoryModalOpen = true;
    }

    public function render()
    {
        return view('livewire.auctions.item-manager', [
            'items' => Auction::where('auction_event_id', $this->event->id)
                ->where('nama_barang', 'like', "%{$this->search}%")
                ->latest()->paginate(15),
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}