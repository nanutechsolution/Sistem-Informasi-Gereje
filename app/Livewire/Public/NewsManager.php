<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsManager extends Component
{
    use WithFileUploads, WithPagination;

    public $isModalOpen = false;
    public $editId = null;

    // Form properties
    public $judul, $kategori = 'Berita', $konten, $image, $existingImage;

    public function create() {
        $this->reset(['editId', 'judul', 'konten', 'image', 'existingImage']);
        $this->isModalOpen = true;
    }

    public function save() {
        $this->validate([
            'judul' => 'required|min:5',
            'konten' => 'required',
            'image' => $this->editId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $imagePath = $this->existingImage;
        if ($this->image) {
            if ($this->existingImage) Storage::disk('public')->delete($this->existingImage);
            $imagePath = $this->image->store('news', 'public');
        }

        Post::updateOrCreate(['id' => $this->editId], [
            'judul' => $this->judul,
            'konten' => $this->konten,
            'kategori' => $this->kategori,
            'gambar_fitur' => $imagePath,
            'user_id' => Auth::id(),
            'published_at' => now(),
        ]);

        $this->dispatch('notify', message: 'Warta berhasil diterbitkan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function render() {
        return view('livewire.public.news-manager', [
            'posts' => Post::with('author')->latest()->paginate(10)
        ]);
    }
}