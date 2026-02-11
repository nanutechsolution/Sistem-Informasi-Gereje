<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.guest')]
#[Title('Masuk - SIG GKS')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    /**
     * Mengecek apakah user sedang dalam masa blokir (Throttle).
     */
    #[Computed]
    public function isLockedOut()
    {
        return RateLimiter::tooManyAttempts($this->throttleKey(), 5);
    }

    /**
     * Mendapatkan sisa waktu blokir dalam detik.
     */
    #[Computed]
    public function lockSeconds()
    {
        return RateLimiter::availableIn($this->throttleKey());
    }

    /**
     * Menangani proses login dengan keamanan tinggi.
     */
    public function login()
    {
        // Jangan proses jika masih terkunci
        if ($this->isLockedOut) {
            $this->ensureIsNotRateLimited();
            return;
        }

        $this->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            RateLimiter::clear($this->throttleKey());
            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Kredensial yang Anda berikan tidak cocok dengan data kami.',
        ]);
    }

    protected function ensureIsNotRateLimited()
    {
        if (!$this->isLockedOut) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan masuk. Silakan coba lagi dalam ' . $this->lockSeconds . ' detik.',
        ]);
    }

    protected function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    public function render()
    {
        return <<<'HTML'
        <div class="min-h-screen flex bg-white sm:bg-gray-50">
            <!-- BAGIAN KIRI: BRANDING -->
            <div class="hidden lg:flex lg:w-1/2 bg-blue-700 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-blue-700 opacity-90"></div>
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
                
                <div class="relative z-10 w-full flex flex-col justify-center px-16 text-white h-full">
                    <div class="mb-8">
                        <div class="h-16 w-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 mb-6">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2m-2 4h2"></path></svg>
                        </div>
                        <h2 class="text-4xl font-extrabold tracking-tight leading-tight">Sistem Informasi<br>Gereja Kristen Sumba</h2>
                        <p class="mt-4 text-lg text-blue-100 max-w-md font-medium">"Melayani dengan Kasih, Mengelola dengan Transparansi."</p>
                    </div>

                    <div class="space-y-4 text-sm text-blue-50 font-medium">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                            <span>Data Jemaat Terpadu</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                            <span>Laporan Keuangan Real-time</span>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-8 text-xs text-blue-300 opacity-60">
                        &copy; {{ date('Y') }} SIG-GKS Development Team.
                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN: FORM LOGIN -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 relative">
                <div class="w-full max-w-md space-y-8">
                    <div class="text-center lg:text-left">
                        <div class="inline-flex lg:hidden justify-center items-center w-14 h-14 rounded-2xl bg-blue-600 text-white mb-6 shadow-lg shadow-blue-500/30">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2m-2 4h2"></path></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
                        <p class="mt-2 text-sm text-gray-500">Silakan masuk menggunakan akun personil Anda.</p>
                    </div>

                    <!-- Warning for Lockout -->
                    @if($this->isLockedOut)
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-center gap-3 animate-pulse">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span class="text-xs font-bold text-red-700">Akses dibatasi. Coba lagi dalam {{ $this->lockSeconds }} detik.</span>
                    </div>
                    @endif

                    <form wire:submit.prevent="login" class="mt-8 space-y-6">
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-bold text-gray-700 ml-1">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input wire:model="email" id="email" type="email" required autofocus
                                    class="appearance-none block w-full pl-11 pr-3 py-3.5 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 sm:text-sm transition-all shadow-sm"
                                    placeholder="nama@gereja.id">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-bold text-gray-700 ml-1">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v16a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input wire:model="password" id="password" type="password" required
                                    class="appearance-none block w-full pl-11 pr-3 py-3.5 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 sm:text-sm transition-all shadow-sm"
                                    placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input wire:model="remember" id="remember-me" type="checkbox" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none font-medium">Ingat saya</label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                wire:loading.attr="disabled"
                                {{ $this->isLockedOut ? 'disabled' : '' }}
                                class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white transition-all transform active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed {{ $this->isLockedOut ? 'bg-gray-400 shadow-none pointer-events-none' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30' }}">
                                <span wire:loading.remove>
                                    @if($this->isLockedOut)
                                        Terkunci ({{ $this->lockSeconds }}s)
                                    @else
                                        Masuk ke Sistem
                                    @endif
                                </span>
                                
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memverifikasi...
                                </span>
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-400">
                            Masalah saat login? Hubungi Administrator SIG GKS.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
