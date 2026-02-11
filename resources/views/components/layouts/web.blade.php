@php
    $setting = \App\Models\ChurchSetting::first() ?? new \App\Models\ChurchSetting([
        'nama_gereja' => 'Gereja Kristen Sumba',
        'nama_jemaat' => 'Jemaat Reda Pada',
        'warna_utama' => '#1e3a8a',
        'warna_aksen' => '#d97706',
        'alamat' => 'Jl. Lolo Ole, Sumba Barat Daya',
        'email' => 'sekretariat@gksredapada.org'
    ]);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? $setting->nama_jemaat }} | {{ $setting->nama_gereja }}</title>
    
    <!-- Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '{{ $setting->warna_utama }}', 
                        accent: '{{ $setting->warna_aksen }}',
                        surface: '#FDFDFD',
                        dark: '#0f172a'
                    },
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'], 
                        serif: ['Playfair Display', 'serif'] 
                    },
                    borderRadius: { '4xl': '2rem', '5xl': '3.5rem' }
                }
            }
        }
    </script>

    <style>
        .glass-nav { background: rgba(253, 253, 253, 0.9); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
        .dropdown-enter { animation: slideDown 0.2s ease-out forwards; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-surface text-slate-900 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- 1. NAVIGATION BAR -->
    <nav class="fixed top-0 w-full z-[100] glass-nav transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group z-50">
                    @if($setting->logo_path)
                        <img src="{{ asset('storage/'.$setting->logo_path) }}" class="h-10 w-auto group-hover:scale-105 transition-transform object-contain">
                    @else
                        <div class="h-10 w-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                        </div>
                    @endif
                    <div class="leading-none hidden sm:block">
                        <span class="block text-lg font-extrabold tracking-tight text-primary uppercase italic">{{ $setting->nama_gereja }}</span>
                        <span class="block text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mt-0.5">{{ $setting->nama_jemaat }}</span>
                    </div>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ url('/') }}" class="text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-colors">Beranda</a>

                    <!-- Dropdown: Tentang -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-primary py-6">
                            Tentang <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute top-16 left-0 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 dropdown-enter">
                            <a href="{{ route('public.profile') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Profil Gereja</a>
                            <a href="{{ url('/#galeri') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Galeri Kegiatan</a>
                            <a href="{{ route('public.contact.index') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Hubungi Kami</a>
                        </div>
                    </div>

                    <!-- Dropdown: Informasi -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-primary py-6">
                            Informasi <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute top-16 left-0 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 dropdown-enter">
                            <a href="{{ route('public.warta.index') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Warta Jemaat</a>
                            <a href="{{ route('public.schedules.index') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Jadwal Ibadah</a>
                            <a href="{{ url('/#keuangan') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Keuangan</a>
                        </div>
                    </div>

                    <!-- Dropdown: Layanan -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-primary py-6">
                            Layanan <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute top-16 right-0 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 dropdown-enter">
                            <a href="{{ route('public.prayer') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Permohonan Doa</a>
                            <a href="{{ route('public.downloads') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Download Dokumen</a>
                            <a href="{{ route('public.sermons') }}" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary rounded-xl">Video Khotbah</a>
                        </div>
                    </div>

                    <!-- Portal -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="ml-4 px-6 py-2.5 bg-slate-900 text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-lg hover:bg-primary transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="ml-4 px-6 py-2.5 bg-primary text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5 transition-all">Login</a>
                    @endauth
                </div>

                <!-- Mobile Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-primary focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak x-transition.opacity class="lg:hidden fixed inset-0 z-40 bg-white pt-24 px-6 overflow-y-auto">
            <div class="flex flex-col space-y-6 text-center">
                <a href="{{ url('/') }}" class="text-xl font-bold text-slate-800">Beranda</a>
                <div class="space-y-3">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tentang</p>
                    <a href="{{ route('public.profile') }}" class="block text-slate-600 py-1">Profil Gereja</a>
                    <a href="{{ url('/#galeri') }}" class="block text-slate-600 py-1">Galeri</a>
                    <a href="{{ route('public.contact.index') }}" class="block text-slate-600 py-1">Kontak</a>
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Informasi</p>
                    <a href="{{ route('public.warta.index') }}" class="block text-slate-600 py-1">Warta Jemaat</a>
                    <a href="{{ route('public.schedules.index') }}" class="block text-slate-600 py-1">Jadwal Ibadah</a>
                    <a href="{{ url('/#keuangan') }}" class="block text-slate-600 py-1">Keuangan</a>
                </div>
                <div class="pt-8 pb-12">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full py-4 bg-slate-900 text-white rounded-2xl font-bold uppercase tracking-widest text-xs">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-4 bg-primary text-white rounded-2xl font-bold uppercase tracking-widest text-xs shadow-xl">Login Pengurus</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. CONTENT SLOT -->
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    @php
        $facebookUrl = $setting->facebook ? (str_starts_with($setting->facebook, 'http') ? $setting->facebook : 'https://facebook.com/' . ltrim($setting->facebook, '@')) : null;
        $instagramUrl = $setting->instagram ? (str_starts_with($setting->instagram, 'http') ? $setting->instagram : 'https://instagram.com/' . ltrim($setting->instagram, '@')) : null;
        $youtubeUrl = $setting->youtube ? (str_starts_with($setting->youtube, 'http') ? $setting->youtube : 'https://youtube.com/' . $setting->youtube) : null;
    @endphp

    <!-- 3. FOOTER -->
    <footer class="bg-white pt-24 pb-12 px-6 lg:px-10 border-t border-slate-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
                
                <!-- Info Gereja -->
                <div class="md:col-span-5">
                    <h2 class="text-4xl font-black italic uppercase tracking-tighter text-slate-900 mb-6 leading-[0.85]">
                        {{ $setting->nama_gereja }}<br><span class="text-primary">{{ $setting->nama_jemaat }}</span>
                    </h2>
                    <p class="text-slate-400 text-sm font-medium leading-loose italic border-l-4 border-primary pl-6 uppercase tracking-widest max-w-md">
                        {{ $setting->deskripsi_singkat ?? 'Melayani Tuhan dengan integritas dan kasih melalui teknologi.' }}
                    </p>
                </div>
                
                <!-- Kontak -->
                <div class="md:col-span-4">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.6em] text-slate-300 mb-8 italic">Hubungi Kami</h4>
                    <p class="text-sm font-black text-slate-800 leading-relaxed mb-6 italic">{{ $setting->alamat }}</p>
                    <ul class="space-y-3 text-xs font-bold text-slate-500">
                        <li class="flex items-center gap-3"><span class="text-primary">EMAIL</span> {{ $setting->email }}</li>
                        @if($setting->telepon) <li class="flex items-center gap-3"><span class="text-primary">PHONE</span> {{ $setting->telepon }}</li> @endif
                    </ul>
                </div>

                <!-- Social -->
                <div class="md:col-span-3 md:text-right">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.6em] text-slate-300 mb-8 italic">Social Media</h4>
                    <div class="flex md:justify-end gap-4">
                        @if($facebookUrl) <a href="{{ $facebookUrl }}" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center font-black text-slate-400 hover:bg-primary hover:text-white transition-all cursor-pointer shadow-sm">FB</a> @endif
                        @if($instagramUrl) <a href="{{ $instagramUrl }}" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center font-black text-slate-400 hover:bg-primary hover:text-white transition-all cursor-pointer shadow-sm">IG</a> @endif
                        @if($youtubeUrl) <a href="{{ $youtubeUrl }}" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center font-black text-slate-400 hover:bg-primary hover:text-white transition-all cursor-pointer shadow-sm">YT</a> @endif
                    </div>
                </div>
            </div>
            
            <!-- Bottom Bar dengan Kredit Khusus -->
            <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
                
                <!-- Copyright -->
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] italic">© {{ date('Y') }} {{ $setting->nama_jemaat }}</p>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] italic mt-1">All Rights Reserved</p>
                </div>

                <!-- Developer Credit & Persembahan -->
                <div class="text-center md:text-right space-y-2">
                    <div class="flex items-center justify-center md:justify-end gap-3">
                        <div class="h-1.5 w-1.5 bg-primary rounded-full animate-ping"></div>
                        <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em] italic">System Online</span>
                    </div>
                    
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] italic leading-relaxed max-w-xs md:max-w-sm">
                        Website & Sistem Informasi ini dikembangkan sebagai bentuk persembahan pelayanan digital untuk kemuliaan Tuhan.
                    </p>

                    <p class="text-[9px] text-slate-300 uppercase tracking-[0.3em] italic">
                        Developed with dedication by <span class="text-primary font-black">Ranus Ate</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Watermark -->
        <div class="absolute -bottom-24 -left-24 text-slate-50 font-serif italic text-[20rem] leading-none pointer-events-none -z-10 select-none">†</div>
    </footer>

</body>
</html>