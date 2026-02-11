@php
    $theme = \App\Models\ChurchSetting::current();
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? $theme->nama_jemaat }} | {{ $theme->nama_gereja }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets (CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  
    <style>
         :root {
            --primary: {{ $theme->color_primary ?? '#1e3a8a' }};
            --accent: {{ $theme->color_accent ?? '#d97706' }};
            --surface: {{ $theme->color_background ?? '#f8fafc' }};
            --sidebar: {{ $theme->color_sidebar ?? '#0f172a' }};
            --radius-ui: {{ $theme->ui_rounded ?? '1.5rem' }};
        }
        body { 
            background-color: var(--surface); 
            color: {{ ($theme->appearance_mode ?? 'light') === 'dark' ? '#f1f5f9' : '#0f172a' }}; 
        }

        .glass-nav { 
            background: {{ ($theme->appearance_mode ?? 'light') === 'dark' ? 'rgba(15, 23, 42, 0.85)' : 'rgba(253, 253, 253, 0.85)' }};
            backdrop-filter: blur(20px); 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
        }

        .dropdown-enter { animation: slideDown 0.2s ease-out forwards; }
        @keyframes slideDown { 
            from { opacity: 0; transform: translateY(-10px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        /* Prevent scroll when mobile menu is open */
        .no-scroll { overflow: hidden; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 selection:bg-primary selection:text-white" 
      x-data="{ 
        mobileMenu: false, 
        isTop: true,
        scrolled() { this.isTop = window.pageYOffset < 50 } 
      }" 
      @scroll.window="scrolled()"
      @keydown.escape="mobileMenu = false">

    <!-- HEADER NAVIGATION -->
    <nav class="fixed top-0 w-full z-[100] transition-all duration-500"
         :class="isTop ? 'py-6 bg-transparent' : 'py-3 glass-effect shadow-sm border-b border-slate-200/50'">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex justify-between items-center">
                
                <!-- Logo Brand -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="h-11 w-11 bg-primary rounded-2xl flex items-center justify-center text-white shadow-xl shadow-primary/20 group-hover:rotate-3 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 21l-8-18h16l-8 18z"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <h1 class="text-lg font-extrabold tracking-tight text-slate-900">{{ $theme->nama_gereja }}</h1>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary/80">{{ $theme->nama_jemaat }}</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8">
                    <div class="flex items-center gap-6 text-[11px] font-bold uppercase tracking-widest text-slate-500">
                        <a href="{{ url('/') }}" class="hover:text-primary transition-colors {{ request()->is('/') ? 'text-primary' : '' }}">Beranda</a>
                        <a href="{{ route('public.schedules.index') }}" class="hover:text-primary transition-colors {{ request()->routeIs('public.schedules.*') ? 'text-primary' : '' }}">Jadwal</a>
                        <a href="{{ route('public.warta.index') }}" class="hover:text-primary transition-colors {{ request()->routeIs('public.warta.*') ? 'text-primary' : '' }}">Warta</a>
                        
                        <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="flex items-center gap-1 hover:text-primary transition-colors uppercase font-bold tracking-widest">
                                Layanan <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak x-transition.opacity class="absolute top-full -left-4 w-56 pt-4">
                                <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-3 flex flex-col gap-1">
                                    <a href="{{ route('public.prayer') }}" class="px-4 py-2 hover:bg-slate-50 rounded-xl transition-colors text-sm text-slate-600 font-medium">Permohonan Doa</a>
                                    <a href="{{ route('public.sermons') }}" class="px-4 py-2 hover:bg-slate-50 rounded-xl transition-colors text-sm text-slate-600 font-medium">Video Khotbah</a>
                                    <a href="{{ route('public.downloads') }}" class="px-4 py-2 hover:bg-slate-50 rounded-xl transition-colors text-sm text-slate-600 font-medium">Pustaka Dokumen</a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('public.profile') }}" class="hover:text-primary transition-colors {{ request()->routeIs('public.profile') ? 'text-primary' : '' }}">Profil</a>
                        <a href="{{ route('public.contact.index') }}" class="hover:text-primary transition-colors">Kontak</a>
                    </div>

                    <div class="h-6 w-[1px] bg-slate-200 mx-2"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-slate-900 text-white px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-primary transition-all shadow-lg shadow-slate-900/10">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-widest hover:shadow-xl hover:shadow-primary/30 transition-all">Portal Login</a>
                    @endauth
                </div>

                <!-- Mobile Trigger -->
                <button @click="mobileMenu = true" class="lg:hidden p-2.5 bg-white rounded-xl text-primary shadow-sm border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenu" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed inset-0 z-[200] bg-white lg:hidden flex flex-col h-screen overflow-hidden">
            
            <!-- Mobile Header -->
            <div class="p-6 flex justify-between items-center border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 bg-primary rounded-xl flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 21l-8-18h16l-8 18z"/></svg>
                    </div>
                    <span class="font-bold text-xs uppercase tracking-[0.2em] text-slate-400">Navigasi Utama</span>
                </div>
                <button @click="mobileMenu = false" class="p-3 bg-slate-50 rounded-full text-slate-400 hover:bg-slate-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile Links Content -->
            <div class="flex-grow overflow-y-auto p-8 space-y-2">
                <a href="{{ url('/') }}" @click="mobileMenu = false" class="nav-link-mobile">
                    <span>Beranda</span>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('public.schedules.index') }}" @click="mobileMenu = false" class="nav-link-mobile">
                    <span>Jadwal Ibadah</span>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('public.warta.index') }}" @click="mobileMenu = false" class="nav-link-mobile">
                    <span>Warta Jemaat</span>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
                
                <!-- Mobile Accordion Layanan -->
                <div x-data="{ open: false }" class="border-b border-slate-50">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-2xl font-extrabold tracking-tight text-slate-900 py-4 transition-all" :class="open ? 'text-primary' : ''">
                        <span>Layanan Jemaat</span>
                        <svg class="w-5 h-5 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="flex flex-col gap-4 pb-6 pl-4 border-l-2 border-primary/10 mt-2">
                        <a href="{{ route('public.prayer') }}" @click="mobileMenu = false" class="text-lg font-bold text-slate-500 hover:text-primary transition-colors">Permohonan Doa</a>
                        <a href="{{ route('public.sermons') }}" @click="mobileMenu = false" class="text-lg font-bold text-slate-500 hover:text-primary transition-colors">Video Khotbah</a>
                        <a href="{{ route('public.downloads') }}" @click="mobileMenu = false" class="text-lg font-bold text-slate-500 hover:text-primary transition-colors">Pustaka Dokumen</a>
                    </div>
                </div>

                <a href="{{ route('public.profile') }}" @click="mobileMenu = false" class="nav-link-mobile">
                    <span>Profil Jemaat</span>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('public.contact.index') }}" @click="mobileMenu = false" class="nav-link-mobile">
                    <span>Kontak Kami</span>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Mobile Footer CTA -->
            <div class="p-8 border-t border-slate-50 bg-slate-50/50">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full bg-slate-900 text-white py-5 rounded-2xl flex items-center justify-center font-bold gap-3 shadow-lg">
                        Buka Dashboard Admin <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full bg-primary text-white py-5 rounded-2xl flex items-center justify-center font-bold gap-3 shadow-lg shadow-primary/20">
                        Masuk ke Portal Jemaat <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- CONTENT AREA -->
    <main class="flex-grow pt-28 pb-20 min-h-[60vh]">
        {{ $slot }}
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 pt-20 pb-10 px-6 mt-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Col 1: Branding -->
                <div class="md:col-span-1">
                    <h2 class="text-2xl font-black italic text-slate-900 mb-4">{{ $theme->nama_gereja }}</h2>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $theme->deskripsi_singkat ?? 'Membangun iman dan komunitas melalui pelayanan kasih di tanah Sumba.' }}</p>
                </div>
                
                <!-- Col 2: Info -->
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">Sekretariat</h4>
                    <p class="text-sm text-slate-600 leading-loose">{{ $theme->alamat }}</p>
                </div>

                <!-- Col 3: Kontak -->
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">Pusat Bantuan</h4>
                    <ul class="text-sm text-slate-600 space-y-3 font-medium">
                        <li><a href="mailto:{{ $theme->email }}" class="hover:text-primary transition-colors">{{ $theme->email }}</a></li>
                        <li><a href="tel:{{ $theme->telepon }}" class="hover:text-primary transition-colors">{{ $theme->telepon }}</a></li>
                        <li><a href="{{ route('public.contact.index') }}" class="hover:text-primary transition-colors underline decoration-primary/20 decoration-2 underline-offset-4">Kirim Pesan</a></li>
                    </ul>
                </div>

                <!-- Col 4: Social -->
                <div class="md:text-right">
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">Media Sosial</h4>
                    <div class="flex md:justify-end gap-3">
                        @foreach(['facebook', 'instagram', 'youtube'] as $sm)
                            @if(!empty($theme->$sm))
                            <a href="{{ str_starts_with($theme->$sm, 'http') ? $theme->$sm : '#' }}" target="_blank" class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all shadow-sm border border-slate-100">
                                <i class="fab fa-{{ $sm }}"></i>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bottom Line -->
            <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} {{ $theme->nama_jemaat }}. All Rights Reserved.</p>
                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> System Online</span>
                    <span>•</span>
                    <span>Dev by <span class="text-primary underline font-black decoration-2 underline-offset-2">Ranus Ate</span></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts (Otomatis menyertakan Alpine.js internal) -->
    @livewireScripts
</body>
</html>