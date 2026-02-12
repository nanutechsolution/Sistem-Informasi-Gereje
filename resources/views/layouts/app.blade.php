@php
    /**
     * Mengambil konfigurasi tema dari database.
     */
    $theme = \App\Models\ChurchSetting::current();
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
            --primary: {{ $theme->color_primary ?? '#1e3a8a' }};
            --accent: {{ $theme->color_accent ?? '#d97706' }};
            --surface: {{ $theme->color_background ?? '#f8fafc' }};
            --sidebar: {{ $theme->color_sidebar ?? '#0f172a' }};
            --radius-ui: {{ $theme->ui_rounded ?? '1rem' }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        .animate-fade-in-up {
            animation: fadeInUp 0.2s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-scrollbar::-webkit-scrollbar { width: 4px; }
        .dropdown-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .dropdown-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased h-full">

    <!-- 1. NOTIFIKASI TOAST -->
    <div x-data="{ 
            show: false, message: '', type: 'success', timeout: null,
            notify(msg, type = 'success') {
                this.show = true; this.message = msg; this.type = type;
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.show = false }, 5000);
            }
        }"
        @notify.window="notify($event.detail.message, $event.detail.type)"
        class="fixed z-[100] top-4 right-4 left-4 sm:left-auto sm:w-[400px] pointer-events-none">
        <div x-show="show" x-cloak x-transition class="pointer-events-auto bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-4 flex items-center gap-4">
                <div :class="type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'" class="h-10 w-10 shrink-0 rounded-xl flex items-center justify-center">
                    <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div class="flex-1 text-xs font-medium text-slate-500" x-text="message"></div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        </div>
    </div>

    <!-- 2. NAVBAR -->
    <nav x-data="{ open: false }" class="bg-primary text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">

                <!-- BRANDING -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="h-10 w-10 bg-accent rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                            <img src="{{$theme->logo_path}}" alt="">
                            <!-- <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2"></path></svg> -->
                        </div>
                        <div class="leading-none">
                            <span class="block text-lg font-extrabold tracking-tight">SIG-GKS</span>
                            <span class="block text-[9px] font-bold uppercase tracking-widest text-blue-300">{{$theme->nama_jemaat}}</span>
                        </div>
                    </a>

                    <!-- DESKTOP MENU -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-blue-100 hover:bg-white/5' }}">Home</a>

                        <!-- Pilar 1: Administrasi -->
                        @canany(['manage_database'])
                        <div class="relative" x-data="{ drop: false }" @click.away="drop = false">
                            <button @click="drop = !drop" 
                                :class="{'bg-white/10 text-white shadow-inner': drop || {{ request()->is('members*', 'families*', 'officers*', 'letters*', 'clerical*', 'sermons*') ? 'true' : 'false' }} }"
                                class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm font-bold text-blue-100 hover:bg-white/5 transition">
                                Administrasi <svg class="w-4 h-4 transition-transform" :class="drop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="drop" x-cloak class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-3 text-slate-700 ring-1 ring-black/5 animate-fade-in-up dropdown-scrollbar max-h-[85vh] overflow-y-auto">
                                <div class="px-4 py-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Database Jemaat</div>
                                <a href="{{ route('members.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('members.*') ? 'text-primary bg-primary/5 border-l-4 border-primary' : '' }}">Data Anggota</a>
                                <a href="{{ route('families.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('families.*') ? 'text-primary bg-primary/5 border-l-4 border-primary' : '' }}">Data Keluarga</a>
                                <a href="{{ route('officers.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('officers.*') ? 'text-primary bg-primary/5 border-l-4 border-primary' : '' }}">Pejabat & Pelayan</a>
                                
                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kearsipan</div>
                                <a href="{{ route('letters.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('letters*') ? 'text-primary bg-primary/5' : '' }}">Persuratan</a>
                                <a href="{{ route('clerical.sacraments') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('clerical/sacraments*') ? 'text-primary bg-primary/5' : '' }}">Administrasi Sakramen</a>
                                <a href="{{ route('clerical.documents') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('clerical/documents*') ? 'text-primary bg-primary/5' : '' }}">Arsip Dokumen</a>
                                <a href="{{ route('sermons.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('sermons*') ? 'text-primary bg-primary/5' : '' }}">Video Khotbah</a>
                            </div>
                        </div>
                        @endcanany

                        <!-- Pilar 2: Pelayanan -->
                        <div class="relative" x-data="{ drop: false }" @click.away="drop = false">
                            <button @click="drop = !drop" 
                                :class="{'bg-white/10 text-white shadow-inner': drop || {{ request()->is('schedules*', 'news*', 'pastoral*') ? 'true' : 'false' }} }"
                                class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm font-bold text-blue-100 hover:bg-white/5 transition">
                                Pelayanan <svg class="w-4 h-4 transition-transform" :class="drop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="drop" x-cloak class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl py-3 text-slate-700 ring-1 ring-black/5 animate-fade-in-up dropdown-scrollbar max-h-[85vh] overflow-y-auto">
                                <a href="{{ route('schedules.my') }}" class="mx-3 mb-3 px-3 py-2.5 rounded-xl bg-primary/5 text-primary flex items-center justify-between hover:bg-primary hover:text-white transition-all shadow-sm {{ request()->routeIs('schedules.my') ? 'ring-2 ring-primary/20' : '' }}">
                                    <span class="text-sm font-bold">Tugas Saya</span>
                                    <span class="px-1.5 py-0.5 rounded bg-accent text-white text-[9px] font-black uppercase tracking-tighter">Personal</span>
                                </a>
                                
                                <div class="px-4 py-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Agenda</div>
                                <a href="{{ route('schedules.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('schedules.index') ? 'text-primary bg-primary/5' : '' }}">Agenda Jemaat</a>
                                <a href="{{ route('schedules.groups') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('schedules.groups') ? 'text-primary bg-primary/5' : '' }}">Kelompok Pelayanan</a>
                                <a href="{{ route('news.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('news*') ? 'text-primary bg-primary/5' : '' }}">Warta Jemaat</a>
                                
                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pastoral</div>
                                <a href="{{ route('pastoral.visits') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('pastoral/visits*') ? 'text-primary bg-primary/5' : '' }}">Kunjungan Pastoral</a>
                                <a href="{{ route('pastoral.prayers') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('pastoral/prayers*') ? 'text-primary bg-primary/5' : '' }}">Permintaan Doa</a>
                                <a href="{{ route('diakonia') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-emerald-700">Diakonia</a>

                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-amber-600 uppercase tracking-widest italic">Kategorial (PKS)</div>
                                <a href="{{ route('schedules.pks') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('*pks') ? 'bg-amber-50 text-amber-700' : '' }}">Input Jadwal PKS</a>
                                @can('approve_transaction')
                                <a href="{{ route('schedules.pks.verify') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-amber-700 font-bold {{ request()->is('*verify*') ? 'bg-amber-100' : '' }}">Verifikasi PKS</a>
                                @endcan
                            </div>
                        </div>

                        <!-- Pilar 3: Keuangan -->
                        @canany(['manage_finance'])
                        <div class="relative" x-data="{ drop: false }" @click.away="drop = false">
                            <button @click="drop = !drop" 
                                :class="{'bg-white/10 text-white shadow-inner': drop || {{ request()->is('transactions*', 'finance*', 'auctions*', 'budgets*', 'settings/accounts*', 'settings/budget-posts*', 'settings/due-types*') ? 'true' : 'false' }} }"
                                class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm font-bold text-blue-100 hover:bg-white/5 transition">
                                Keuangan <svg class="w-4 h-4 transition-transform" :class="drop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="drop" x-cloak class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl py-3 text-slate-700 ring-1 ring-black/5 animate-fade-in-up dropdown-scrollbar max-h-[85vh] overflow-y-auto">
                                <div class="px-4 py-1 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Operasional</div>
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('transactions*') ? 'text-emerald-700 bg-emerald-50' : '' }}">Kas & Jurnal Umum</a>
                                <a href="{{ route('finance.payroll') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('finance/payroll*') ? 'text-emerald-700 bg-emerald-50' : '' }}">Gaji (Payroll)</a>
                                <a href="{{ route('auctions.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('auctions*') ? 'text-emerald-700 bg-emerald-50' : '' }}">Lelang</a>
                                <a href="{{ route('auctions.receivables') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Piutang Lelang</a>
                                <a href="{{ route('finance.flexible-dues') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-blue-600 {{ request()->is('finance/flexible-dues*') ? 'bg-blue-50' : '' }}">Tanggungan Jemaat</a>
                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Perencanaan</div>
                                <a href="{{ route('budgets.manage') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->is('budgets*') ? 'bg-slate-50' : '' }}">RAPB Jemaat</a>
                                <a href="{{ route('settings.accounts.dompet') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Dompet & Rekening</a>
                                <a href="{{ route('settings.budget-posts') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pos Anggaran</a>
                                <a href="{{ route('finance.opening-balances') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Saldo Awal</a>
                                <a href="{{ route('settings.due-types') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50">Jenis Iuran</a>
                            </div>
                        </div>
                        @endcanany

                        <!-- Pilar 4: Laporan -->
                        @canany(['view_reports'])
                        <div class="relative" x-data="{ drop: false }" @click.away="drop = false">
                            <button @click="drop = !drop" 
                                :class="{'bg-white/10 text-white shadow-inner': drop || {{ request()->is('reports*') ? 'true' : 'false' }} }"
                                class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm font-bold text-blue-100 hover:bg-white/5 transition">
                                Laporan <svg class="w-4 h-4 transition-transform" :class="drop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="drop" x-cloak class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-3 text-slate-700 ring-1 ring-black/5 animate-fade-in-up dropdown-scrollbar max-h-[85vh] overflow-y-auto">
                                <div class="px-4 py-1 text-[10px] font-black text-indigo-600 uppercase tracking-widest">Laporan Jemaat</div>
                                <a href="{{ route('reports.weekly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('reports.weekly') ? 'bg-indigo-50 text-indigo-700' : '' }}">Warta Mingguan</a>
                                <a href="{{ route('reports.monthly') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 {{ request()->routeIs('reports.monthly') ? 'bg-indigo-50 text-indigo-700' : '' }}">Laporan Bulanan</a>
                                <a href="{{ route('reports.census') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 text-indigo-700 {{ request()->routeIs('reports.census') ? 'bg-indigo-100' : '' }}">Laporan Sensus</a>
                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-indigo-600 uppercase tracking-widest">Laporan Keuangan</div>
                                <a href="{{ route('reports.budget-realization') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-slate-50 italic font-bold text-rose-600 {{ request()->routeIs('reports.budget-realization') ? 'bg-rose-50' : '' }}">Realisasi RAPB</a>
                            </div>
                        </div>
                        @endcanany

                        <!-- Pilar 5: Sistem (Admin) -->
                        @role('admin')
                        <div class="relative" x-data="{ drop: false }" @click.away="drop = false">
                            <button @click="drop = !drop" 
                                :class="{'bg-white/20 text-white': drop || {{ request()->is('users*', 'settings*') ? 'true' : 'false' }} }"
                                class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm font-bold text-blue-100 hover:bg-white/5 transition border border-white/20">
                                Sistem <svg class="w-4 h-4 transition-transform" :class="drop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="drop" x-cloak class="absolute right-0 mt-2 w-64 bg-slate-900 rounded-2xl shadow-2xl py-3 text-slate-300 ring-1 ring-white/10 animate-fade-in-up">
                                <div class="px-4 py-1 text-[10px] font-black text-slate-500 uppercase tracking-widest">Keamanan</div>
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white {{ request()->is('users*') ? 'bg-white/10 text-white' : '' }}">Manajemen User</a>
                                <a href="{{ route('settings.roles') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white text-accent {{ request()->is('settings/roles*') ? 'bg-white/10' : '' }}">Hak Akses (Roles)</a>
                                
                                <div class="h-px bg-white/10 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-slate-500 uppercase tracking-widest">Konfigurasi</div>
                                <a href="{{ route('settings.assets') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">Aset Gereja</a>
                                <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">Profil Jemaat</a>
                                
                                <div class="h-px bg-white/10 my-2 mx-4"></div>
                                <div class="px-4 py-1 text-[10px] font-black text-slate-500 uppercase tracking-widest">Master Data Referensi</div>
                                <a href="{{ route('settings.master', 'wilayah') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">Wilayah Pelayanan</a>
                                <a href="{{ route('settings.positions') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">Jabatan Organisasi</a>
                                <a href="{{ route('settings.activity-types') }}" class="block px-4 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">Kategori Kegiatan</a>
                            </div>
                        </div>
                        @endrole
                    </div>
                </div>

                <!-- USER MENU -->
                <div class="flex items-center gap-4">
                    <div class="hidden lg:block text-right pr-4 border-r border-white/10">
                        <div class="text-xs font-bold leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-[9px] text-accent font-black uppercase mt-1 italic tracking-widest">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
                        @csrf
                        <button type="submit" class="p-2.5 rounded-xl bg-white/10 hover:bg-rose-500 transition-colors" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                        </button>
                    </form>
                    
                    <!-- Hamburger Mobile -->
                    <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl bg-white/10 border border-white/20 transition-all hover:scale-95 active:scale-90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU (Full Overlay & Accordion) -->
        <div x-show="open" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="lg:hidden fixed inset-0 z-[60] bg-primary overflow-y-auto pb-10">
            
            <!-- Mobile Header / Close Button -->
            <div class="sticky top-0 bg-primary/95 backdrop-blur-md px-6 py-5 flex justify-between items-center border-b border-white/10 mb-6">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-accent rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2"></path></svg>
                    </div>
                    <span class="font-bold text-sm tracking-tight uppercase">Menu Navigasi</span>
                </div>
                <button @click="open = false" class="p-2 rounded-xl bg-white/10 border border-white/20 hover:bg-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-6 space-y-4">
                <!-- Utama Quick Access -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('dashboard') }}" @click="open = false" 
                       class="p-5 rounded-3xl text-center transition {{ request()->routeIs('dashboard') ? 'bg-accent text-white shadow-lg' : 'bg-white/5 border border-white/10' }}">
                        <div class="text-2xl mb-1">🏠</div>
                        <div class="text-[10px] font-black uppercase tracking-widest">Beranda</div>
                    </a>
                    <a href="{{ route('schedules.my') }}" @click="open = false" 
                       class="p-5 rounded-3xl text-center shadow-xl transition {{ request()->routeIs('schedules.my') ? 'bg-white text-primary' : 'bg-accent text-white shadow-accent/20' }}">
                        <div class="text-2xl mb-1">📅</div>
                        <div class="text-[10px] font-black uppercase tracking-widest leading-none">Tugas Saya</div>
                    </a>
                </div>

                <!-- Accordion Administrasi -->
                @canany(['manage_database'])
                <div x-data="{ group: {{ request()->is('members*', 'families*', 'officers*', 'letters*', 'clerical*', 'sermons*') ? 'true' : 'false' }} }" 
                     class="bg-white/5 rounded-3xl border border-white/5 overflow-hidden">
                    <button @click="group = !group" class="w-full flex items-center justify-between p-5 text-blue-300 text-[11px] font-black uppercase tracking-widest">
                        <span>Administrasi & Data</span>
                        <svg class="w-4 h-4 transition-transform" :class="group ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="group" x-cloak x-collapse class="bg-white/5 grid grid-cols-2 gap-2 p-3 border-t border-white/5">
                        <a href="{{ route('members.index') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('members.*') ? 'bg-white/20 shadow-inner' : '' }}">JEMAAT</a>
                        <a href="{{ route('families.index') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('families.*') ? 'bg-white/20 shadow-inner' : '' }}">KELUARGA</a>
                        <a href="{{ route('officers.index') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('officers.*') ? 'bg-white/20 shadow-inner' : '' }}">PELAYAN</a>
                        <a href="{{ route('letters.index') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->is('letters*') ? 'bg-white/20 shadow-inner' : '' }}">SURAT</a>
                    </div>
                </div>
                @endcanany

                <!-- Accordion Pelayanan -->
                <div x-data="{ group: {{ request()->is('schedules*', 'news*', 'pastoral*') ? 'true' : 'false' }} }" 
                     class="bg-white/5 rounded-3xl border border-white/5 overflow-hidden">
                    <button @click="group = !group" class="w-full flex items-center justify-between p-5 text-accent text-[11px] font-black uppercase tracking-widest">
                        <span>Pelayanan & Agenda</span>
                        <svg class="w-4 h-4 transition-transform" :class="group ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="group" x-cloak x-collapse class="bg-white/5 space-y-1 p-3 border-t border-white/5">
                        <a href="{{ route('schedules.index') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/10 text-xs font-bold uppercase tracking-tight {{ request()->routeIs('schedules.index') ? 'bg-white/10' : '' }}">Agenda Jemaat</a>
                        <a href="{{ route('pastoral.visits') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/10 text-xs font-bold uppercase tracking-tight {{ request()->is('pastoral/visits*') ? 'bg-white/10' : '' }}">Kunjungan Pastoral</a>
                        <a href="{{ route('schedules.pks') }}" @click="open = false" class="block p-3 rounded-2xl bg-accent/20 text-accent text-xs font-bold uppercase tracking-tight {{ request()->is('*pks') ? 'bg-accent/40 shadow-inner' : '' }}">Input Jadwal PKS</a>
                    </div>
                </div>

                <!-- Accordion Keuangan -->
                @canany(['manage_finance'])
                <div x-data="{ group: {{ request()->is('transactions*', 'finance*', 'auctions*', 'budgets*') ? 'true' : 'false' }} }" 
                     class="bg-white/5 rounded-3xl border border-white/5 overflow-hidden">
                    <button @click="group = !group" class="w-full flex items-center justify-between p-5 text-emerald-400 text-[11px] font-black uppercase tracking-widest">
                        <span>Keuangan & Anggaran</span>
                        <svg class="w-4 h-4 transition-transform" :class="group ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="group" x-cloak x-collapse class="bg-white/5 space-y-1 p-3 border-t border-white/5">
                        <a href="{{ route('transactions.index') }}" @click="open = false" class="block p-3 rounded-2xl bg-emerald-500/10 text-emerald-100 text-xs font-bold uppercase {{ request()->is('transactions*') ? 'bg-emerald-500/30 shadow-inner' : '' }}">Jurnal Kas Umum</a>
                        <a href="{{ route('budgets.manage') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/10 text-xs font-bold uppercase {{ request()->is('budgets*') ? 'bg-white/10' : '' }}">RAPB Jemaat</a>
                        <a href="{{ route('finance.flexible-dues') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/10 text-xs font-bold uppercase {{ request()->is('finance/flexible-dues*') ? 'bg-white/10' : '' }}">Tanggungan Jemaat</a>
                    </div>
                </div>
                @endcanany

                <!-- Accordion Laporan -->
                @canany(['view_reports'])
                <div x-data="{ group: {{ request()->is('reports*') ? 'true' : 'false' }} }" 
                     class="bg-white/5 rounded-3xl border border-white/5 overflow-hidden">
                    <button @click="group = !group" class="w-full flex items-center justify-between p-5 text-indigo-400 text-[11px] font-black uppercase tracking-widest">
                        <span>Pusat Laporan</span>
                        <svg class="w-4 h-4 transition-transform" :class="group ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="group" x-cloak x-collapse class="bg-white/5 grid grid-cols-2 gap-2 p-3 border-t border-white/5">
                        <a href="{{ route('reports.weekly') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('reports.weekly') ? 'bg-white/20 shadow-inner' : '' }}">WARTA</a>
                        <a href="{{ route('reports.monthly') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('reports.monthly') ? 'bg-white/20 shadow-inner' : '' }}">BULANAN</a>
                        <a href="{{ route('reports.census') }}" @click="open = false" class="p-3 rounded-2xl bg-white/5 text-[10px] font-bold text-center {{ request()->routeIs('reports.census') ? 'bg-white/20 shadow-inner' : '' }}">SENSUS</a>
                        <a href="{{ route('reports.budget-realization') }}" @click="open = false" class="p-3 rounded-2xl bg-rose-500/20 text-rose-300 text-[10px] font-bold text-center {{ request()->routeIs('reports.budget-realization') ? 'bg-rose-500/40 shadow-inner' : '' }}">RAPB</a>
                    </div>
                </div>
                @endcanany

                <!-- Accordion Admin Sistem -->
                @role('admin')
                <div x-data="{ group: {{ request()->is('users*', 'settings*') ? 'true' : 'false' }} }" 
                     class="bg-slate-900 rounded-3xl border border-white/10 overflow-hidden">
                    <button @click="group = !group" class="w-full flex items-center justify-between p-5 text-slate-400 text-[11px] font-black uppercase tracking-widest">
                        <span>Sistem & Admin</span>
                        <svg class="w-4 h-4 transition-transform" :class="group ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="group" x-cloak x-collapse class="p-3 border-t border-white/5 space-y-1">
                        <a href="{{ route('users.index') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/5 text-xs font-bold uppercase {{ request()->is('users*') ? 'bg-white/10 text-white' : '' }}">Manajemen User</a>
                        <a href="{{ route('settings.roles') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/5 text-xs font-bold uppercase text-accent {{ request()->is('settings/roles*') ? 'bg-white/10' : '' }}">Hak Akses (Roles)</a>
                        <a href="{{ route('settings.master', 'wilayah') }}" @click="open = false" class="block p-3 rounded-2xl hover:bg-white/5 text-xs font-bold uppercase {{ request()->is('settings/master*') ? 'bg-white/10' : '' }}">Wilayah Pelayanan</a>
                    </div>
                </div>
                @endrole

                <!-- Logout Mobile Footer -->
                <div class="pt-8 text-center pb-20">
                    <div class="mb-6 flex items-center justify-center gap-4 bg-white/5 p-4 rounded-3xl border border-white/5">
                        <div class="h-12 w-12 rounded-2xl bg-accent flex items-center justify-center text-primary font-black text-xl">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="text-left">
                            <div class="text-sm font-bold text-white">{{ auth()->user()->name }}</div>
                            <div class="text-[9px] text-blue-300 font-black uppercase tracking-widest">{{ auth()->user()->getRoleNames()->first() }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-rose-600 rounded-2xl font-black text-white shadow-xl hover:scale-[0.98] transition-all">KELUAR SISTEM</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. MAIN CONTENT -->
    <main class="min-h-[calc(100vh-80px)]">
        {{ $slot }}
    </main>

    <!-- 4. FOOTER -->
    <footer class="hidden sm:block bg-white border-t border-slate-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center text-slate-400">
            <p class="text-xs font-bold uppercase tracking-widest italic">&copy; {{ date('Y') }} GKS {{$theme->nama_jemaat}} - SIG</p>
            <div class="flex items-center gap-4">
                <span class="text-[9px] font-black bg-slate-50 px-2 py-1 rounded text-slate-400 border border-slate-100 tracking-tighter uppercase">Versi Aplikasi 2.9.5</span>
            </div>
        </div>
    </footer>
</body>
</html>