<?php

namespace App\Livewire\Settings;

use App\Models\ChurchSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChurchProfile extends Component
{
    use WithFileUploads;

    public $nama_gereja, $nama_jemaat, $deskripsi_singkat;
    public $alamat, $email, $telepon;
    public $warna_utama, $warna_aksen;
    public $facebook, $instagram, $youtube;
    public $visi, $misi = [];
    public $logo, $existingLogo;

    public function mount()
    {
        $setting = ChurchSetting::first();
        if ($setting) {
            $this->fill($setting->toArray());
            $this->existingLogo = $setting->logo_path;
            // Pastikan misi berbentuk array untuk dynamic input
            if (!$this->misi) $this->misi = [''];
        }
    }

    public function addMisi()
    {
        $this->misi[] = '';
    }

    public function removeMisi($index)
    {
        unset($this->misi[$index]);
        $this->misi = array_values($this->misi);
    }

    public function save()
    {
        $this->validate([
            'nama_gereja' => 'required',
            'email' => 'required|email',
            'warna_utama' => 'required',
            'logo' => 'nullable|image|max:1024',
        ]);

        $data = $this->except(['logo', 'existingLogo']);

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('branding', 'public');
        }

        $setting = ChurchSetting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            ChurchSetting::create($data);
        }

        $this->dispatch('notify', message: 'Profil Gereja berhasil diperbarui!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.church-profile');
    }
}