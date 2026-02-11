<?php

namespace App\Livewire\Public;

use App\Models\ChurchSetting;
use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('components.layouts.web')]
#[Title('Hubungi Kami | GKS Jemaat Reda Pada')]
class Contact extends Component
{
    public $nama, $email, $telepon, $subjek, $pesan;
    public $successMessage = false;

    protected $rules = [
        'nama' => 'required|min:3',
        'email' => 'required|email',
        'subjek' => 'required|min:5',
        'pesan' => 'required|min:10',
    ];

    public function save()
    {
        $this->validate();

        ContactMessage::create([
            'uuid' => (string) Str::uuid(),
            'nama' => $this->nama,
            'email' => $this->email,
            'telepon' => $this->telepon,
            'subjek' => $this->subjek,
            'pesan' => $this->pesan,
        ]);

        $this->reset(['nama', 'email', 'telepon', 'subjek', 'pesan']);
        $this->successMessage = true;
    }

    public function render()
    {
        return view('livewire.public.contact', [
            'setting' => ChurchSetting::current()
        ]);
    }
}