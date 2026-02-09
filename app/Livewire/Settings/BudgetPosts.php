<?php

namespace App\Livewire\Settings;

use App\Models\RefBudgetPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BudgetPosts extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    
    // Properti Form
    public $postId, $kode, $nama, $jenis = 'pengeluaran', $parent_id;

    protected $rules = [
        'kode' => 'required|min:1',
        'nama' => 'required|min:3',
        'jenis' => 'required|in:pemasukan,pengeluaran',
    ];

    protected $messages = [
        'kode.required' => 'Kode anggaran (KUA) wajib diisi.',
        'nama.required' => 'Nama pos / uraian wajib diisi.',
        'nama.min' => 'Nama pos minimal 3 huruf.',
    ];

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $post = RefBudgetPost::findOrFail($id);
        $this->postId = $post->id;
        $this->kode = $post->kode;
        $this->nama = $post->nama;
        $this->jenis = $post->jenis;
        $this->parent_id = $post->parent_id;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        // Otomatis samakan jenis dengan induk jika ada
        if ($this->parent_id) {
            $parent = RefBudgetPost::find($this->parent_id);
            if ($parent) {
                $this->jenis = $parent->jenis;
            }
        }

        RefBudgetPost::updateOrCreate(['id' => $this->postId], [
            'uuid' => (string) Str::uuid(),
            'kode' => $this->kode,
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'parent_id' => $this->parent_id ?: null,
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'Struktur anggaran berhasil disimpan.', type: 'success');
        $this->isOpen = false;
        $this->resetInput();
    }

    public function delete($id)
    {
        $post = RefBudgetPost::findOrFail($id);
        if ($post->children()->count() > 0) {
            $this->dispatch('notify', message: 'Gagal! Hapus sub-pos di bawahnya terlebih dahulu.', type: 'error');
            return;
        }
        $post->delete();
        $this->dispatch('notify', message: 'Pos anggaran berhasil dihapus.', type: 'success');
    }

    private function resetInput()
    {
        $this->postId = null;
        $this->kode = '';
        $this->nama = '';
        $this->jenis = 'pengeluaran';
        $this->parent_id = null;
    }

    public function render()
    {
        // Ambil Level 1 (Akar)
        $posts = RefBudgetPost::with(['children.children'])
            ->whereNull('parent_id')
            ->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode', 'like', '%' . $this->search . '%');
            })
            ->orderBy('kode')
            ->get();

        // Ambil semua pos untuk dropdown (kecuali diri sendiri saat edit)
        $allOptions = RefBudgetPost::orderBy('kode')
            ->when($this->postId, fn($q) => $q->where('id', '!=', $this->postId))
            ->get();

        // FIX: Menggunakan nama view yang spesifik agar tidak bentrok dengan modul Accounts
        return view('livewire.settings.budget-posts', [
            'posts' => $posts,
            'allOptions' => $allOptions
        ]);
    }
}