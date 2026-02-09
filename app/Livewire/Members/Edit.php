<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\RefHubunganKeluarga;
use App\Models\RefPekerjaan;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public Member $member;

    public $nama, $nik, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $no_hp;
    public $hubungan_keluarga_id, $pekerjaan_id; // Pakai ID
    public $status_baptis, $status_sidi, $status_nikah;

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'nik.digits' => 'NIK wajib 16 digit.',
        'hubungan_keluarga_id.required' => 'Hubungan keluarga wajib dipilih.',
    ];

    public function mount(Member $member)
    {
        $this->member = $member;

        $this->nama = $member->nama;
        $this->nik = $member->nik;
        $this->tempat_lahir = $member->tempat_lahir;
        $this->tanggal_lahir = Carbon::parse($member->tanggal_lahir)
            ->format('Y-m-d');
        $this->jenis_kelamin = $member->jenis_kelamin;
        $this->no_hp = $member->no_hp;

        // Load ID relasi
        $this->hubungan_keluarga_id = $member->hubungan_keluarga_id;
        $this->pekerjaan_id = $member->pekerjaan_id;

        $this->status_baptis = $member->status_baptis;
        $this->status_sidi = $member->status_sidi;
        $this->status_nikah = $member->status_nikah;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'nik' => 'nullable|numeric|digits:16|unique:members,nik,' . $this->member->id,
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'hubungan_keluarga_id' => 'required|exists:ref_hubungan_keluargas,id',
            'pekerjaan_id' => 'nullable|exists:ref_pekerjaans,id',
            'status_baptis' => 'required',
            'status_sidi' => 'required',
            'status_nikah' => 'required',
        ]);

        $this->member->update([
            'nama' => $this->nama,
            'nik' => $this->nik,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'hubungan_keluarga_id' => $this->hubungan_keluarga_id,
            'pekerjaan_id' => $this->pekerjaan_id,
            'status_baptis' => $this->status_baptis,
            'status_sidi' => $this->status_sidi,
            'status_nikah' => $this->status_nikah,
        ]);

        $this->dispatch('notify', message: 'Data anggota berhasil diperbarui!', type: 'success');

        // Gunakan objek family untuk redirect
        return redirect()->route('families.edit', $this->member->family);
    }

    public function render()
    {
        return view('livewire.members.edit', [
            'refHubungans' => RefHubunganKeluarga::orderBy('urutan')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}
