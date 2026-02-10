<x-layouts.web>
    <!-- 2. IMMERSIVE HERO SECTION -->
    <header id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1920" class="absolute inset-0 w-full h-full object-cover scale-110 motion-safe:animate-[pulse_10s_infinite]" alt="GKS Reda Pada Altar">
        <div class="absolute inset-0 hero-gradient"></div>

        <div class="relative z-10 text-center px-6 max-w-6xl mt-16">
            <div class="text-reveal inline-flex items-center gap-3 px-5 py-2.5 bg-white/5 backdrop-blur-xl rounded-full border border-white/10 mb-10 mx-auto shadow-2xl">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                <span class="text-white text-[9px] font-black uppercase tracking-[0.5em]">Digital Ecclesia Experience 2026</span>
            </div>

            <h1 class="text-reveal text-6xl sm:text-[10rem] font-serif text-white italic mb-12 leading-[0.9] tracking-tighter" style="animation-delay: 0.3s">
                Melayani dengan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-white/40">Kasih & Integritas.</span>
            </h1>

            <div class="text-reveal flex flex-col sm:flex-row items-center justify-center gap-8 mt-12" style="animation-delay: 0.6s">
                <a href="#jadwal" class="w-full sm:w-auto px-16 py-6 bg-white text-primary rounded-full font-black text-xs uppercase tracking-[0.3em] shadow-2xl hover:bg-primary hover:text-white transition-all duration-500">Cek Agenda Ibadah</a>
                <a href="#keuangan" class="w-full sm:w-auto px-16 py-6 bg-white/5 text-white border border-white/20 rounded-full font-black text-xs uppercase tracking-[0.3em] backdrop-blur-2xl hover:bg-white/10 transition-all">Transparansi Keuangan</a>
            </div>
        </div>

        <!-- Float Decoration -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 animate-bounce opacity-40">
            <svg class="w-6 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </header>

    <!-- 3. BENTO GRID STATS (Dinamis: $stats) -->
    <section class="relative -mt-32 z-20 px-6 lg:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- KK Count -->
                <div class="md:col-span-2 bg-white p-12 rounded-5xl shadow-2xl border border-slate-100 group hover:bg-primary transition-all duration-700 relative overflow-hidden">
                    <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 group-hover:text-blue-200">Keluarga Terdaftar</p>
                    <div class="flex items-baseline gap-4">
                        <h3 class="text-8xl font-black text-primary group-hover:text-white transition-colors tracking-tighter">{{ $stats['total_kk'] }}</h3>
                        <span class="text-xl font-bold text-slate-300 group-hover:text-blue-300 italic uppercase">KK</span>
                    </div>
                    <svg class="absolute -right-8 -bottom-8 w-48 h-48 opacity-5 text-primary group-hover:text-white transition-all" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                </div>
                <!-- Jiwa Count -->
                <div class="bg-white p-12 rounded-5xl shadow-2xl border border-slate-100 group hover:bg-emerald-600 transition-all duration-700 relative overflow-hidden">
                    <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 group-hover:text-emerald-100 leading-none">Total Jiwa</p>
                    <h3 class="text-7xl font-black text-slate-900 group-hover:text-white transition-colors tracking-tighter">{{ $stats['total_jiwa'] }}</h3>
                    <p class="mt-4 text-xs font-bold text-slate-400 group-hover:text-emerald-200 uppercase italic">Jiwa Terintegrasi</p>
                </div>
                <!-- Wilayah Count -->
                <div class="bg-dark p-12 rounded-5xl shadow-2xl border border-white/5 group transition-all duration-700 relative overflow-hidden text-center">
                    <p class="text-[12px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4">Wilayah</p>
                    <h3 class="text-7xl font-black text-white italic tracking-tighter">{{ $stats['total_wilayah'] }}</h3>
                    <div class="mt-8 flex justify-center">
                        <div class="h-1 w-10 bg-accent rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. AGENDA PELAYANAN (Dinamis: $schedules) -->
    <section id="jadwal" class="py-48 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end gap-10 mb-32">
                <div class="max-w-2xl">
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.6em] mb-6 italic leading-none">Worship Schedule</h2>
                    <h3 class="text-7xl font-serif italic text-slate-900 tracking-tighter leading-none">Agenda Pelayanan<br><span class="text-slate-300">Pekan Ini</span></h3>
                </div>
                <p class="text-slate-400 text-sm font-medium max-w-xs text-left md:text-right uppercase tracking-[0.2em] leading-relaxed italic">"Sebab di mana dua atau tiga orang berkumpul dalam Nama-Ku, di situ Aku ada."</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @forelse($schedules as $sch)
                <div class="group bg-slate-50 p-12 rounded-5xl hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col h-full border border-slate-100 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-14 relative z-10">
                        <div class="h-20 w-20 bg-primary text-white rounded-3xl flex items-center justify-center shadow-2xl shadow-primary/30 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 bg-white px-5 py-2 rounded-full border border-slate-100 shadow-sm italic">
                            {{ $sch->type->nama }}
                        </span>
                    </div>

                    <p class="text-[11px] font-black text-primary uppercase tracking-[0.4em] mb-4 italic relative z-10">
                        {{ $sch->tanggal->isoFormat('dddd, D MMMM') }}
                    </p>
                    <h4 class="text-4xl font-black text-slate-900 uppercase italic mb-12 leading-none relative z-10 tracking-tighter">
                        {{ $sch->tema ?? ($sch->family ? 'Syukur Kel. '.$sch->family->kepala_keluarga : 'Ibadah Rutin') }}
                    </h4>

                    <div class="mt-auto relative z-10">
                        <div class="flex items-center gap-5 p-6 bg-white rounded-4xl shadow-sm border border-slate-50 group-hover:border-primary/10 transition-all">
                            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-primary flex items-center justify-center font-black text-lg uppercase shadow-inner">
                                {{ substr($sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? '?', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5 italic">Pelayan Firman</p>
                                <p class="text-sm font-extrabold text-slate-800 truncate">
                                    {{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Majelis Bertugas' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 text-center bg-slate-50 rounded-5xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-black uppercase text-xs tracking-widest italic opacity-50">Belum ada agenda yang dipublikasikan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. E-GALLERY DOCUMENTATION (Dinamis: $galleries) -->
    <section id="galeri" class="py-48 px-6 lg:px-10 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-32">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.7em] mb-8 italic">Visual Testimony</h2>
                <h3 class="text-7xl font-serif italic text-slate-900 tracking-tighter leading-none uppercase">Dokumentasi <span class="text-slate-300">Pelayanan</span></h3>
            </div>

            <div class="flex gap-10 overflow-x-auto no-scrollbar pb-20 -mx-6 px-6">
                @forelse($galleries as $gallery)
                <div class="min-w-[400px] group relative rounded-5xl overflow-hidden shadow-2xl aspect-[3/4.5] transition-all hover:-translate-y-6 duration-1000">
                    <img src="{{ asset('storage/' . $gallery->file_path) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-[3s]" alt="{{ $gallery->judul }}" onerror="this.src='https://images.unsplash.com/photo-1544427928-c49cdfebf194?auto=format&fit=crop&q=80&w=800'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-700"></div>
                    <div class="absolute bottom-14 left-14 right-14 text-white">
                        <div class="h-1 w-0 bg-primary mb-6 group-hover:w-full transition-all duration-1000"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.5em] text-blue-400 mb-4 italic">{{ $gallery->kategori }}</p>
                        <h4 class="text-4xl font-black italic uppercase leading-none tracking-tighter">{{ $gallery->judul }}</h4>
                    </div>
                </div>
                @empty
                <div class="w-full py-32 text-center bg-white rounded-5xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-300 font-black uppercase text-[11px] tracking-[0.5em]">Belum ada foto yang dibagikan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- 6. WARTA JEMAAT (Dinamis: $posts) -->
    <section id="berita" class="py-48 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-24">
                <div class="lg:col-span-4">
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.6em] mb-8 italic">Church Bulletin</h2>
                    <h3 class="text-7xl font-serif italic text-slate-900 tracking-tighter leading-none uppercase mb-10">Warta <br><span class="text-slate-300">Jemaat</span></h3>
                    <p class="text-slate-500 text-lg leading-loose italic mb-14">Temukan kabar terbaru, renungan, dan pengumuman dari setiap wilayah pelayanan GKS Jemaat Reda Pada.</p>
                    <button class="px-14 py-6 border-2 border-slate-900 rounded-full font-black text-xs uppercase tracking-[0.3em] hover:bg-slate-900 hover:text-white transition-all shadow-2xl hover:shadow-slate-300">Arsip Warta</button>
                </div>

                <div class="lg:col-span-8 space-y-20">
                    @forelse($posts as $post)
                    <a href="#" class="flex flex-col sm:flex-row gap-12 items-center group cursor-pointer border-b border-slate-100 pb-20 last:border-0 last:pb-0">
                        <div class="w-full sm:w-80 h-80 rounded-5xl overflow-hidden shadow-2xl relative shrink-0">
                            <img src="{{ asset('storage/' . $post->gambar_fitur) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]" alt="{{ $post->judul }}" onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=800'">
                            <div class="absolute inset-0 bg-primary/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="pt-4">
                            <span class="text-[10px] font-black text-primary bg-blue-50 px-5 py-2 rounded-full uppercase tracking-[0.25em] italic">{{ $post->kategori }}</span>
                            <h4 class="text-4xl font-black text-slate-900 uppercase italic mt-8 group-hover:text-primary transition-colors leading-[1.05] tracking-tighter">{{ $post->judul }}</h4>
                            <div class="flex items-center gap-5 mt-10">
                                <div class="h-px flex-1 bg-slate-100"></div>
                                <p class="text-[11px] text-slate-400 font-extrabold uppercase tracking-widest italic">
                                    {{ $post->published_at->isoFormat('D MMM Y') }} • {{ $post->author->name }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="py-20 text-center text-slate-300 font-black uppercase text-[11px] tracking-[0.5em]">Tidak ada berita minggu ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <!-- 7. FINANCIAL GLASSMORPHISM (Dinamis: $saldo) -->
    <section id="keuangan" class="py-48 px-6 lg:px-10 bg-dark relative overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h2 class="text-xs font-black text-emerald-400 uppercase tracking-[0.8em] mb-12 italic leading-none">Trust & Accountability</h2>
            <h3 class="text-7xl sm:text-9xl font-serif italic text-white mb-24 leading-none tracking-tighter uppercase">Integritas <span class="text-white/20">Kas</span></h3>

            <div class="bg-white/5 border border-white/10 backdrop-blur-[60px] rounded-[5rem] p-20 sm:p-32 shadow-2xl relative overflow-hidden group hover:bg-white/10 transition-all duration-1000 finance-glow">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-emerald-500 to-transparent opacity-40"></div>

                <p class="text-[13px] font-black text-slate-500 uppercase tracking-[0.6em] mb-10 leading-none italic">Saldo Kas Umum Terverifikasi 2026</p>

                <h4 class="text-8xl sm:text-[12rem] font-black text-emerald-400 italic tracking-tighter transition-all group-hover:scale-105 duration-1000">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h4>

                <div class="flex justify-center items-center gap-5 mt-20">
                    <div class="h-3 w-3 rounded-full bg-emerald-500 animate-ping"></div>
                    <p class="text-[11px] text-slate-400 font-black uppercase tracking-[0.5em] italic">Audit Sistem Real-Time SIG-GKS</p>
                </div>

                <!-- SVG Icon Pattern -->
                <div class="absolute -right-32 -bottom-32 opacity-5 pointer-events-none transition-all group-hover:rotate-12 duration-1000 text-emerald-500">
                    <svg class="w-[45rem] h-[45rem]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Decoration Gradients -->
        <div class="absolute -left-64 -bottom-64 w-[800px] h-[800px] bg-emerald-600/10 rounded-full blur-[200px] pointer-events-none"></div>
        <div class="absolute -right-64 -top-64 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[200px] pointer-events-none"></div>
    </section>
</x-layouts.web>