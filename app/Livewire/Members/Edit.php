<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\RefHubunganKeluarga;
use App\Models\RefPekerjaan;
use Livewire\Component;

class Edit extends Component
{
    public Member $member;
    public $personName; // Read-only

    // Data yang bisa diubah di level Member
    public $hubungan_keluarga_id;
    public $pekerjaan_id;
    public $status_keanggotaan;

    public function mount(Member $member)
    {
        $this->member = $member->load('churchPeople');
        
        // Data Orang (Display Only)
        $this->personName = $member->churchPeople->full_name;

        // Data Member
        $this->hubungan_keluarga_id = $member->hubungan_keluarga_id;
        $this->pekerjaan_id = $member->pekerjaan_id;
        $this->status_keanggotaan = $member->status_keanggotaan;
    }

    public function update()
    {
        $this->validate([
            'hubungan_keluarga_id' => 'required|exists:ref_hubungan_keluargas,id',
            'pekerjaan_id' => 'required|exists:ref_pekerjaans,id',
            'status_keanggotaan' => 'required|in:aktif,pindah,meninggal',
        ]);

        $this->member->update([
            'hubungan_keluarga_id' => $this->hubungan_keluarga_id,
            'pekerjaan_id' => $this->pekerjaan_id,
            'status_keanggotaan' => $this->status_keanggotaan,
        ]);

        $this->dispatch('notify', message: 'Data keanggotaan diperbarui.', type: 'success');
        return redirect()->route('families.edit', $this->member->family->uuid);
    }

    public function render()
    {
        return view('livewire.members.edit', [
            'refHubungans' => RefHubunganKeluarga::orderBy('urutan')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}