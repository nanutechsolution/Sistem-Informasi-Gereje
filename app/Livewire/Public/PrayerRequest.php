<?php

namespace App\Livewire\Public;

use App\Models\PrayerRequest as PrayerModel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('components.layouts.web')]
#[Title('Permohonan Doa | GKS Jemaat Reda Pada')]
class PrayerRequest extends Component
{
    public $nama_pemohon;
    public $kontak;
    public $kategori = 'Pergumulan';
    public $pokok_doa;
    
    // Opsi Privasi
    public $is_private = false; // Default boleh publik
    public $butuh_konseling = false;

    public $successSent = false;

    protected $rules = [
        'kategori' => 'required',
        'pokok_doa' => 'required|min:10',
    ];

    public function save()
    {
        $this->validate();

        PrayerModel::create([
            'uuid' => (string) Str::uuid(),
            'nama_pemohon' => $this->nama_pemohon ?: 'Hamba Tuhan',
            'kontak' => $this->kontak,
            'kategori' => $this->kategori,
            'pokok_doa' => $this->pokok_doa,
            'is_private' => $this->is_private,
            'butuh_konseling' => $this->butuh_konseling,
            'status' => 'baru'
        ]);

        $this->reset(['nama_pemohon', 'kontak', 'pokok_doa', 'is_private', 'butuh_konseling']);
        $this->successSent = true;
    }

    public function render()
    {
        return view('livewire.public.prayer-request');
    }
}