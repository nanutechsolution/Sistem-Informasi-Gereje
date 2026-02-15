<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\RefWilayah;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $nomor_kk;
    public $wilayah_id;
    public $alamat;
    public $status = 'aktif';
    public $keterangan;

    protected $rules = [
        'nomor_kk' => 'required|numeric|digits:16|unique:families,nomor_kk',
        'wilayah_id' => 'required|exists:ref_wilayahs,id',
        'alamat' => 'required|min:5',
        'status' => 'required|in:aktif,pindah,keluar,disiplin',
        'keterangan' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        Family::create([
            'uuid' => Str::uuid(), // Generate UUID
            'nomor_kk' => $this->nomor_kk,
            'wilayah_id' => $this->wilayah_id,
            'alamat' => $this->alamat,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('notify', message: 'Kartu Keluarga baru berhasil dibuat!', type: 'success');
        return redirect()->route('families.index');
    }

    public function render()
    {
        return view('livewire.families.create', [
            // Asumsi nama tabel ref_wilayahs kolomnya 'nama'
            'refWilayahs' => RefWilayah::orderBy('nama')->get() 
        ]);
    }
}