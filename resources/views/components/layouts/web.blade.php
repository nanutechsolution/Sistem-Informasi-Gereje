@php
// Ambil setting dari database, fallback jika tabel kosong
$setting = \App\Models\ChurchSetting::first() ?? new \App\Models\ChurchSetting([
'nama_gereja' => 'Gereja Kristen Sumba',
'nama_jemaat' => 'Jemaat Reda Pada',
'warna_utama' => '#1e3a8a',
'warna_aksen' => '#d97706',
]);
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? $setting->nama_jemaat }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
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
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '3.5rem'
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(253, 253, 253, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hero-mask {
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.3), rgba(15, 23, 42, 1));
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .text-reveal {
            animation: reveal 1.2s cubic-bezier(0.77, 0, 0.175, 1) forwards;
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-surface text-slate-900 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="fixed top-0 w-full z-[100] glass-nav transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                    <div class="h-10 w-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path>
                        </svg>
                    </div>
                    <div class="leading-none">
                        <span class="block text-xl font-extrabold tracking-tighter text-primary uppercase italic">{{ $setting->nama_gereja }}</span>
                        <span class="block text-[8px] font-black uppercase tracking-[0.3em] text-slate-400 mt-0.5">{{ $setting->nama_jemaat }}</span>
                    </div>
                </a>

                <div class="hidden lg:flex items-center space-x-10 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-primary transition-all">Beranda</a>
                    <a href="{{ url('/#jadwal') }}" class="hover:text-primary transition-all">Jadwal</a>
                    <a href="{{ url('/#keuangan') }}" class="hover:text-primary transition-all">Transparansi</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-primary text-white rounded-full shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5 transition-all">Portal</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- KONTEN DINAMIS -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- FOOTER -->
    <footer class="bg-white pt-32 pb-16 px-6 lg:px-10 border-t border-slate-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-24">
                <div class="md:col-span-5">
                    <h2 class="text-4xl font-black italic uppercase tracking-tighter text-slate-900 mb-6 leading-[0.85]">
                        {{ $setting->nama_gereja }}<br><span class="text-primary">{{ $setting->nama_jemaat }}</span>
                    </h2>
                    <p class="text-slate-400 text-sm font-medium leading-loose italic border-l-4 border-primary pl-6 uppercase tracking-widest max-w-md">
                        {{ $setting->deskripsi_singkat ?? 'Melayani Tuhan dengan integritas.' }}
                    </p>
                </div>
                <div class="md:col-span-4">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.6em] text-slate-300 mb-14 italic">Contact Center</h4>
                    <p class="text-sm font-black text-slate-800 leading-relaxed mb-10 italic">{{ $setting->alamat }}</p>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] italic">© {{ date('Y') }} {{ $setting->nama_jemaat }}</p>
        </div>
    </footer>
</body>

</html>