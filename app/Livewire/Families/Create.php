<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\RefWilayah; // Import Model Wilayah
use Livewire\Component;

class Create extends Component
{
    // Properti Data
    public $nomor_kk;
    public $kepala_keluarga;
    public $wilayah_id; // Ganti $wilayah jadi $wilayah_id
    public $alamat;
    public $status = 'aktif';
    public $keterangan;

    protected $messages = [
        'nomor_kk.required' => 'Nomor KK wajib diisi.',
        'nomor_kk.unique' => 'Nomor KK ini sudah terdaftar.',
        'nomor_kk.digits' => 'Nomor KK wajib 16 digit.',
        'kepala_keluarga.required' => 'Nama Kepala Keluarga wajib diisi.',
        'wilayah_id.required' => 'Wilayah pelayanan wajib dipilih.',
        'alamat.required' => 'Alamat rumah wajib diisi.',
    ];

    public function save()
    {
        // 1. Validasi
        $this->validate([
            'nomor_kk' => 'required|numeric|digits:16|unique:families,nomor_kk',
            'kepala_keluarga' => 'required|min:3',
            'wilayah_id' => 'required|exists:ref_wilayahs,id',
            'alamat' => 'required|min:5',
            'status' => 'required|in:aktif,pindah,keluar,disiplin',
            'keterangan' => 'nullable|string',
        ]);

        // 2. Simpan
        Family::create([
            'nomor_kk' => $this->nomor_kk,
            'kepala_keluarga' => $this->kepala_keluarga,
            'wilayah_id' => $this->wilayah_id,
            'alamat' => $this->alamat,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        // 3. Notifikasi & Redirect
        $this->dispatch('notify', message: 'Data Keluarga berhasil didaftarkan!', type: 'success');
        return redirect()->route('families.index');
    }

    public function render()
    {
        return view('livewire.families.create', [
            'refWilayahs' => RefWilayah::orderBy('nama')->get() 
        ]);
    }
}
