<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GKS Jemaat Reda Pada | Melayani Dengan Kasih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#1e3a8a', accent: '#d97706' },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], serif: ['Playfair Display', 'serif'] }
                }
            }
        }
    </script>
    <style>
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .hero-gradient { background: linear-gradient(to bottom, rgba(30, 58, 138, 0.6), rgba(15, 23, 42, 0.95)); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased text-sm sm:text-base">

    <!-- NAVIGATION -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 glass border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 bg-primary rounded-2xl flex items-center justify-center text-white shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                    </div>
                    <div class="leading-none">
                        <span class="block text-xl font-black tracking-tighter text-primary uppercase italic leading-none">SIG-GKS</span>
                        <span class="block text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">Jemaat Reda Pada</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-10 text-[10px] font-black uppercase tracking-widest text-slate-500">
                    <a href="#home" class="text-primary border-b-2 border-primary pb-1">Beranda</a>
                    <a href="#jadwal" class="hover:text-primary transition-colors">Jadwal</a>
                    <a href="#galeri" class="hover:text-primary transition-colors">Galeri</a>
                    <a href="#berita" class="hover:text-primary transition-colors">Warta</a>
                    <a href="#keuangan" class="hover:text-primary transition-colors">Transparansi</a>
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-primary text-white rounded-full shadow-lg hover:scale-105 transition-all">Portal Majelis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header id="home" class="relative h-[90vh] flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1280" class="absolute inset-0 w-full h-full object-cover scale-105" alt="GKS Reda Pada">
        <div class="absolute inset-0 hero-gradient"></div>
        
        <div class="relative z-10 text-center px-4">
            <h2 class="text-amber-400 text-xs font-black uppercase tracking-[0.5em] mb-6">Soli Deo Gloria</h2>
            <h1 class="text-4xl sm:text-7xl font-serif text-white italic mb-10 max-w-4xl leading-tight">
                "Melayani dengan Kasih, <br>Mengelola dengan Amanah."
            </h1>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#jadwal" class="w-full sm:w-auto px-12 py-5 bg-white text-primary rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-primary hover:text-white transition-all">Lihat Agenda Pelayanan</a>
            </div>
        </div>
    </header>

    <!-- DYNAMIC QUICK STATS -->
    <section class="relative -mt-16 z-20 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white p-10 rounded-[40px] shadow-2xl text-center border border-slate-100 group hover:bg-primary transition-all duration-500">
                <p class="text-5xl font-black text-primary mb-2 group-hover:text-white transition-colors tracking-tighter">{{ $stats['total_kk'] }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-200 leading-none">Keluarga (KK)</p>
            </div>
            <div class="bg-white p-10 rounded-[40px] shadow-2xl text-center border border-slate-100 group hover:bg-primary transition-all duration-500">
                <p class="text-5xl font-black text-primary mb-2 group-hover:text-white transition-colors tracking-tighter">{{ $stats['total_jiwa'] }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-200 leading-none">Jiwa Terdaftar</p>
            </div>
            <div class="bg-white p-10 rounded-[40px] shadow-2xl text-center border border-slate-100 group hover:bg-primary transition-all duration-500 hidden md:block">
                <p class="text-5xl font-black text-primary mb-2 group-hover:text-white transition-colors tracking-tighter">{{ $stats['total_wilayah'] }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-200 leading-none">Wilayah Pelayanan</p>
            </div>
        </div>
    </section>

    <!-- DYNAMIC WORSHIP SCHEDULE -->
    <section id="jadwal" class="py-32 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4 leading-none italic">Agenda Pelayanan</h2>
                <h3 class="text-5xl font-serif italic text-slate-900 tracking-tight leading-none">Mari Bersekutu Bersama Kami</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($schedules as $sch)
                <div class="bg-slate-50 p-10 rounded-[50px] border border-slate-100 hover:shadow-2xl transition-all duration-500 flex flex-col group h-full">
                    <div class="flex justify-between items-start mb-8">
                        <div class="h-14 w-14 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-100 shadow-sm italic">
                            {{ $sch->type->nama }}
                        </span>
                    </div>
                    
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2 italic">
                        {{ $sch->tanggal->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    <h4 class="text-2xl font-black text-slate-900 uppercase italic mb-6 leading-tight flex-1">
                        {{ $sch->tema ?? ($sch->family ? 'PKS Kel. '.$sch->family->kepala_keluarga : 'Ibadah Rutin') }}
                    </h4>
                    
                    <div class="mt-auto space-y-4">
                        <div class="flex items-center gap-3 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center font-black text-xs uppercase">
                                {{ substr($sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? '?', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Pelayan Firman</p>
                                <p class="text-xs font-bold text-slate-800 truncate mt-1 leading-none">
                                    {{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Dalam Konfirmasi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[50px] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs italic">Jadwal baru sedang disusun oleh Sekretariat.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- NEW SECTION: GALERI PELAYANAN -->
    <section id="galeri" class="py-32 px-4 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16">
                <div class="text-left">
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4 italic leading-none">Kilas Pelayanan</h2>
                    <h3 class="text-5xl font-serif italic text-slate-900 tracking-tight leading-none">Galeri & Dokumentasi</h3>
                </div>
                <p class="text-slate-400 text-xs font-bold max-w-xs text-left md:text-right uppercase tracking-widest leading-relaxed">Melihat jejak pertumbuhan dan persekutuan jemaat dalam visual.</p>
            </div>

            <div class="flex gap-6 overflow-x-auto no-scrollbar pb-10 -mx-4 px-4">
                <!-- Mockup Galeri -->
                <div class="min-w-[300px] group relative rounded-[40px] overflow-hidden shadow-xl aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&q=80&w=400" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Ibadah">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <p class="text-[9px] font-black uppercase tracking-widest text-blue-300">Ibadah Minggu</p>
                        <h4 class="text-xl font-black italic uppercase leading-none mt-1">Persekutuan Kudus</h4>
                    </div>
                </div>
                <div class="min-w-[300px] group relative rounded-[40px] overflow-hidden shadow-xl aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1541810232490-671842845c1c?auto=format&fit=crop&q=80&w=400" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Pembangunan">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <p class="text-[9px] font-black uppercase tracking-widest text-amber-400">Pembangunan</p>
                        <h4 class="text-xl font-black italic uppercase leading-none mt-1">Gedung Baru</h4>
                    </div>
                </div>
                <div class="min-w-[300px] group relative rounded-[40px] overflow-hidden shadow-xl aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=400" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Pemuda">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <p class="text-[9px] font-black uppercase tracking-widest text-rose-400">Pemuda</p>
                        <h4 class="text-xl font-black italic uppercase leading-none mt-1">Youth Fellowship</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW SECTION: BERITA & WARTA JEMAAT -->
    <section id="berita" class="py-32 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4 leading-none italic">Kabar Jemaat</h2>
                <h3 class="text-5xl font-serif italic text-slate-900 tracking-tight leading-none">Warta & Artikel Terbaru</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Blog Item 1 -->
                <div class="flex flex-col sm:flex-row gap-8 items-center group cursor-pointer">
                    <div class="w-full sm:w-48 h-48 rounded-[32px] overflow-hidden shadow-lg shrink-0">
                        <img src="https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="News">
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-primary bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">Pengumuman</span>
                        <h4 class="text-2xl font-black text-slate-900 uppercase italic mt-3 group-hover:text-primary transition-colors leading-tight">Persiapan Sidang Majelis Jemaat Triwulan I</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase mt-3 tracking-widest leading-none">10 Feb 2026 • Oleh: Sekretaris</p>
                    </div>
                </div>
                <!-- Blog Item 2 -->
                <div class="flex flex-col sm:flex-row gap-8 items-center group cursor-pointer">
                    <div class="w-full sm:w-48 h-48 rounded-[32px] overflow-hidden shadow-lg shrink-0">
                        <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="News">
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest">Renungan</span>
                        <h4 class="text-2xl font-black text-slate-900 uppercase italic mt-3 group-hover:text-primary transition-colors leading-tight">Menemukan Sukacita Dalam Kesederhanaan Melayani</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase mt-3 tracking-widest leading-none">08 Feb 2026 • Oleh: Pdt. Alponia Malo</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <button class="px-10 py-4 border-2 border-slate-200 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">Lihat Semua Warta</button>
            </div>
        </div>
    </section>

    <!-- DYNAMIC FINANCIAL TRANSPARENCY -->
    <section id="keuangan" class="py-32 px-4 bg-slate-900 relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-xs font-black text-emerald-400 uppercase tracking-[0.4em] mb-6 italic leading-none">Transparansi Keuangan</h2>
            <h3 class="text-4xl sm:text-6xl font-serif italic text-white mb-10 leading-tight">Pengelolaan Kas Jemaat Yang Akuntabel.</h3>
            
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-[50px] p-12 shadow-2xl relative overflow-hidden group">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4 leading-none">Posisi Saldo Kas Umum (Terverifikasi)</p>
                <h4 class="text-5xl sm:text-7xl font-black text-emerald-400 italic tracking-tighter">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h4>
                <p class="text-xs text-slate-400 mt-10 font-medium italic">"Terima kasih atas setiap persembahan syukur Anda untuk pelayanan Tuhan."</p>
                
                <!-- Decorative Icon -->
                <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none">
                    <svg class="w-64 h-64 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Decorative bg blur -->
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white pt-24 pb-12 px-4 border-t border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 mb-20 border-b border-slate-100 pb-20">
                <div class="col-span-1">
                    <h2 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 mb-6 leading-none">GKS JEMAAT<br>REDA PADA</h2>
                    <p class="text-slate-400 text-xs font-bold leading-relaxed italic border-l-4 border-primary pl-4 uppercase tracking-widest">
                        Melayani dengan integritas melalui digitalisasi sistem informasi jemaat.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-300 mb-8 italic leading-none">Kontak & Alamat</h4>
                    <p class="text-sm font-bold text-slate-700 leading-relaxed italic mb-4">Jl. Lolo Ole, Kec. Kota Tambolaka, Sumba Barat Daya, NTT.</p>
                    <p class="text-xs font-bold text-slate-400 tracking-widest uppercase italic">Email: jemaat@gksredapada.or.id</p>
                </div>
                <div class="text-right">
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-300 mb-8 italic leading-none">Media Sosial</h4>
                    <div class="flex justify-end gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-slate-400 hover:text-primary transition-all cursor-pointer shadow-sm">FB</div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-slate-400 hover:text-primary transition-all cursor-pointer shadow-sm">IG</div>
                    </div>
                </div>
            </div>
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] text-center">© 2026 SIG-GKS JEMAAT REDA PADA. BUILT BY SIG-TEAM.</p>
        </div>
    </footer>

</body>
</html>