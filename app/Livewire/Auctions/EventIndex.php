<?php

namespace App\Livewire\Auctions;

use App\Models\AuctionEvent;
use App\Models\FiscalYear;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    
    // Form properties
    public $nama_event, $tanggal_event, $tujuan_kas = 'umum', $ref_budget_post_id, $keterangan;

    public function create()
    {
        $this->reset(['nama_event', 'tanggal_event', 'tujuan_kas', 'ref_budget_post_id', 'keterangan']);
        $this->tanggal_event = date('Y-m-d');
        
        // Default Pos Lelang Umum (1.11)
        $defaultPos = RefBudgetPost::where('kode', '1.11')->first();
        $this->ref_budget_post_id = $defaultPos?->id;
        
        $this->isOpen = true;
    }

    public function updatedTujuanKas($value)
    {
        // Auto-switch Pos Anggaran saat user pilih tujuan kas
        $kode = ($value === 'pembangunan') ? '1.21.1' : '1.11';
        $pos = RefBudgetPost::where('kode', $kode)->first();
        $this->ref_budget_post_id = $pos?->id;
    }

    public function save()
    {
        $this->validate([
            'nama_event' => 'required|min:5',
            'tanggal_event' => 'required|date',
            'tujuan_kas' => 'required|in:umum,pembangunan',
            'ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
        ]);

        $activeYear = FiscalYear::active();
        if (!$activeYear) {
            $this->dispatch('notify', message: 'Tahun Anggaran Aktif tidak ditemukan!', type: 'error');
            return;
        }

        AuctionEvent::create([
            'uuid' => (string) Str::uuid(),
            'fiscal_year_id' => $activeYear->id,
            'nama_event' => $this->nama_event,
            'tanggal_event' => $this->tanggal_event,
            'tujuan_kas' => $this->tujuan_kas,
            // Simpan referensi pos default untuk event ini
            'ref_budget_post_id' => $this->ref_budget_post_id, 
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('notify', message: 'Kegiatan lelang berhasil didaftarkan!', type: 'success');
        $this->isOpen = false;
    }

    public function render()
    {
        $events = AuctionEvent::with(['auctions', 'fiscalYear'])
            ->withCount('auctions')
            ->where('nama_event', 'like', '%' . $this->search . '%')
            ->latest('tanggal_event')
            ->paginate(9);

        return view('livewire.auctions.event-index', [
            'events' => $events,
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->orderBy('kode')->get()
        ]);
    }
}