<?php

namespace App\Livewire\Members;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefHubunganKeluarga; // Import
use App\Models\RefPekerjaan; // Import
use Livewire\Component;

class Create extends Component
{
    public Family $family;

    // Properti Form
    public $nama, $nik, $tempat_lahir, $tanggal_lahir, $jenis_kelamin = 'L', $no_hp;
    public $hubungan_keluarga_id; // Ganti string jadi ID
    public $pekerjaan_id; // Baru
    public $status_baptis = 'Belum', $status_sidi = 'Belum', $status_nikah = 'Belum';

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'nik.digits' => 'NIK wajib 16 digit.',
        'nik.unique' => 'NIK ini sudah terdaftar.',
        'hubungan_keluarga_id.required' => 'Hubungan keluarga wajib dipilih.',
        'pekerjaan_id.required' => 'Pekerjaan wajib dipilih.',
    ];

    public function mount(Family $family)
    {
        $this->family = $family;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'nik' => 'nullable|numeric|digits:16|unique:members,nik',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'hubungan_keluarga_id' => 'required|exists:ref_hubungan_keluargas,id',
            'pekerjaan_id' => 'nullable|exists:ref_pekerjaans,id',
            'status_baptis' => 'required',
            'status_sidi' => 'required',
            'status_nikah' => 'required',
        ]);

        $this->family->members()->create([
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

        $this->dispatch('notify', message: 'Anggota keluarga berhasil ditambahkan!', type: 'success');
        return redirect()->route('families.edit', $this->family);
    }

    public function render()
    {
        return view('livewire.members.create', [
            'refHubungans' => RefHubunganKeluarga::orderBy('urutan')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}