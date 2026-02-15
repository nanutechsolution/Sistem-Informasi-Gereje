<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\RefWilayah;
use App\Models\Member;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public Family $family;

    // Form Data Keluarga
    public $nomor_kk;
    public $wilayah_id;
    public $alamat;
    public $status;
    public $keterangan;

    public function mount(Family $family)
    {
        // Load Family beserta Anggota dan Data Orangnya
        // Kita urutkan berdasarkan hubungan keluarga (Suami -> Istri -> Anak)
        $this->family = $family->load(['members' => function($q) {
            $q->join('ref_hubungan_keluargas', 'members.hubungan_keluarga_id', '=', 'ref_hubungan_keluargas.id')
              ->orderBy('ref_hubungan_keluargas.urutan', 'asc') // Asumsi ada kolom 'urutan'
              ->select('members.*'); // Hindari konflik kolom id
        }, 'members.churchPeople', 'members.refHubunganKeluarga']);

        // Isi Form
        $this->nomor_kk = $family->nomor_kk;
        $this->wilayah_id = $family->wilayah_id;
        $this->alamat = $family->alamat;
        $this->status = $family->status;
        $this->keterangan = $family->keterangan;
    }

    public function update()
    {
        $this->validate([
            'nomor_kk' => ['required', 'numeric', 'digits:16', Rule::unique('families')->ignore($this->family->id)],
            'wilayah_id' => 'required|exists:ref_wilayahs,id',
            'alamat' => 'required|min:5',
            'status' => 'required|in:aktif,pindah,keluar,disiplin',
            'keterangan' => 'nullable|string',
        ]);

        $this->family->update([
            'nomor_kk' => $this->nomor_kk,
            'wilayah_id' => $this->wilayah_id,
            'alamat' => $this->alamat,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('notify', message: 'Data Kartu Keluarga berhasil diperbarui.', type: 'success');
    }

    public function deleteMember($memberUuid)
    {
        // Cek Otoritas
        $userRole = Auth::user()->role ?? '';
        if (!in_array($userRole, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak boleh menghapus anggota.', type: 'error');
            return;
        }

        $member = Member::where('uuid', $memberUuid)->first();

        if ($member) {
            $member->delete(); // Hapus dari KK (Data Orang di ChurchPeople tetap aman)
            
            $this->dispatch('notify', message: 'Anggota keluarga berhasil dihapus.', type: 'success');
            
            // Refresh data agar list terupdate otomatis
            $this->family->refresh(); 
        }
    }

    public function render()
    {
        return view('livewire.families.edit', [
            'refWilayahs' => RefWilayah::orderBy('nama')->get(),
        ]);
    }
}