<?php

namespace App\Livewire\Auctions;

use App\Models\Auction;
use App\Models\AuctionEvent;
use App\Models\AuctionPayment;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\Member;
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

    public $nama_barang, $harga_jadi = 0;
    public $donatur_nama, $donatur_member_id;
    public $pemenang_nama, $pemenang_member_id;
    
    public $searchDonatur = '', $searchPemenang = '';
    public $foundDonatur = [], $foundPemenang = [];

    public $selectedAuctionId;
    public $nominal_bayar, $tanggal_bayar, $catatan_bayar, $ref_account_id, $ref_budget_post_id;

    public $paymentHistory = [];
    public $activeItemName = '';

    protected $messages = [
        'nama_barang.required' => 'Nama barang wajib diisi.',
        'harga_jadi.required' => 'Harga kesepakatan wajib diisi.',
        'ref_account_id.required' => 'Pilih dompet penyimpanan.',
        'ref_budget_post_id.required' => 'Pilih pos anggaran.',
    ];

    public function mount(AuctionEvent $event)
    {
        $this->event = $event;
        $this->tanggal_bayar = date('Y-m-d');
    }

    public function updatedSearchDonatur($value)
    {
        $this->foundDonatur = strlen($value) > 1 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get()->toArray() 
            : [];
    }

    public function updatedSearchPemenang($value)
    {
        $this->foundPemenang = strlen($value) > 1 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get()->toArray() 
            : [];
    }

    public function selectDonatur($id, $name)
    {
        $this->donatur_member_id = $id;
        $this->donatur_nama = $name;
        $this->searchDonatur = $name;
        $this->foundDonatur = [];
    }

    public function selectPemenang($id, $name)
    {
        $this->pemenang_member_id = $id;
        $this->pemenang_nama = $name;
        $this->searchPemenang = $name;
        $this->foundPemenang = [];
    }

    public function create()
    {
        $this->reset(['nama_barang', 'donatur_nama', 'donatur_member_id', 'pemenang_nama', 'pemenang_member_id', 'harga_jadi', 'editId', 'searchDonatur', 'searchPemenang', 'foundDonatur', 'foundPemenang']);
        $this->resetErrorBag();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $item = Auction::findOrFail($id);
        $this->editId = $item->id;
        $this->nama_barang = $item->nama_barang;
        $this->donatur_nama = $item->donatur_nama;
        $this->donatur_member_id = $item->donatur_member_id;
        $this->pemenang_nama = $item->pemenang_nama;
        $this->pemenang_member_id = $item->pemenang_member_id;
        $this->searchDonatur = $item->donatur_nama;
        $this->searchPemenang = $item->pemenang_nama;
        $this->harga_jadi = $item->harga_jadi;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate(['nama_barang' => 'required', 'harga_jadi' => 'required']);
        
        // Manual sync donatur/pemenang name jika user hanya ketik manual tanpa pilih jemaat
        if(!$this->donatur_member_id) $this->donatur_nama = $this->searchDonatur;
        if(!$this->pemenang_member_id) $this->pemenang_nama = $this->searchPemenang;

        $cleanHarga = (float) str_replace(['.', ','], '', $this->harga_jadi);

        Auction::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? Auction::find($this->editId)->uuid : (string) Str::uuid(),
            'auction_event_id' => $this->event->id,
            'nama_barang' => $this->nama_barang,
            'donatur_nama' => $this->donatur_nama,
            'donatur_member_id' => $this->donatur_member_id ?: null,
            'pemenang_nama' => $this->pemenang_nama,
            'pemenang_member_id' => $this->pemenang_member_id ?: null,
            'harga_jadi' => $cleanHarga,
        ]);

        $this->dispatch('notify', message: 'Data berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function openPaymentModal($id)
    {
        $this->selectedAuctionId = $id;
        $item = Auction::findOrFail($id);
        $this->activeItemName = $item->nama_barang;
        $this->nominal_bayar = number_format($item->sisa_piutang, 0, ',', '.');
        
        $keyword = $this->event->tujuan_kas == 'pembangunan' ? 'Pembangunan' : 'Umum';
        $acc = RefAccount::where('nama', 'like', "%$keyword%")->where('is_active', true)->first() ?: RefAccount::where('is_active', true)->first();
        $this->ref_account_id = $acc?->id;
        $this->ref_budget_post_id = $this->event->ref_budget_post_id;

        $this->isPaymentModalOpen = true;
    }

    public function savePayment()
    {
        $this->validate([
            'nominal_bayar' => 'required', 
            'ref_account_id' => 'required', 
            'ref_budget_post_id' => 'required'
        ]);

        DB::transaction(function () {
            $item = Auction::findOrFail($this->selectedAuctionId);
            $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_bayar);
            
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $this->event->fiscal_year_id,
                'tanggal' => $this->tanggal_bayar,
                'jenis' => 'masuk',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $this->ref_budget_post_id,
                'nominal' => $cleanNominal,
                'keterangan' => "Bayar Lelang: {$item->nama_barang}",
                'user_id' => Auth::id(),
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
        $this->dispatch('notify', message: 'Pembayaran disimpan.', type: 'success');
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
                ->latest()->paginate(10),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->orderBy('kode')->get()
        ]);
    }
}