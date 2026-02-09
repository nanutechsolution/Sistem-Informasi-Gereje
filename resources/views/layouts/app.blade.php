<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'SIG-GKS' }} | Sistem Informasi Gereja</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 antialiased h-full">
    
    <!-- 1. NOTIFIKASI TOAST GLOBAL -->
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
        class="fixed z-[100] top-4 right-4 left-4 sm:left-auto sm:w-[400px] pointer-events-none"
    >
        <div x-show="show" x-cloak x-transition class="pointer-events-auto bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-4 flex items-center gap-4">
                <div :class="{'bg-emerald-100 text-emerald-600': type === 'success', 'bg-rose-100 text-rose-600': type === 'error'}" class="h-12 w-12 shrink-0 rounded-xl flex items-center justify-center">
                    <template x-if="type === 'success'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="type === 'error'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></template>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-900" x-text="type === 'success' ? 'Berhasil' : 'Pemberitahuan'"></p>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium" x-text="message"></p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        </div>
    </div>

    <!-- 2. NAVBAR UTAMA -->
    <nav x-data="{ open: false, dbOpen: false, serviceOpen: false, financeOpen: false, adminOpen: false }" class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <!-- BRANDING & DESKTOP MENU -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <!-- <div class="h-11 w-11 bg-accent rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                             <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                        </div> -->
                        <span class="hidden sm:block text-lg font-extrabold tracking-tight">SIG Jemaat Reda Pada</span>
                    </a>

                    <!-- DESKTOP MENU GROUPING -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">Home</a>
                        
                        <!-- Dropdown Database -->
                        <div class="relative" @click.away="dbOpen = false">
                            <button @click="dbOpen = !dbOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('members*') || request()->is('families*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Database <svg class="w-4 h-4 transition-transform" :class="dbOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="dbOpen" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('members.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Data Jemaat</a>
                                <a href="{{ route('families.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Data Keluarga</a>
                                <a href="{{ route('officers.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Personil/Pegawai</a>
                            </div>
                        </div>

                        <!-- Dropdown Pelayanan -->
                        <div class="relative" @click.away="serviceOpen = false">
                            <button @click="serviceOpen = !serviceOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('schedules*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Pelayanan <svg class="w-4 h-4 transition-transform" :class="serviceOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="serviceOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('schedules.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Agenda Jemaat</a>
                                <a href="{{ route('schedules.my') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jadwal Saya</a>
                                <a href="{{ route('schedules.pks.verify') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Verifikasi Kolekte PKS</a>
                                <a href="{{ route('reports.weekly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Warta Mingguan</a>
                            </div>
                        </div>

                        <!-- Dropdown Keuangan -->
                        <div class="relative" @click.away="financeOpen = false">
                            <button @click="financeOpen = !financeOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('transactions*') || request()->is('auctions*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Keuangan <svg class="w-4 h-4 transition-transform" :class="financeOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="financeOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jurnal Kas Umum</a>
                                <a href="{{ route('auctions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Lelang</a>
                                <a href="{{ route('finance.payroll') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Payroll / Gaji</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('reports.budget-realization') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Laporan Realisasi</a>
                                <a href="{{ route('reports.general-ledger') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Buku Kas Umum (BKU)</a>
                            </div>
                        </div>

                        <!-- Dropdown Admin -->
                        <div class="relative" @click.away="adminOpen = false">
                            <button @click="adminOpen = !adminOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('settings*') || request()->is('budgets*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Admin <svg class="w-4 h-4 transition-transform" :class="adminOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="adminOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('budgets.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pengaturan RAPB</a>
                                <a href="{{ route('settings.accounts.dompet') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Dompet</a>
                                <a href="{{ route('settings.budget-posts') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Struktur Pos Anggaran</a>
                                <a href="{{ route('finance.opening-balances') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Input Saldo Awal</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen User</a>
                                <a href="{{ route('settings.master', 'wilayah') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Master Wilayah</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFO USER & BURGER -->
                <div class="flex items-center gap-4">
                    <div class="hidden lg:flex items-center gap-3 pl-6 border-l border-white/10">
                        <div class="text-right">
                            <div class="text-sm font-bold leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-accent font-extrabold uppercase mt-1 tracking-widest">{{ auth()->user()->role }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 hover:bg-rose-500 hover:border-rose-400 transition shadow-sm" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>

                    <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl bg-white/10 border border-white/20 hover:scale-95 transition-all">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MENU MOBILE (Full Screen Overlay) -->
        <div x-show="open" x-cloak x-transition class="lg:hidden fixed inset-0 z-40 bg-primary/98 backdrop-blur-xl pt-24 px-6 overflow-y-auto">
            <div class="space-y-6 pb-20">
                <!-- Group 1: Core -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('dashboard') }}" class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center flex flex-col items-center gap-2">
                        <span class="text-xl">🏠</span><span class="text-xs font-bold uppercase tracking-widest">Home</span>
                    </a>
                    <a href="{{ route('members.index') }}" class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center flex flex-col items-center gap-2">
                        <span class="text-xl">👥</span><span class="text-xs font-bold uppercase tracking-widest">Jemaat</span>
                    </a>
                </div>

                <!-- Group 2: Keuangan & Laporan -->
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest pl-2">Keuangan & Pelaporan</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('transactions.index') }}" class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-center flex flex-col items-center gap-2">
                            <span class="text-xl">💰</span><span class="text-xs font-bold uppercase tracking-widest">Jurnal Kas</span>
                        </a>
                        <a href="{{ route('reports.weekly') }}" class="p-4 rounded-2xl bg-blue-500/20 border border-blue-500/30 text-center flex flex-col items-center gap-2">
                            <span class="text-xl">📋</span><span class="text-xs font-bold uppercase tracking-widest">Warta</span>
                        </a>
                    </div>
                </div>

                <!-- Group 3: Pelayanan -->
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest pl-2">Agenda & Pelayanan</p>
                    <div class="bg-white/5 rounded-3xl border border-white/10 overflow-hidden divide-y divide-white/5">
                        <a href="{{ route('schedules.index') }}" class="block p-4 text-sm font-bold hover:bg-white/5">Daftar Jadwal</a>
                        <a href="{{ route('schedules.my') }}" class="block p-4 text-sm font-bold hover:bg-white/5">Tugas Saya</a>
                        <a href="{{ route('schedules.pks.verify') }}" class="block p-4 text-sm font-bold hover:bg-white/5 text-amber-300">Verifikasi Kolekte PKS</a>
                    </div>
                </div>

                <!-- Action Bottom -->
                <div class="pt-6 border-t border-white/10">
                    <div class="flex items-center gap-4 mb-6">
                         <div class="h-14 w-14 rounded-2xl bg-accent flex items-center justify-center text-primary font-black text-xl">{{ substr(auth()->user()->name, 0, 1) }}</div>
                         <div class="flex-1">
                            <div class="text-lg font-extrabold leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-blue-300 font-bold uppercase mt-2">{{ auth()->user()->role }}</div>
                         </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-rose-600 rounded-2xl font-bold text-white shadow-xl">Keluar Sistem</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. KONTEN UTAMA -->
    <main class="min-h-[calc(100vh-80px)]">
        {{ $slot }}
    </main>

    <!-- 4. FOOTER -->
    <footer class="hidden sm:block bg-white border-t border-slate-200 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-bold text-slate-400">&copy; {{ date('Y') }} Sistem Informasi SIG-GKS Jemaat Reda Pada</p>
            <p class="text-[10px] text-slate-300 mt-2 uppercase tracking-[0.5em]">Melayani dengan Kasih, Mengelola dengan Transparansi</p>
        </div>
    </footer>

</body>
</html>