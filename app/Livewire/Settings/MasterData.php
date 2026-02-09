<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class MasterData extends Component
{
    use WithPagination;

    public $type; // Menentukan kita sedang mengelola apa (wilayah, pekerjaan, dll)
    public $search = '';
    
    // State Modal & Form
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $editId = null;
    
    // Field Data Dinamis
    public $nama;
    public $kategori; // Khusus untuk Event Type
    public $deleteName; // Untuk konfirmasi hapus

    // Mapping Konfigurasi (Otak dari Komponen Ini)
    protected function config()
    {
        return [
            'wilayah' => [
                'model' => \App\Models\RefWilayah::class,
                'title' => 'Data Wilayah Pelayanan',
                'label' => 'Nama Wilayah',
                'has_kategori' => false
            ],
            'pekerjaan' => [
                'model' => \App\Models\RefPekerjaan::class,
                'title' => 'Master Pekerjaan',
                'label' => 'Nama Pekerjaan',
                'has_kategori' => false
            ],
            'hubungan' => [
                'model' => \App\Models\RefHubunganKeluarga::class,
                'title' => 'Status Hubungan Keluarga',
                'label' => 'Status Hubungan',
                'has_kategori' => false
            ],
            'event' => [
                'model' => \App\Models\RefEventType::class,
                'title' => 'Jenis Peristiwa / Sakramen',
                'label' => 'Nama Peristiwa',
                'has_kategori' => true // Spesial: Punya kategori (Rohani/Sipil)
            ],
        ];
    }

    public function mount($type = 'wilayah')
    {
        if (!array_key_exists($type, $this->config())) {
            abort(404);
        }
        $this->type = $type;
    }

    // --- LOGIKA CRUD ---

    public function create()
    {
        $this->reset(['nama', 'kategori', 'editId']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $modelClass = $this->config()[$this->type]['model'];
        $data = $modelClass::find($id);
        
        $this->editId = $id;
        $this->nama = $data->nama;
        if ($this->config()[$this->type]['has_kategori']) {
            $this->kategori = $data->kategori;
        }
        
        $this->isModalOpen = true;
    }

    public function save()
    {
        $rules = ['nama' => 'required|min:3'];
        if ($this->config()[$this->type]['has_kategori']) {
            $rules['kategori'] = 'required';
        }
        
        $this->validate($rules, [
            'nama.required' => 'Nama wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.'
        ]);

        $modelClass = $this->config()[$this->type]['model'];
        $payload = ['nama' => $this->nama];
        
        if ($this->config()[$this->type]['has_kategori']) {
            $payload['kategori'] = $this->kategori;
        }

        if ($this->editId) {
            $modelClass::find($this->editId)->update($payload);
            $message = 'Data berhasil diperbarui.';
        } else {
            $modelClass::create($payload);
            $message = 'Data baru berhasil ditambahkan.';
        }

        $this->dispatch('notify', message: $message, type: 'success');
        $this->isModalOpen = false;
    }

    public function confirmDelete($id)
    {
        $modelClass = $this->config()[$this->type]['model'];
        $data = $modelClass::find($id);
        $this->editId = $id;
        $this->deleteName = $data->nama;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        $modelClass = $this->config()[$this->type]['model'];
        $modelClass::find($this->editId)->delete();
        
        $this->dispatch('notify', message: 'Data berhasil dihapus.', type: 'success');
        $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        $cfg = $this->config()[$this->type];
        $modelClass = $cfg['model'];

        $query = $modelClass::query();
        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        return view('livewire.settings.master-data', [
            'data' => $query->latest()->paginate(10),
            'config' => $cfg,
            'currentType' => $this->type
        ]);
    }
}