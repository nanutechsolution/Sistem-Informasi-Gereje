<?php

namespace App\Livewire\Public;

use App\Models\PrayerRequest as PrayerModel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PrayerRequest extends Component
{
    // Properti Form
    public $nama_pemohon = '';
    public $kontak = '';
    public $kategori = 'Pergumulan';
    public $pokok_doa = '';
    public $is_private = false;
    public $butuh_konseling = false;
    public $successSent = false;

    // Honeypot: Field jebakan untuk bot (harus tetap kosong)
    public $security_trap = ''; 

    protected function rules()
    {
        return [
            'nama_pemohon'    => 'nullable|string|max:100',
            'kontak'          => 'nullable|string|max:50',
            'kategori'        => 'required|string|in:Pergumulan,Ucapan Syukur,Sakit Penyakit,Dukacita,Lainnya',
            'pokok_doa'       => 'required|string|min:10|max:2000',
            'is_private'      => 'boolean',
            'butuh_konseling' => 'boolean',
            'security_trap'   => 'present|max:0', // Bot akan gagal jika mengisi ini
        ];
    }

    /**
     * Menyimpan data dengan proteksi keamanan maksimal.
     */
    public function save()
    {
        // 1. Cek Honeypot (Anti-Bot)
        if (!empty($this->security_trap)) return;

        // 2. Proteksi Spam (Rate Limiting: Max 2 per jam per IP)
        $this->ensureIsNotSpamming();

        $this->validate();

        // 3. Sanitasi & Simpan (Anti-XSS & SQL Injection)
        // Laravel Eloquent otomatis mencegah SQL Injection via Prepared Statements.
        PrayerModel::create([
            'nama_pemohon'    => strip_tags(trim($this->nama_pemohon)) ?: 'Hamba Tuhan',
            'kontak'          => strip_tags(trim($this->kontak)),
            'kategori'        => $this->kategori,
            'pokok_doa'       => strip_tags(trim($this->pokok_doa)),
            'is_private'      => $this->is_private,
            'butuh_konseling' => $this->butuh_konseling,
            'status'          => 'baru',
            'ip_address'      => request()->ip(), // Untuk audit & ban jika nakal
            'user_agent'      => request()->userAgent(),
        ]);

        // 4. Hit Limiter (Mencegah pengiriman berulang)
        RateLimiter::hit($this->throttleKey(), 3600);

        $this->reset(['nama_pemohon', 'kontak', 'pokok_doa', 'is_private', 'butuh_konseling']);
        $this->successSent = true;
    }

    /**
     * Memastikan user tidak melakukan spamming.
     */
    protected function ensureIsNotSpamming()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 2)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            throw ValidationException::withMessages([
                'pokok_doa' => "Aktivitas mencurigakan. Coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }
    }

    protected function throttleKey()
    {
        return 'prayer-req|' . request()->ip();
    }

    #[Layout('layouts.web')]
    #[Title('Permohonan Doa | GKS Jemaat Reda Pada')]
    public function render()
    {
        return view('livewire.public.prayer-request');
    }
}