<?php

namespace App\Livewire\Clerical;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '', $isModalOpen = false;
    public $editId = null;

    // Form
    public $judul, $kategori = 'Tata Ibadah', $deskripsi, $file, $existingFile;
    public $is_public = true;

    protected $rules = [
        'judul' => 'required|min:3',
        'kategori' => 'required',
        'file' => 'nullable|file|max:10240', // Max 10MB
    ];

    public function create()
    {
        $this->reset(['editId', 'judul', 'deskripsi', 'file', 'existingFile']);
        $this->is_public = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'judul' => 'required|min:3',
            'file' => $this->editId ? 'nullable|file|max:10240' : 'required|file|max:10240',
        ]);

        $filePath = $this->existingFile;
        if ($this->file) {
            // Hapus file lama jika ada
            if ($this->existingFile) Storage::disk('public')->delete($this->existingFile);
            $filePath = $this->file->store('documents', 'public');
        }

        Document::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? Document::find($this->editId)->uuid : (string) Str::uuid(),
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'file_path' => $filePath,
            'is_public' => $this->is_public,
        ]);

        $this->dispatch('notify', message: 'Dokumen berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function edit($id)
    {
        $doc = Document::findOrFail($id);
        $this->editId = $doc->id;
        $this->judul = $doc->judul;
        $this->kategori = $doc->kategori;
        $this->deskripsi = $doc->deskripsi;
        $this->existingFile = $doc->file_path;
        $this->is_public = $doc->is_public;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $doc = Document::findOrFail($id);
        if ($doc->file_path) Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        $this->dispatch('notify', message: 'Dokumen dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.clerical.document-manager', [
            'documents' => Document::where('judul', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10)
        ]);
    }
}