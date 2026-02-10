<?php

namespace App\Livewire\Auctions;

use App\Models\Auction;
use App\Models\AuctionEvent;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Receivables extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEvent = '';

    public function render()
    {
        // Ambil data barang yang belum lunas (Piutang)
        $query = Auction::with(['event', 'pemenang'])
            ->where('harga_jadi', '>', DB::raw('total_terbayar_cache'))
            ->when($this->filterEvent, fn($q) => $q->where('auction_event_id', $this->filterEvent))
            ->where(function($q) {
                $q->where('pemenang_nama', 'like', "%{$this->search}%")
                  ->orWhere('nama_barang', 'like', "%{$this->search}%");
            })
            ->orderBy('pemenang_nama');

        return view('livewire.auctions.receivables', [
            'receivables' => $query->paginate(15),
            'events' => AuctionEvent::latest()->get()
        ]);
    }
}