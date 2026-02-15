<x-layouts.web>
    
    <!-- 1. HERO SECTION -->
    <header id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-slate-900">
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1920" 
             class="absolute inset-0 w-full h-full object-cover opacity-50 scale-105 animate-[pulse_60s_infinite]" 
             alt="GKS Reda Pada">
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
        
        <div class="relative z-10 text-center px-6 max-w-5xl mt-20 pb-32">
            <div class="inline-flex items-center gap-2 px-5 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.4em]">Gereja Kristen Sumba</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl lg:text-9xl font-serif text-white italic mb-10 leading-[0.9] tracking-tighter drop-shadow-2xl animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
                Melayani dengan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-white to-amber-100/50">Kasih Kristus.</span>
            </h1>

            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto mb-12 font-medium leading-relaxed animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-300">
                Selamat datang di portal informasi resmi {{ $setting->nama_gereja ?? 'GKS Jemaat Reda Pada' }}. Jadwal pelayanan, warta jemaat, dan transparansi laporan keuangan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-in fade-in slide-in-from-bottom-12 duration-1000 delay-500">
                <a href="#jadwal" class="w-full sm:w-auto px-12 py-5 bg-white text-primary rounded-full font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl hover:scale-105 transition-all">
                    Lihat Agenda Ibadah
                </a>
                <a href="#keuangan" class="w-full sm:w-auto px-12 py-5 bg-transparent border border-white/30 text-white rounded-full font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 transition-all backdrop-blur-sm">
                    Laporan Transparansi
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/30">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </header>

    <!-- 2. STATISTIK JEMAAT -->
    <section class="relative -mt-16 z-20 px-6 lg:px-10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6" 
             x-data="{ 
                countKK: 0, countJiwa: 0, countWil: 0,
                startCounters() {
                    let speed = 20;
                    let intKK = setInterval(() => { if(this.countKK < {{ $stats['total_kk'] }}) this.countKK++; else clearInterval(intKK); }, speed);
                    let intJiwa = setInterval(() => { if(this.countJiwa < {{ $stats['total_jiwa'] }}) this.countJiwa += Math.ceil({{ $stats['total_jiwa'] }}/20); else { this.countJiwa = {{ $stats['total_jiwa'] }}; clearInterval(intJiwa); } }, 30);
                    let intWil = setInterval(() => { if(this.countWil < {{ $stats['total_wilayah'] }}) this.countWil++; else clearInterval(intWil); }, 100);
                }
             }" x-init="startCounters()">
            
            <div class="bg-white p-10 rounded-[3.5rem] shadow-2xl border border-slate-100 relative overflow-hidden group">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-3">Kepala Keluarga</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-7xl font-black text-primary tracking-tighter leading-none" x-text="countKK">0</h3>
                    <span class="text-xl font-bold text-slate-300 uppercase">Kk</span>
                </div>
            </div>

            <div class="bg-primary p-10 rounded-[3.5rem] shadow-2xl text-white relative overflow-hidden group">
                <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.4em] mb-3">Total Jemaat</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-7xl font-black text-white tracking-tighter leading-none" x-text="countJiwa">0</h3>
                    <span class="text-xl font-bold text-blue-300 uppercase">Jiwa</span>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-400/20 rounded-full blur-3xl"></div>
            </div>

            <div class="bg-white p-10 rounded-[3.5rem] shadow-2xl border border-slate-100 relative overflow-hidden flex flex-col justify-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-3">Wilayah Pelayanan</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-7xl font-black text-slate-900 tracking-tighter leading-none" x-text="countWil">0</h3>
                    <span class="text-xl font-bold text-slate-200 uppercase">Wil</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. KATA SAMBUTAN & AYAT HARIAN -->
    <section class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2 relative group">
                <div class="aspect-[4/5] rounded-[4rem] overflow-hidden shadow-2xl border-8 border-slate-50 relative z-10">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-1000" alt="Pendeta Jemaat">
                </div>
                <div class="absolute -bottom-6 -right-6 w-64 h-64 bg-amber-400 rounded-full blur-[100px] opacity-20 -z-10 group-hover:opacity-40 transition-opacity"></div>
                <div class="absolute -top-10 -left-10 p-10 bg-primary text-white rounded-[3rem] shadow-xl z-20 hidden md:block animate-bounce duration-[3s]">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V5C14.017 3.89543 14.9124 3 16.017 3H21.017C22.1216 3 23.017 3.89543 23.017 5V15C23.017 18.3137 20.3307 21 17.017 21H14.017ZM1.017 21L1.017 18C1.017 16.8954 1.91243 16 3.017 16H6.017C6.56928 16 7.017 15.5523 7.017 15V9C7.017 8.44772 6.56928 8 6.017 8H3.017C1.91243 8 1.017 7.10457 1.017 6V5C1.017 3.89543 1.91243 3 3.017 3H8.017C9.12157 3 10.017 3.89543 10.017 5V15C10.017 18.3137 7.33071 21 4.017 21H1.017Z"/></svg>
                </div>
            </div>
            <div class="lg:w-1/2">
                <span class="text-[10px] font-black text-amber-500 uppercase tracking-[0.5em] block mb-4 italic">Salam Gembala</span>
                <h3 class="text-4xl md:text-5xl font-serif italic text-slate-900 mb-8 tracking-tighter">"Selamat datang di rumah doa bagi segala bangsa."</h3>
                <p class="text-slate-600 font-medium leading-loose mb-10">
                    Puji Tuhan atas kesempatan yang diberikan-Nya bagi kita untuk terus bersatu dalam pelayanan. Melalui wadah digital ini, kami berharap jemaat dapat lebih mudah mendapatkan informasi dan terhubung dalam kasih Kristus. Kiranya iman kita terus bertumbuh dan berbuah bagi kemuliaan-Nya.
                </p>
                <div class="border-l-4 border-primary pl-6">
                    <h5 class="font-black text-slate-900 uppercase text-lg tracking-tighter leading-none">Pdt. Alponia Malo, S.Th</h5>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Ketua Majelis Jemaat</p>
                </div>
            </div>
        </div>

        <!-- Ayat Hari Ini -->
        <div class="max-w-4xl mx-auto mt-32 p-12 rounded-[4rem] bg-slate-50 border border-slate-100 text-center italic group hover:bg-white hover:shadow-2xl transition-all duration-700">
            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.5em] block mb-6">Ayat Hari Ini</span>
            <p class="text-xl md:text-2xl font-serif text-slate-700 leading-relaxed group-hover:text-primary">
                "Tetapi carilah dahulu Kerajaan Allah dan kebenarannya, maka semuanya itu akan ditambahkan kepadamu."
            </p>
            <p class="mt-4 font-black text-[10px] uppercase tracking-widest text-slate-400">— Matius 6:33</p>
        </div>
    </section>

    <!-- 4. JADWAL IBADAH -->
    <section id="jadwal" class="py-32 px-6 lg:px-10 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.5em] mb-4">Agenda Mendatang</h2>
                <h3 class="text-5xl md:text-6xl font-serif italic text-slate-900 tracking-tighter">Jadwal Peribadatan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($schedules as $sch)
                    @php
                        $head = $sch->family?->members->sortBy('hubungan_keluarga_id')->first();
                        $hostName = $head->churchPeople->full_name ?? 'Keluarga';
                    @endphp
                    <div class="bg-white rounded-[45px] p-8 border border-slate-100 hover:shadow-2xl transition-all duration-500 group flex flex-col h-full">
                        <div class="flex justify-between items-center mb-8 relative">
                            <div class="flex items-center gap-4">
                                <div class="bg-slate-50 p-4 rounded-3xl shadow-sm text-center border border-slate-100 min-w-[70px]">
                                    <span class="block text-2xl font-black text-slate-900 leading-none">{{ $sch->tanggal->format('d') }}</span>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ $sch->tanggal->format('M') }}</span>
                                </div>
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-primary text-white mb-1 shadow-md shadow-primary/20">
                                        {{ $sch->type->nama }}
                                    </span>
                                    <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1 uppercase">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $sch->jam_mulai->format('H:i') }} WITA
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Tuan Rumah / Lokasi</p>
                            <h4 class="text-2xl font-black text-slate-900 leading-tight mb-4 uppercase tracking-tighter">
                                {{ $sch->family_id ? 'Kel. ' . $hostName : $sch->lokasi_manual }}
                            </h4>
                            
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 mb-6 bg-slate-50 w-fit px-3 py-1.5 rounded-full border border-slate-100">
                                <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $sch->family->wilayah->nama ?? 'Umum' }}
                            </div>

                            <div class="bg-slate-900 p-5 rounded-[2rem] flex items-center gap-4 text-white">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-[10px] font-black shrink-0">PF</div>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Pelayan Firman</p>
                                    <p class="text-sm font-bold truncate uppercase text-amber-200">{{ $sch->servants->where('peran', 'Pembaca Firman')->first()?->member?->churchPeople?->full_name ?? 'Ditentukan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-[4rem] border border-dashed border-slate-200 text-slate-300 italic">Jadwal belum diperbarui.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. WARTA & GALERI -->
    <section class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20">
            <div>
                <h3 class="text-3xl font-serif italic text-slate-900 mb-12 flex items-center gap-4">
                    <span class="w-1.5 h-10 bg-primary rounded-full"></span> Kabar Jemaat
                </h3>
                <div class="space-y-10">
                    @forelse($posts as $post)
                    <a href="{{ route('public.warta.show', $post->slug) }}" class="flex gap-6 group">
                        <div class="w-24 h-24 rounded-3xl overflow-hidden shrink-0 shadow-lg border border-slate-100">
                            <img src="{{ asset('storage/' . $post->gambar_fitur) }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700" onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=300'">
                        </div>
                        <div class="flex-1 py-1">
                            <span class="text-[9px] font-black text-primary bg-primary/5 px-3 py-1 rounded-full uppercase tracking-widest">{{ $post->kategori }}</span>
                            <h4 class="text-xl font-bold text-slate-900 mt-3 mb-1 group-hover:text-primary transition-colors leading-tight">{{ $post->judul }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $post->published_at->isoFormat('D MMMM Y') }}</p>
                        </div>
                    </a>
                    @empty
                        <p class="text-slate-300 italic">Belum ada warta terbaru.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-3xl font-serif italic text-slate-900 mb-12 flex items-center gap-4">
                    <span class="w-1.5 h-10 bg-amber-400 rounded-full"></span> Galeri Pelayanan
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($galleries as $gallery)
                    <div class="relative aspect-square rounded-[2.5rem] overflow-hidden group shadow-xl border-4 border-slate-50">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-6 text-center">
                            <span class="text-white text-[10px] font-black uppercase tracking-widest">{{ $gallery->judul }}</span>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-2 py-20 text-center bg-slate-50 rounded-[3rem] border border-dashed border-slate-200 text-slate-300 italic">Galeri belum tersedia.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- 6. TRANSPARANSI KAS -->
    <section id="keuangan" class="py-32 px-6 lg:px-10 bg-slate-950 relative overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h2 class="text-xs font-black text-emerald-400 uppercase tracking-[0.5em] mb-10 italic">Laporan Transparansi Kas</h2>
            <div class="bg-white/5 border border-white/10 backdrop-blur-2xl rounded-[5rem] p-16 sm:p-24 shadow-2xl relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-700">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-6 leading-none">Saldo Kas Umum Saat Ini (Live)</p>
                <div class="inline-flex items-center justify-center gap-4 text-6xl sm:text-8xl lg:text-9xl font-black text-emerald-400 italic tracking-tighter">
                    <span class="text-2xl sm:text-4xl font-serif text-emerald-900 not-italic tracking-normal mr-2">Rp</span>
                    {{ number_format($saldo, 0, ',', '.') }}
                </div>
                <div class="flex justify-center items-center gap-4 mt-12 pt-10 border-t border-white/5">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em]">Diperbarui Otomatis oleh Sistem Keuangan SIG-GKS</p>
                </div>
            </div>
        </div>
        <div class="absolute -left-20 -bottom-20 w-[800px] h-[800px] bg-emerald-500/5 rounded-full blur-[150px]"></div>
    </section>

    <!-- 7. LOKASI & KONTAK (BAGIAN BARU) -->
    <section class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="order-2 lg:order-1">
                    <div class="aspect-video w-full rounded-[4rem] overflow-hidden shadow-2xl border-8 border-slate-50">
                        <!-- Placeholder Google Maps -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15764.0864356064!2d119.2345!3d-9.4567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOcKwMjcnMjQuMSJTIDExOcKwMTQnMDQuMiJF!5e0!3m2!1sid!2sid!4v1645000000000!5m2!1sid!2sid" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="grayscale hover:grayscale-0 transition-all duration-1000"></iframe>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.5em] block mb-4 italic">Kunjungi Kami</span>
                    <h3 class="text-4xl font-serif italic text-slate-900 mb-8 tracking-tighter leading-tight">Rumah Ibadah &<br>Sekretariat Jemaat</h3>
                    
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-primary shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                                <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $setting->alamat ?? 'Lolo Ole, Kec. Kota Tambolaka, Kabupaten Sumba Barat Daya, NTT' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-primary shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"/></svg></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email & Telepon</p>
                                <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $setting->email ?? 'info@gksredapada.org' }}<br>{{ $setting->telepon ?? '0812-xxxx-xxxx' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <a href="https://wa.me/{{ $setting->telepon ?? '' }}" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-600 transition-all">WhatsApp Sekretariat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white pt-24 pb-12 px-6 lg:px-10 border-t border-slate-100">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 mb-2 leading-none">{{ $setting->nama_gereja ?? 'GKS JEMAAT REDA PADA' }}</h2>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic">Sumba Barat Daya, Nusa Tenggara Timur</p>
            <p class="text-[9px] font-black text-slate-200 uppercase tracking-[0.5em]">© 2026 - Digital Transformation for Church Excellence</p>
        </div>
    </footer>

</x-layouts.web>