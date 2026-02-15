<?php

namespace App\Livewire\Auctions;

use App\Models\Auction;
use App\Models\AuctionEvent;
use App\Models\AuctionPayment;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\Member;
use App\Models\FiscalYear;
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
    
    public $isModalOpen = false;
    public $isPaymentModalOpen = false;
    public $isHistoryModalOpen = false;
    public $editId = null;

    // Form Barang
    public $nama_barang, $harga_jadi = 0, $jumlah = 1;
    public $donatur_nama, $donatur_member_id;
    
    // Batch Properties
    public $items_list = []; 
    public $activeSearchIndex = null;

    public $searchDonatur = '';
    public $foundDonatur = [], $foundPemenangBatch = [];

    // Form Pembayaran
    public $selectedAuctionId;
    public $nominal_bayar, $tanggal_bayar, $ref_account_id, $ref_budget_post_id;

    public $paymentHistory = [];
    public $activeItemName = '';

    protected $messages = [
        'nominal_bayar.required' => 'Nominal pembayaran wajib diisi.',
        'ref_account_id.required' => 'Pilih dompet/kas penyimpanan.',
        'ref_budget_post_id.required' => 'Pilih pos anggaran RAPB.',
        'nama_barang.required' => 'Nama barang wajib diisi.',
    ];

    public function mount(AuctionEvent $event)
    {
        $this->event = $event;
        $this->tanggal_bayar = date('Y-m-d');
    }

    /**
     * Helper untuk membersihkan format rupiah secara ketat.
     * Menghapus semua karakter kecuali angka untuk mencegah "100.000" terbaca sebagai "100".
     */
    private function parseRupiah($value)
    {
        if (!$value) return 0;
        // Hapus semua karakter yang bukan angka
        $clean = preg_replace('/[^0-9]/', '', (string) $value);
        return (float) $clean;
    }

    public function updatedJumlah($value)
    {
        $qty = max(1, min(50, (int)$value));
        $this->jumlah = $qty;

        $currentCount = count($this->items_list);
        if ($qty > $currentCount) {
            for ($i = $currentCount; $i < $qty; $i++) {
                $this->items_list[] = ['pemenang_nama' => '', 'pemenang_member_id' => null, 'harga_jadi' => 0];
            }
        } else {
            $this->items_list = array_slice($this->items_list, 0, $qty);
        }
    }

    public function searchMemberBatch($index, $query)
    {
        $this->activeSearchIndex = $index;
        $this->items_list[$index]['pemenang_nama'] = $query;

        if (strlen($query) > 2) {
            $this->foundPemenangBatch = Member::whereHas('churchPeople', function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%");
            })->with('churchPeople')->limit(5)->get()->toArray();
        } else {
            $this->foundPemenangBatch = [];
        }
    }

    public function selectPemenangBatch($index, $memberId, $memberName)
    {
        $this->items_list[$index]['pemenang_member_id'] = $memberId;
        $this->items_list[$index]['pemenang_nama'] = $memberName;
        $this->foundPemenangBatch = [];
        $this->activeSearchIndex = null;
    }

    public function updatedSearchDonatur($value)
    {
        if (strlen($value) > 2) {
            $this->foundDonatur = Member::whereHas('churchPeople', function ($q) use ($value) {
                $q->where('full_name', 'like', "%{$value}%");
            })->with('churchPeople')->limit(5)->get();
        } else {
            $this->foundDonatur = [];
        }
    }

    public function selectDonatur($id, $name)
    {
        $this->donatur_member_id = $id;
        $this->donatur_nama = $name;
        $this->searchDonatur = $name;
        $this->foundDonatur = [];
    }

    public function create()
    {
        $this->reset(['nama_barang', 'donatur_nama', 'donatur_member_id', 'harga_jadi', 'jumlah', 'items_list', 'editId', 'searchDonatur', 'activeSearchIndex']);
        $this->jumlah = 1;
        $this->items_list = [['pemenang_nama' => '', 'pemenang_member_id' => null, 'harga_jadi' => 0]];
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate(['nama_barang' => 'required', 'jumlah' => 'required|numeric|min:1']);

        DB::transaction(function () {
            foreach ($this->items_list as $index => $itemData) {
                $cleanHarga = $this->parseRupiah($itemData['harga_jadi']);
                $finalName = $this->jumlah > 1 ? "{$this->nama_barang} #" . ($index + 1) : $this->nama_barang;

                Auction::create([
                    'uuid' => (string) Str::uuid(),
                    'auction_event_id' => $this->event->id,
                    'nama_barang' => $finalName,
                    'donatur_nama' => $this->donatur_nama ?: $this->searchDonatur,
                    'donatur_member_id' => $this->donatur_member_id,
                    'pemenang_nama' => $itemData['pemenang_nama'],
                    'pemenang_member_id' => $itemData['pemenang_member_id'],
                    'harga_jadi' => $cleanHarga,
                ]);
            }
        });

        $this->dispatch('notify', message: 'Item lelang berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function openPaymentModal($id)
    {
        $this->resetErrorBag();
        $this->selectedAuctionId = $id;
        $item = Auction::findOrFail($id);
        
        $this->activeItemName = $item->nama_barang;
        // Format nominal awal untuk display
        $this->nominal_bayar = number_format($item->sisa_piutang, 0, '', '');
        
        $acc = RefAccount::where('nama', 'like', $this->event->tujuan_kas == 'pembangunan' ? '%Pembangunan%' : '%Umum%')->first();
        $this->ref_account_id = $acc?->id;
        $this->ref_budget_post_id = $this->event->ref_budget_post_id;
        
        $this->isPaymentModalOpen = true;
    }

    public function savePayment()
    {
        $this->validate([
            'nominal_bayar' => 'required', 
            'ref_account_id' => 'required|exists:ref_accounts,id',
            'ref_budget_post_id' => 'required|exists:ref_budget_posts,id'
        ]);
        
        $cleanNominal = $this->parseRupiah($this->nominal_bayar);

        if ($cleanNominal <= 0) {
            $this->addError('nominal_bayar', 'Nominal harus lebih dari 0.');
            return;
        }

        DB::transaction(function () use ($cleanNominal) {
            $item = Auction::findOrFail($this->selectedAuctionId);
            
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $this->event->fiscal_year_id,
                'tanggal' => $this->tanggal_bayar,
                'jenis' => 'masuk',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $this->ref_budget_post_id,
                'nominal' => $cleanNominal,
                'keterangan' => "Lelang: {$item->nama_barang} ({$this->event->nama_event})",
                'user_id' => Auth::id()
            ]);

            AuctionPayment::create([
                'uuid' => (string) Str::uuid(),
                'auction_id' => $item->id,
                'transaction_id' => $trx->id,
                'nominal' => $cleanNominal,
                'tanggal_bayar' => $this->tanggal_bayar,
            ]);

            $item->update(['total_terbayar_cache' => $item->payments()->sum('nominal')]);
        });

        $this->isPaymentModalOpen = false;
        $this->dispatch('notify', message: 'Pembayaran berhasil diverifikasi.', type: 'success');
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
                ->where(fn($q) => $q->where('nama_barang', 'like', "%{$this->search}%")->orWhere('pemenang_nama', 'like', "%{$this->search}%"))
                ->with(['pemenang.churchPeople', 'donatur.churchPeople'])
                ->latest()->paginate(15),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->orderBy('kode')->get()
        ]);
    }
}