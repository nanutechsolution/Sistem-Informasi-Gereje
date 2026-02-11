@php
// Ambil konfigurasi gereja dari database, gunakan default jika belum ada
$setting = \App\Models\ChurchSetting::first() ?? new \App\Models\ChurchSetting([
'nama_gereja' => 'Gereja Kristen Sumba',
'nama_jemaat' => 'Jemaat Reda Pada',
'warna_utama' => '#1e3a8a', // Biru Default
'warna_aksen' => '#d97706', // Emas Default
'alamat' => 'Jl. Lolo Ole, Sumba Barat Daya',
'email' => 'sekretariat@gksredapada.org'
]);
@endphp
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
        :root {
        --primary: {{ $setting->warna_utama ?? '#1e3a8a' }};
        --accent: {{ $setting->warna_aksen ?? '#d97706' }};
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

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
        class="fixed z-[100] top-4 right-4 left-4 sm:left-auto sm:w-[400px] pointer-events-none">
        <div x-show="show" x-cloak x-transition class="pointer-events-auto bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-4 flex items-center gap-4">
                <div :class="{'bg-emerald-100 text-emerald-600': type === 'success', 'bg-rose-100 text-rose-600': type === 'error'}" class="h-12 w-12 shrink-0 rounded-xl flex items-center justify-center">
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
                <button @click="show = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
        </div>
    </div>

    <!-- 2. NAVBAR UTAMA -->
    <nav x-data="{ open: false, dbOpen: false, serviceOpen: false, financeOpen: false, adminOpen: false }" class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">

                <!-- BRANDING -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="h-11 w-11 bg-accent rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path>
                            </svg>
                        </div>
                        <div class="leading-none">
                            <span class="block text-lg font-extrabold tracking-tight">SIG-GKS</span>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-blue-300">Jemaat Reda Pada</span>
                        </div>
                    </a>

                    <!-- DESKTOP MENU -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">Home</a>

                        <!-- 1. DATABASE (Sekretaris & Admin) -->
                        @can('manage_database')
                        <div class="relative" @click.away="dbOpen = false">
                            <button @click="dbOpen = !dbOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('members*') || request()->is('families*') || request()->is('officers*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Database <svg class="w-4 h-4 transition-transform" :class="dbOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="dbOpen" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('members.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Data Jemaat (Jiwa)</a>
                                <a href="{{ route('families.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Data Keluarga (KK)</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('officers.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-primary">Pejabat & Pelayan</a>
                                <a href="{{ route('pastoral.visits') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-primary">Kunjungan</a>
                                <a href="{{ route('news.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Warta</a>
                                <a href="{{ route('clerical.documents') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Managemen Dokumen</a>
                                <a href="{{ route('sermons.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Managemen Video Khotbah</a>
                            </div>
                        </div>
                        @endcan

                        <!-- 2. PELAYANAN (Campuran) -->
                        <div class="relative" @click.away="serviceOpen = false">
                            <button @click="serviceOpen = !serviceOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('schedules*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Pelayanan <svg class="w-4 h-4 transition-transform" :class="serviceOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="serviceOpen" x-cloak class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <!-- Jadwal Umum (Sekretaris) -->
                                @can('manage_schedules')
                                <a href="{{ route('schedules.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Agenda Jemaat</a>
                                <a href="{{ route('schedules.groups') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Kelompok Pelayanan</a>
                                @endcan
                                <!-- Input PKS (Majelis & Sekretaris) -->
                                @can('input_pks')
                                <a href="{{ route('schedules.pks') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jadwal PKS</a>
                                @endcan
                                <a href="{{ route('pastoral.prayers') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Permintaan Doa</a>

                                <!-- Tugas Saya (Semua Pelayan) -->
                                <a href="{{ route('schedules.my') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 flex items-center justify-between">
                                    Tugas Saya
                                    <span class="px-1.5 py-0.5 rounded-md bg-accent text-primary text-[9px] font-black uppercase">Personal</span>
                                </a>

                                <!-- Verifikasi (Bendahara) -->
                                @can('approve_transaction')
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('schedules.pks.verify') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-amber-600 italic">Verifikasi Kolekte PKS</a>
                                @endcan

                                <!-- Laporan Warta (Semua) -->
                                @can('view_reports')
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('reports.weekly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Laporan Warta Jemaat</a>
                                <a href="{{ route('reports.monthly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Laporan Bulanan</a>
                                <a href="{{ route('reports.census') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Laporan Sensus Jemaat</a>
                                @endcan
                                <a href="{{ route('letters.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Surat Menyurat</a>
                                <a href="{{ route('clerical.sacraments') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Administrasi Sakramen</a>
                            </div>
                        </div>

                        <!-- 3. KEUANGAN (Bendahara & Admin) -->
                        @can('manage_finance')
                        <div class="relative" @click.away="financeOpen = false">
                            <button @click="financeOpen = !financeOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('transactions*') || request()->is('auctions*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Keuangan <svg class="w-4 h-4 transition-transform" :class="financeOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="financeOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jurnal Kas Umum</a>
                                <a href="{{ route('auctions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Lelang</a>
                                <a href="{{ route('auctions.receivables') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Piutang Lelang</a>
                                <a href="{{ route('finance.payroll') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Payroll / Gaji</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('reports.budget-realization') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Laporan Realisasi</a>
                                <a href="{{ route('reports.general-ledger') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Buku Kas Umum (BKU)</a>
                                <a href="{{ route('finance.flexible-dues') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Tanggungan Jemaat</a>
                                <a href="{{ route('finance.diakonia') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Diakonia</a>
                            </div>
                        </div>
                        @endcan

                        <!-- 4. SISTEM (Admin Only) -->
                        @if(auth()->user()->hasRole('admin') || auth()->user()->can('manage_finance'))
                        <div class="relative" @click.away="adminOpen = false">
                            <button @click="adminOpen = !adminOpen" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->is('settings*') || request()->is('budgets*') ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/5' }}">
                                Sistem
                                <svg class="w-4 h-4 transition-transform" :class="adminOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="adminOpen" x-cloak class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 text-slate-700 ring-1 ring-black/5 animate-fade-in-down">
                                @role('admin')
                                <a href="{{ route('budgets.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pengaturan RAPB</a>
                                <a href="{{ route('settings.accounts.dompet') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Dompet</a>
                                <a href="{{ route('settings.budget-posts') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pos Anggaran</a>
                                <a href="{{ route('finance.opening-balances') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Saldo Awal Tahun</a>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen User</a>
                                <a href="{{ route('settings.roles') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Hak Akses</a>
                                <a href="{{ route('settings.master', 'wilayah') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Data Wilayah</a>
                                <a href="{{ route('settings.activity-types') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jenis Kegiatan</a>
                                <a href="{{ route('settings.positions') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Master Jabatan</a>
                                <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pengaturan Profil</a>
                                @endrole
                                @can('manage_finance')
                                <a href="{{ route('settings.due-types') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Master Iuran</a>
                                @endcan
                                <a href="{{ route('settings.assets') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manajemen Aset</a>

                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- INFO USER & LOGOUT -->
                <div class="flex items-center gap-4">
                    <div class="hidden lg:flex items-center gap-3 pl-6 border-l border-white/10">
                        <div class="text-right">
                            <div class="text-sm font-bold leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-accent font-extrabold uppercase mt-1 tracking-widest italic">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 hover:bg-rose-500 hover:border-rose-400 transition shadow-sm" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- HAMBURGER MOBILE -->
                    <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl bg-white/10 border border-white/20 hover:scale-95 transition-all">
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

        <!-- MENU MOBILE (Full Screen) -->
        <div x-show="open" x-cloak x-transition class="lg:hidden fixed inset-0 z-40 bg-primary/98 backdrop-blur-xl pt-24 px-6 overflow-y-auto">
            <div class="space-y-6 pb-20">

                <!-- Home -->
                <a href="{{ route('dashboard') }}" class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center flex flex-col items-center gap-2">
                    <span class="text-xl">🏠</span><span class="text-xs font-bold uppercase tracking-widest">Dashboard</span>
                </a>

                <!-- Database (Admin/Sekretaris) -->
                @can('manage_database')
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest pl-2">Database Jemaat</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('members.index') }}" class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center"><span class="text-xl">👥</span><br><span class="text-[10px] font-bold uppercase">Jemaat</span></a>
                        <a href="{{ route('families.index') }}" class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center"><span class="text-xl">🏠</span><br><span class="text-[10px] font-bold uppercase">Keluarga</span></a>
                    </div>
                </div>
                @endcan

                <!-- Pelayanan (PKS) -->
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-accent uppercase tracking-widest pl-2 italic">Pelayanan</p>
                    <div class="bg-white/5 rounded-3xl border border-white/10 overflow-hidden divide-y divide-white/5">
                        <a href="{{ route('schedules.my') }}" class="block p-4 text-sm font-bold text-white flex justify-between">Tugas Saya <span class="bg-accent text-primary px-1 rounded text-[8px] font-black">NEW</span></a>
                        @can('input_pks') <a href="{{ route('schedules.pks') }}" class="block p-4 text-sm font-bold text-white">Input Jadwal PKS</a> @endcan
                        @can('approve_transaction') <a href="{{ route('schedules.pks.verify') }}" class="block p-4 text-sm font-bold text-amber-300">Verifikasi Setoran</a> @endcan
                    </div>
                </div>

                <!-- Keuangan -->
                @can('manage_finance')
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-emerald-300 uppercase tracking-widest pl-2">Keuangan</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('transactions.index') }}" class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-center"><span class="text-xl">💰</span><br><span class="text-[10px] font-bold uppercase">Kas</span></a>
                        <a href="{{ route('finance.payroll') }}" class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-center"><span class="text-xl">💸</span><br><span class="text-[10px] font-bold uppercase">Gaji</span></a>
                    </div>
                </div>
                @endcan

                <!-- Logout Mobile -->
                <div class="pt-6 border-t border-white/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-14 w-14 rounded-2xl bg-accent flex items-center justify-center text-primary font-black text-xl">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="flex-1">
                            <div class="text-lg font-extrabold leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-blue-300 font-bold uppercase mt-2 italic">{{ auth()->user()->getRoleNames()->first() ?? '-' }}</div>
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
            <p class="text-sm font-bold text-slate-400">&copy; {{ date('Y') }} SIG-GKS Jemaat Reda Pada</p>
        </div>
    </footer>
</body>

</html>