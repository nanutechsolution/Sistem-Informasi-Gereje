<?php

namespace App\Livewire\Settings;

use App\Models\RefActivityType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ActivityTypes extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $editId = null;

    // Form Properties
    public $nama, $warna_label = '#3b82f6'; // Default Biru

    protected $rules = [
        'nama' => 'required|min:3|unique:ref_activity_types,nama',
        'warna_label' => 'required',
    ];

    protected $messages = [
        'nama.required' => 'Nama kegiatan wajib diisi.',
        'nama.unique' => 'Nama kegiatan ini sudah ada.',
        'warna_label.required' => 'Pilih warna label untuk kalender.',
    ];

    public function create()
    {
        $this->reset(['editId', 'nama']);
        $this->warna_label = '#3b82f6';
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $type = RefActivityType::findOrFail($id);
        $this->editId = $type->id;
        $this->nama = $type->nama;
        $this->warna_label = $type->warna_label;
        $this->isOpen = true;
    }

    public function save()
    {
        // Validasi Unique kecuali diri sendiri saat edit
        $this->validate([
            'nama' => 'required|min:3|unique:ref_activity_types,nama,' . $this->editId,
            'warna_label' => 'required',
        ]);

        RefActivityType::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? RefActivityType::find($this->editId)->uuid : (string) Str::uuid(),
            'nama' => $this->nama,
            'warna_label' => $this->warna_label,
        ]);

        $this->dispatch('notify', message: 'Jenis kegiatan berhasil disimpan.', type: 'success');
        $this->isOpen = false;
    }

    public function delete($id)
    {
        $type = RefActivityType::findOrFail($id);
        
        // Cek jika sudah dipakai di jadwal
        if ($type->schedules()->count() > 0) {
            $this->dispatch('notify', message: 'Gagal! Jenis ini sedang dipakai di jadwal aktif.', type: 'error');
            return;
        }

        $type->delete();
        $this->dispatch('notify', message: 'Jenis kegiatan dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.activity-types', [
            'types' => RefActivityType::where('nama', 'like', '%' . $this->search . '%')
                ->orderBy('nama')
                ->paginate(10)
        ]);
    }
}