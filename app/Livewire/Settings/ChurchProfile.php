<?php

namespace App\Livewire\Settings;

use App\Models\ChurchSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChurchProfile extends Component
{
    use WithFileUploads;

    public $settingId;

    public $nama_gereja;
    public $nama_jemaat;
    public $deskripsi_singkat;
    public $sejarah_singkat;
    public $alamat;
    public $email;
    public $telepon;

    // Kolom Warna Baru
    public $color_primary = '#1e3a8a';
    public $color_accent = '#d97706';
    public $color_background = '#f8fafc';
    public $color_sidebar = '#0f172a';
    
    // Pengaturan UI Baru
    public $appearance_mode = 'light';
    public $ui_rounded = 'xl';

    public $facebook;
    public $instagram;
    public $youtube;

    public $visi;
    public $misi = [];

    public $logo;
    public $existingLogo;

    public function mount()
    {
        $setting = ChurchSetting::first();

        if ($setting) {
            $this->settingId = $setting->id;

            $this->nama_gereja = $setting->nama_gereja;
            $this->nama_jemaat = $setting->nama_jemaat;
            $this->deskripsi_singkat = $setting->deskripsi_singkat;
            $this->sejarah_singkat = $setting->sejarah_singkat;
            $this->alamat = $setting->alamat;
            $this->email = $setting->email;
            $this->telepon = $setting->telepon;
            
            // Mapping Kolom Warna & UI Baru
            $this->color_primary = $setting->color_primary;
            $this->color_accent = $setting->color_accent;
            $this->color_background = $setting->color_background;
            $this->color_sidebar = $setting->color_sidebar;
            $this->appearance_mode = $setting->appearance_mode;
            $this->ui_rounded = $setting->ui_rounded;

            $this->facebook = $setting->facebook;
            $this->instagram = $setting->instagram;
            $this->youtube = $setting->youtube;
            $this->visi = $setting->visi;
            
            $this->misi = is_array($setting->misi)
                ? $setting->misi
                : json_decode($setting->misi ?? '[]', true);
            $this->existingLogo = $setting->logo_path;
        }

        if (empty($this->misi)) {
            $this->misi = [''];
        }
    }

    protected function rules()
    {
        return [
            'nama_gereja' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string|max:255',
            'color_primary' => 'required|string|max:7',
            'color_accent' => 'required|string|max:7',
            'color_background' => 'required|string|max:7',
            'color_sidebar' => 'required|string|max:7',
            'appearance_mode' => 'required|in:light,dark',
            'ui_rounded' => 'required|string',
            'logo' => 'nullable|image|max:1024',
            'misi.*' => 'nullable|string|max:500',
            'sejarah_singkat' => 'nullable|string',
        ];
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
        $this->validate();

        $data = [
            'nama_gereja' => $this->nama_gereja,
            'nama_jemaat' => $this->nama_jemaat,
            'deskripsi_singkat' => $this->deskripsi_singkat,
            'sejarah_singkat' => $this->sejarah_singkat,
            'alamat' => $this->alamat,
            'email' => $this->email,
            'telepon' => $this->telepon,
            'color_primary' => $this->color_primary,
            'color_accent' => $this->color_accent,
            'color_background' => $this->color_background,
            'color_sidebar' => $this->color_sidebar,
            'appearance_mode' => $this->appearance_mode,
            'ui_rounded' => $this->ui_rounded,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'visi' => $this->visi,
            'misi' => array_filter($this->misi),
        ];

        if ($this->logo) {
            if ($this->existingLogo && Storage::disk('public')->exists($this->existingLogo)) {
                Storage::disk('public')->delete($this->existingLogo);
            }
            $data['logo_path'] = $this->logo->store('branding', 'public');
        }

        if ($this->settingId) {
            ChurchSetting::find($this->settingId)->update($data);
        } else {
            $data['uuid'] = Str::uuid();
            ChurchSetting::create($data);
        }

        $this->dispatch('notify', message: 'Profil Gereja berhasil diperbarui!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.church-profile');
    }
}