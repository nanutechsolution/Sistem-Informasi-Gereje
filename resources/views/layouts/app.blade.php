<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'SIG-GKS' }} | Sistem Informasi Gereja</title>

    <!-- Aset Tailwind & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Plus Jakarta Sans (Sangat Modern & Bersih) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Scrollbar styling untuk kesan premium */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased h-full">

    <!-- 1. SISTEM NOTIFIKASI TOAST (GLOBAL) -->
    <div
        x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            timeout: null,
            notify(msg, type = 'success') {
                this.show = true;
                this.message = msg;
                this.type = type;
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.show = false }, 5000);
            }
        }"
        @notify.window="notify($event.detail.message, $event.detail.type)"
        class="fixed z-[100] top-4 right-4 left-4 sm:left-auto sm:w-[400px] pointer-events-none">
        <div
            x-show="show"
            x-cloak
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-[-20px] opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-4 flex items-center gap-4">
                <div :class="{
                    'bg-emerald-100 text-emerald-600': type === 'success',
                    'bg-rose-100 text-rose-600': type === 'error',
                    'bg-amber-100 text-amber-600': type === 'warning'
                }" class="h-12 w-12 shrink-0 rounded-xl flex items-center justify-center">
                    <template x-if="type === 'success'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg></template>
                    <template x-if="type === 'error'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg></template>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-900" x-text="type === 'success' ? 'Berhasil' : 'Pemberitahuan'"></p>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium" x-text="message"></p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="h-1 w-full bg-slate-50">
                <div :class="type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'" class="h-full transition-all duration-[5000ms] ease-linear w-0" :style="show ? 'width: 100%' : 'width: 0%'"></div>
            </div>
        </div>
    </div>

    <!-- 2. NAVBAR MODERN (MOBILE FIRST) -->
    <nav x-data="{ open: false, profileOpen: false, financeOpen: false, reportOpen: false, settingsOpen: false }" class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-18 py-3 sm:py-0 sm:h-20">

                <!-- Kiri: Logo & Branding -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="h-10 w-10 sm:h-11 sm:w-11 bg-accent rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-lg font-extrabold tracking-tight leading-none">SIG GKS</span>
                            <span class="block text-[10px] text-blue-200 font-bold tracking-widest uppercase mt-1">Jemaat Reda</span>
                        </div>
                    </a>

                    <!-- Menu Desktop -->
                    <div class="hidden lg:flex items-center ml-10 space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">Dashboard</a>
                        <a href="{{ route('members.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('members.*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">Jemaat</a>
                        <a href="{{ route('families.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('families.*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">Keluarga</a>
                        <a href="{{ route('auctions.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('auctions.*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">Manajemen Lelang</a>
                        <a href="{{ route('officers.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('officers.*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">Pegawai</a>

                        <!-- Dropdown Keuangan -->
                        <div class="relative" @click.away="financeOpen = false">
                            <button @click="financeOpen = !financeOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('transactions*') || request()->is('finance*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">
                                Keuangan
                                <svg class="w-4 h-4 transition-transform duration-200" :class="financeOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="financeOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Jurnal Transaksi</a>
                                <a href="{{ route('budgets.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Anggaran (RAPB)</a>
                                <a href="{{ route('finance.opening-balances') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Saldo Awal</a>
                                <a href="{{ route('settings.accounts.dompet') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Dompet</a>
                                <a href="{{ route('finance.payroll') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Payrol</a>

                            </div>
                        </div>

                        <!-- Dropdown Laporan -->
                        <div class="relative" @click.away="reportOpen = false">
                            <button @click="reportOpen = !reportOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('reports*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">
                                Laporan
                                <svg class="w-4 h-4 transition-transform duration-200" :class="reportOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="reportOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('reports.weekly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Laporan Mingguan</a>
                                <a href="{{ route('reports.budget-realization') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Realisasi Anggaran</a>
                                <a href="{{ route('reports.general-ledger') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 hover:text-primary">Buku Kas Umum</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Profil & Burger -->
                <div class="flex items-center gap-3 sm:gap-5">
                    <!-- Desktop: Info Profil -->
                    <div class="hidden lg:flex items-center gap-3 pl-6 border-l border-white/10" x-data="{ open: false }" @click.away="open = false">
                        <div class="text-right">
                            <div class="text-sm font-bold leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-accent font-extrabold uppercase mt-1 tracking-widest">{{ auth()->user()->role }}</div>
                        </div>
                        <div class="relative">
                            <button @click="open = !open" class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 hover:bg-white/20 transition cursor-pointer overflow-hidden">
                                <span class="font-black text-xs">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </button>
                            <div x-show="open" x-cloak class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5">
                                <a href="{{ route('settings.master', 'wilayah') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pengaturan Sistem</a>
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Personil</a>
                                <a href="{{ route('settings.budget-posts') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Budget Posts</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50 transition">Keluar Sistem</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger Mobile -->
                    <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl bg-white/10 border border-white/20 text-white hover:bg-white/20 transition-all active:scale-90">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MENU MOBILE (Full Screen Blur) -->
        <div x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            class="lg:hidden fixed inset-0 z-40 bg-primary/95 backdrop-blur-xl pt-20 px-6 overflow-y-auto">

            <div class="flex flex-col space-y-2 pb-10">
                <a href="{{ route('dashboard') }}" class="p-4 rounded-2xl text-xl font-bold text-white border border-white/5 flex items-center gap-4 active:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}">
                    <div class="h-10 w-10 rounded-lg bg-blue-500/20 flex items-center justify-center">🏠</div>
                    Dashboard
                </a>
                <a href="{{ route('members.index') }}" class="p-4 rounded-2xl text-xl font-bold text-white border border-white/5 flex items-center gap-4 active:bg-white/10 {{ request()->routeIs('members.*') ? 'bg-white/20' : '' }}">
                    <div class="h-10 w-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">👥</div>
                    Data Jemaat
                </a>
                <a href="{{ route('families.index') }}" class="p-4 rounded-2xl text-xl font-bold text-white border border-white/5 flex items-center gap-4 active:bg-white/10 {{ request()->routeIs('families.*') ? 'bg-white/20' : '' }}">
                    <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center">🏠</div>
                    Data Keluarga
                </a>


                <a href="{{ route('auctions.index') }}" class="p-4 rounded-2xl text-xl font-bold text-white border border-white/5 flex items-center gap-4 active:bg-white/10 {{ request()->routeIs('auctions.*') ? 'bg-white/20' : '' }}">
                    <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center">🏠</div>
                    Manajemen Lelang
                </a>
                <a href="{{ route('officers.index') }}" class="p-4 rounded-2xl text-xl font-bold text-white border border-white/5 flex items-center gap-4 active:bg-white/10 {{ request()->routeIs('officers.*') ? 'bg-white/20' : '' }}">
                    <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center">🏠</div>
                    Pegawai
                </a>
                <!-- Grup Keuangan -->
                <div class="pt-4 pb-2 text-[10px] font-black text-blue-300 uppercase tracking-widest pl-4">Modul Keuangan</div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('transactions.index') }}" class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center flex flex-col items-center gap-2">
                        <span class="text-2xl">💰</span>
                        <span class="text-xs font-bold">Jurnal Kas</span>
                    </a>
                    <a href="{{ route('reports.weekly') }}" class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center flex flex-col items-center gap-2">
                        <span class="text-2xl">📋</span>
                        <span class="text-xs font-bold">Warta Mingguan</span>
                    </a>
                </div>

                <!-- Admin Info & Logout -->
                <div class="mt-8 p-6 bg-white/5 rounded-[32px] border border-white/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-14 w-14 rounded-2xl bg-accent flex items-center justify-center text-primary font-black text-xl">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-lg font-extrabold text-white leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-blue-300 font-bold uppercase mt-2">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('users.index') }}" class="p-3 bg-white/5 rounded-xl text-center text-[10px] font-bold">MANAJEMEN USER</a>
                        <a href="{{ route('settings.master', 'wilayah') }}" class="p-3 bg-white/5 rounded-xl text-center text-[10px] font-bold uppercase">Pengaturan</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-rose-600 rounded-2xl font-bold text-white shadow-xl shadow-rose-900/40">Keluar Sistem</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. KONTEN UTAMA -->
    <main class="min-h-[calc(100vh-80px)]">
        {{ $slot }}
    </main>

    <!-- 4. FOOTER (Hidden on Mobile Dashboard to save space) -->
    <footer class="hidden sm:block bg-white border-t border-slate-200 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-bold text-slate-400">&copy; {{ date('Y') }} Sistem Informasi Gereja Kristen Sumba (SIG-GKS)</p>
            <p class="text-[10px] text-slate-300 mt-2 uppercase tracking-tighter">Dikembangkan untuk kemajuan pelayanan Jemaat Reda Pada</p>
        </div>
    </footer>

</body>

</html>