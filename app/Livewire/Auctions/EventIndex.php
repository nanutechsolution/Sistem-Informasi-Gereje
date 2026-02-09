<?php

namespace App\Livewire\Auctions;

use App\Models\AuctionEvent;
use App\Models\FiscalYear;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    
    // Form properties
    public $nama_event, $tanggal_event, $tujuan_kas = 'umum', $keterangan;

    public function create()
    {
        $this->reset(['nama_event', 'tanggal_event', 'tujuan_kas', 'keterangan']);
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'nama_event' => 'required|min:5',
            'tanggal_event' => 'required|date',
            'tujuan_kas' => 'required|in:umum,pembangunan',
        ]);

        AuctionEvent::create([
            'uuid' => (string) Str::uuid(),
            'fiscal_year_id' => FiscalYear::active()->id,
            'nama_event' => $this->nama_event,
            'tanggal_event' => $this->tanggal_event,
            'tujuan_kas' => $this->tujuan_kas,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('notify', message: 'Kegiatan lelang berhasil dibuat!', type: 'success');
        $this->isOpen = false;
    }

    public function render()
    {
        $events = AuctionEvent::withCount('auctions')
            ->where('nama_event', 'like', '%' . $this->search . '%')
            ->latest('tanggal_event')
            ->paginate(9);

        return view('livewire.auctions.event-index', [
            'events' => $events
        ]);
    }
}