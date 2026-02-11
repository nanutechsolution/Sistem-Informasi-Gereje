<?php

namespace App\Livewire\Public;

use App\Models\Document;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.web')]
#[Title('Pustaka Dokumen | GKS Jemaat Reda Pada')]
class Downloads extends Component
{
    public $filterKategori = '';

    public function download($uuid)
    {
        $doc = Document::where('uuid', $uuid)->firstOrFail();
        $doc->increment('download_count');
        return Storage::disk('public')->download($doc->file_path, $doc->judul . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }

    public function render()
    {
        return view('livewire.public.downloads', [
            'documents' => Document::where('is_public', true)
                ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
                ->latest()
                ->get()
        ]);
    }
}