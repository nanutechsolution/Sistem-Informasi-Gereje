<?php

namespace App\Livewire\Settings;

use App\Models\RefDueType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class DueTypeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $editId = null;

    // Properti Form
    public $nama, $target_level = 'member', $unit_type = 'money', $satuan_barang, $is_active = true;

    protected $rules = [
        'nama' => 'required|min:3',
        'target_level' => 'required|in:member,family',
        'unit_type' => 'required|in:money,item',
        'satuan_barang' => 'required_if:unit_type,item',
    ];

    public function create()
    {
        $this->reset(['editId', 'nama', 'target_level', 'unit_type', 'satuan_barang', 'is_active']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $type = RefDueType::findOrFail($id);
        $this->editId = $type->id;
        $this->nama = $type->nama;
        $this->target_level = $type->target_level;
        $this->unit_type = $type->unit_type;
        $this->satuan_barang = $type->satuan_barang;
        $this->is_active = $type->is_active;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        RefDueType::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? RefDueType::find($this->editId)->uuid : (string) Str::uuid(),
            'nama' => $this->nama,
            'target_level' => $this->target_level,
            'unit_type' => $this->unit_type,
            'satuan_barang' => $this->unit_type == 'item' ? $this->satuan_barang : null,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Jenis iuran berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        $type = RefDueType::findOrFail($id);
        
        // Proteksi jika sudah ada jemaat yang terdaftar di iuran ini
        if ($type->registries()->exists()) {
            $this->dispatch('notify', message: 'Gagal! Iuran ini sudah memiliki data jemaat terdaftar.', type: 'error');
            return;
        }

        $type->delete();
        $this->dispatch('notify', message: 'Jenis iuran berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.due-type-manager', [
            'types' => RefDueType::where('nama', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10)
        ]);
    }
}