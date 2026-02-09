<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefWilayah;
use Livewire\Component;

class Edit extends Component
{
    public Family $family;

    public $wilayah_id;
    // Properti Data
    public $nomor_kk;
    public $kepala_keluarga;
    public $wilayah;
    public $alamat;
    public $status;
    public $keterangan;

    // Custom Messages
    protected $messages = [
        'nomor_kk.required' => 'Nomor KK wajib diisi.',
        'nomor_kk.unique' => 'Nomor KK ini sudah digunakan keluarga lain.',
        'kepala_keluarga.required' => 'Nama Kepala Keluarga wajib diisi.',
        'wilayah.required' => 'Wilayah pelayanan wajib dipilih.',
        'alamat.required' => 'Alamat rumah wajib diisi.',
    ];

    public function mount(Family $family)
    {
        $this->family = $family;
        $this->wilayah_id = $family->wilayah_id;
        // Isi form dengan data lama
        $this->nomor_kk = $family->nomor_kk;
        $this->kepala_keluarga = $family->kepala_keluarga;
        $this->wilayah = $family->wilayah;
        $this->alamat = $family->alamat;
        $this->status = $family->status;
        $this->keterangan = $family->keterangan;
    }

    public function update()
    {
        // 1. Validasi
        $this->validate([
            // Unique ignorable: Boleh pakai nomor KK lama milik sendiri
            'nomor_kk' => 'required|numeric|unique:families,nomor_kk,' . $this->family->id,
            'kepala_keluarga' => 'required|min:3',
            'wilayah_id' => 'required',
            'alamat' => 'required|min:5',
            'status' => 'required|in:aktif,pindah,keluar,disiplin',
            'keterangan' => 'nullable|string',
        ]);

        // 2. Update Database
        $this->family->update([
            'nomor_kk' => $this->nomor_kk,
            'kepala_keluarga' => $this->kepala_keluarga,
            'wilayah_id' => $this->wilayah_id,
            'alamat' => $this->alamat,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        // 3. Notifikasi & Redirect
        $this->dispatch('notify', message: 'Data Keluarga berhasil diperbarui!', type: 'success');
        return redirect()->route('families.index');
    }

    public function deleteMember($memberId)
    {
        // 1. Cek Otoritas (Hanya Admin & Pendeta)
        if (!in_array(auth()->user()->role, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak memiliki izin menghapus data anggota.', type: 'error');
            return;
        }

        // 2. Cari & Hapus
        $member = Member::find($memberId);

        if ($member) {
            $member->delete();
            $this->dispatch('notify', message: 'Data anggota berhasil dihapus.', type: 'success');

            // Refresh data family agar tampilan terupdate
            $this->family->refresh();
        }
    }
    public function render()
    {
        return view('livewire.families.edit', [
            'refWilayahs' => RefWilayah::orderBy('nama')->get() // Kirim data master ke view
        ]);
    }
}
