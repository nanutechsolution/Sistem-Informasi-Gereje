<x-layouts.web>
    
    <!-- 1. HERO SECTION (Tampilan Depan) -->
    <header id="home" class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden bg-slate-900">
        <!-- Gambar Latar -->
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1920" 
             class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105 animate-[pulse_60s_infinite]" 
             alt="Altar Gereja">
        
        <!-- Overlay Gradasi -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-slate-900/60"></div>
        
        <div class="relative z-10 text-center px-6 max-w-5xl mt-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Soli Deo Gloria</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-9xl font-serif text-white italic mb-10 leading-[0.9] tracking-tighter shadow-black drop-shadow-lg animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
                Melayani dengan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-white/60">Kasih Kristus.</span>
            </h1>

            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto mb-12 font-medium leading-relaxed animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-300">
                Selamat datang di website resmi GKS Jemaat Reda Pada. Pusat informasi jadwal, warta, dan transparansi pelayanan jemaat.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-in fade-in slide-in-from-bottom-12 duration-1000 delay-500">
                <a href="#jadwal" class="w-full sm:w-auto px-12 py-5 bg-white text-primary rounded-full font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:scale-105 transition-all">
                    Lihat Jadwal Ibadah
                </a>
                <a href="#keuangan" class="w-full sm:w-auto px-12 py-5 bg-transparent border border-white/30 text-white rounded-full font-black text-xs uppercase tracking-[0.2em] hover:bg-white/10 transition-all backdrop-blur-sm">
                    Laporan Kas
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </header>

    <!-- 2. STATISTIK JEMAAT (Bento Grid) -->
    <!-- <section class="relative -mt-24 z-20 px-6 lg:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-slate-100 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Total Keluarga</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-6xl font-black text-primary tracking-tighter">{{ $stats['total_kk'] }}</h3>
                            <span class="text-lg font-bold text-slate-300">KK</span>
                        </div>
                    </div>
                    <div class="absolute right-0 bottom-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity text-primary">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                </div>

                <div class="bg-primary p-10 rounded-[3rem] shadow-2xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.3em] mb-2">Total Jemaat</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-6xl font-black text-white tracking-tighter">{{ $stats['total_jiwa'] }}</h3>
                            <span class="text-lg font-bold text-blue-300">Jiwa</span>
                        </div>
                    </div>
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl group-hover:bg-blue-400/30 transition-colors"></div>
                </div>

                <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-slate-100 relative overflow-hidden text-center flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Wilayah Pelayanan</p>
                    <h3 class="text-6xl font-black text-slate-900 tracking-tighter">{{ $stats['total_wilayah'] }}</h3>
                </div>
            </div>
        </div>
    </section> -->

    <!-- 3. JADWAL IBADAH (Detail PKS & Umum) -->
    <section id="jadwal" class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <!-- Section Title -->
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.5em] mb-4 italic">Agenda Pelayanan</h2>
                <h3 class="text-5xl md:text-6xl font-serif italic text-slate-900 tracking-tighter">Jadwal Peribadatan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($schedules as $sch)
                <div class="bg-slate-50 rounded-[40px] p-8 border border-slate-200 hover:shadow-2xl hover:border-primary/20 transition-all duration-300 flex flex-col group relative overflow-hidden h-full">
                    
                    <!-- Decorative BG -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full -translate-y-1/2 translate-x-1/2 opacity-50 group-hover:scale-125 transition-transform duration-700"></div>

                    <!-- Header: Tanggal & Tipe -->
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="bg-white p-3 rounded-2xl shadow-sm text-center min-w-[60px] border border-slate-100">
                                <span class="block text-xl font-black text-slate-900 leading-none">{{ $sch->tanggal->format('d') }}</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase">{{ $sch->tanggal->format('M') }}</span>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-primary text-white mb-1 shadow-md shadow-primary/20">
                                    {{ $sch->type->nama }}
                                </span>
                                <p class="text-xs font-bold text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $sch->jam_mulai->format('H:i') }} WITA
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Body: Detail Acara -->
                    <div class="flex-1 relative z-10">
                        <!-- JIKA PKS (Ibadah Rumah Tangga) -->
                        @if($sch->family)
                            <div class="mb-6">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tuan Rumah</p>
                                <h4 class="text-xl font-black text-slate-900 leading-tight italic">
                                    Kel. {{ $sch->family->kepala_keluarga }}
                                </h4>
                                <p class="text-xs font-medium text-slate-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $sch->family->refWilayah->nama ?? 'Wilayah -' }}
                                </p>
                            </div>

                            <!-- Detail Tim Pelayan (PKS) -->
                            <div class="bg-white p-5 rounded-3xl border border-slate-100 space-y-4">
                                <!-- PF -->
                                <div class="flex items-start gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[9px] font-black shrink-0">PF</div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pelayan Firman</p>
                                        <p class="text-sm font-bold text-slate-800 leading-tight">
                                            {{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Belum Ditunjuk' }}
                                        </p>
                                    </div>
                                </div>
                                <!-- Pendamping -->
                                @if($sch->servants->where('peran', 'Pendamping')->count() > 0)
                                <div class="pt-3 border-t border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Anggota Tim</p>
                                    <ul class="text-xs font-medium text-slate-600 space-y-1.5">
                                        @foreach($sch->servants->where('peran', 'Pendamping') as $p)
                                            <li class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                                {{ $p->member->nama }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                        <!-- JIKA IBADAH UMUM / LAINNYA -->
                        @else
                            <div class="mb-6">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tema / Topik</p>
                                <h4 class="text-xl font-black text-slate-900 leading-tight italic">
                                    {{ $sch->tema ?? 'Ibadah Rutin' }}
                                </h4>
                                <p class="text-xs font-medium text-slate-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                                    {{ $sch->lokasi_manual ?? 'Gedung Gereja' }}
                                </p>
                            </div>
                            
                            <div class="bg-white p-5 rounded-3xl border border-slate-100 flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center text-[9px] font-black shrink-0">PF</div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pelayan Firman</p>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">
                                        {{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[50px] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs italic">Belum ada agenda pelayanan minggu ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 4. GALERI & WARTA (Layout Asimetris) -->
    <section class="py-20 px-6 lg:px-10 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Kolom Kiri: Galeri -->
            <div>
                <h3 class="text-2xl font-black italic text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-accent rounded-full"></span> Galeri Pelayanan
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($galleries->take(4) as $gallery)
                    <div class="relative aspect-square rounded-[2rem] overflow-hidden group shadow-lg">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $gallery->judul }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <span class="text-white text-xs font-bold truncate">{{ $gallery->judul }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-10 text-center text-slate-300 italic text-xs uppercase font-bold border-2 border-dashed border-slate-100 rounded-3xl">Belum ada foto.</div>
                    @endforelse
                </div>
            </div>

            <!-- Kolom Kanan: Warta -->
            <div>
                <h3 class="text-2xl font-black italic text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-primary rounded-full"></span> Kabar Jemaat
                </h3>
                <div class="space-y-6">
                    @forelse($posts->take(3) as $post)
                    <a href="{{ route('public.warta.show', $post->slug) }}" class="flex gap-6 items-start group">
                        <div class="w-24 h-24 rounded-3xl bg-slate-100 overflow-hidden shrink-0 shadow-sm relative">
                            <img src="{{ asset('storage/' . $post->gambar_fitur) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=200'">
                        </div>
                        <div class="flex-1 py-1">
                            <span class="text-[9px] font-black text-primary bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">{{ $post->kategori }}</span>
                            <h4 class="text-lg font-bold text-slate-900 mt-2 mb-1 group-hover:text-primary transition-colors leading-tight">{{ $post->judul }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $post->published_at->format('d M Y') }}</p>
                        </div>
                    </a>
                    @empty
                    <div class="py-10 text-center text-slate-300 italic text-xs uppercase font-bold">Belum ada warta terbaru.</div>
                    @endforelse
                </div>
                <div class="mt-8">
                    <a href="{{ route('public.warta.index') }}" class="inline-block text-xs font-bold text-primary border-b border-primary hover:text-blue-800 pb-0.5 uppercase tracking-widest">Lihat Semua Warta &rarr;</a>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. TRANSPARANSI KAS -->
    <section id="keuangan" class="py-32 px-6 lg:px-10 bg-slate-950 relative overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h2 class="text-xs font-black text-emerald-400 uppercase tracking-[0.5em] mb-8 italic">Transparansi Keuangan</h2>
            <div class="bg-white/5 border border-white/10 backdrop-blur-2xl rounded-[60px] p-16 shadow-2xl relative overflow-hidden group">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4 leading-none">Saldo Kas Umum Saat Ini</p>
                <h4 class="text-6xl sm:text-8xl font-black text-emerald-400 italic tracking-tighter">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h4>
                <div class="flex justify-center items-center gap-3 mt-8">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Update Real-time</p>
                </div>
            </div>
        </div>
        <div class="absolute -left-20 -bottom-20 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[150px]"></div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white pt-24 pb-12 px-6 lg:px-10 border-t border-slate-100">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 mb-2 leading-none">GKS JEMAAT REDA PADA</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mb-12">{{ $setting->alamat ?? 'Sumba Barat Daya' }}</p>
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">© 2026 Sistem Informasi Gereja Terintegrasi</p>
        </div>
    </footer>

</x-layouts.web>