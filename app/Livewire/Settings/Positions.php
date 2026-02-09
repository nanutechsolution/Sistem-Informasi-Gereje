<?php

namespace App\Livewire\Settings;

use App\Models\RefPosition;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Positions extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $positionId;

    // Form Properties
    public $nama, $singkatan, $is_paid = false, $urutan = 0;
    public $default_incentive = 0, $default_allowance = 0;

    protected $rules = [
        'nama' => 'required|min:3',
        'singkatan' => 'nullable|max:10',
        'urutan' => 'integer',
        'default_incentive' => 'required', // numeric validation manual
        'default_allowance' => 'required',
    ];

    public function create()
    {
        $this->resetInput();
        $this->urutan = RefPosition::max('urutan') + 1;
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $pos = RefPosition::findOrFail($id);
        $this->positionId = $pos->id;
        $this->nama = $pos->nama;
        $this->singkatan = $pos->singkatan;
        $this->is_paid = $pos->is_paid;
        $this->urutan = $pos->urutan;
        // Format angka tanpa desimal untuk input
        $this->default_incentive = number_format($pos->default_incentive, 0, '', '');
        $this->default_allowance = number_format($pos->default_allowance, 0, '', '');
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        RefPosition::updateOrCreate(['id' => $this->positionId], [
            'uuid' => $this->positionId ? RefPosition::find($this->positionId)->uuid : (string) Str::uuid(),
            'nama' => $this->nama,
            'singkatan' => $this->singkatan,
            'is_paid' => $this->is_paid,
            'urutan' => $this->urutan,
        ]);

        $this->dispatch('notify', message: 'Jabatan berhasil disimpan.', type: 'success');
        $this->isOpen = false;
        $this->resetInput();
    }

    public function delete($id)
    {
        $pos = RefPosition::findOrFail($id);
        // Cek relasi ke church_officers
        if ($pos->officers()->exists()) {
            $this->dispatch('notify', message: 'Gagal! Jabatan sedang dipakai oleh personil aktif.', type: 'error');
            return;
        }

        $pos->delete();
        $this->dispatch('notify', message: 'Jabatan dihapus.', type: 'success');
    }

    private function resetInput()
    {
        $this->reset(['positionId', 'nama', 'singkatan', 'is_paid', 'urutan', 'default_incentive', 'default_allowance']);
    }

    public function render()
    {
        return view('livewire.settings.positions', [
            'positions' => RefPosition::where('nama', 'like', '%' . $this->search . '%')
                ->orderBy('urutan')
                ->paginate(10)
        ]);
    }
}