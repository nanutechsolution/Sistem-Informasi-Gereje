<?php

namespace App\Livewire\Public;

use App\Models\Sermon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.web')]
#[Title('Galeri Khotbah | GKS Jemaat Reda Pada')]
class Sermons extends Component
{
    use WithPagination;

    public $search = '';
    public $activeVideo = null; // Untuk Modal Player

    public function play($id)
    {
        $this->activeVideo = Sermon::findOrFail($id);
        $this->activeVideo->increment('views'); // Hitung jumlah penonton
    }

    public function closePlayer()
    {
        $this->activeVideo = null;
    }

    public function render()
    {
        return view('livewire.public.sermons', [
            'videos' => Sermon::where('judul', 'like', "%{$this->search}%")
                ->orWhere('pengkhotbah', 'like', "%{$this->search}%")
                ->latest('tanggal')
                ->paginate(9)
        ]);
    }
}