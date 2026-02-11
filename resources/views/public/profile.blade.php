<x-layouts.web>
    <x-slot:title>Profil & Sejarah | GKS Jemaat Reda Pada</x-slot>

    <!-- 1. HERO SECTION -->
    <section class="relative pt-48 pb-32 px-6 lg:px-10 overflow-hidden bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-3 px-5 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Tentang Kami</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-[0.9] animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                Gereja yang <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200">Hidup & Berbuah</span>
            </h1>
            
            <p class="text-slate-400 text-sm md:text-lg font-medium max-w-3xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                Mengenal lebih dekat identitas, sejarah, dan orang-orang yang melayani di GKS Jemaat Reda Pada.
            </p>
        </div>

        <!-- Background Elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-primary/20 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-surface to-transparent"></div>
    </section>

    <!-- 2. SEJARAH SINGKAT -->
    <section class="py-24 px-6 lg:px-10 bg-surface">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row gap-16 items-start">
                <div class="md:w-1/3 sticky top-32">
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4 border-l-4 border-accent pl-4">Sejarah Iman</h2>
                    <h3 class="text-4xl font-serif text-slate-900 italic leading-tight">Perjalanan Iman di Tanah Sumba</h3>
                </div>
                <div class="md:w-2/3 prose prose-lg prose-slate text-slate-600 font-medium leading-loose">
                    @if($setting->sejarah_singkat)
                        {!! nl2br(e($setting->sejarah_singkat)) !!}
                    @else
                        <p class="italic text-slate-400">Belum ada data sejarah yang diinput oleh admin. Silakan lengkapi di menu Pengaturan Gereja.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- 3. VISI & MISI (BENTO GRID) -->
    <section class="py-32 px-6 lg:px-10 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4">Arah Pelayanan</h2>
                <h3 class="text-5xl font-serif italic text-slate-900">Visi & Misi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Visi (Besar) -->
                <div class="md:col-span-2 bg-primary p-12 rounded-[3rem] text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-12 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-200 mb-6">Visi Kami</p>
                    <h4 class="text-3xl md:text-5xl font-serif italic leading-tight">
                        "{{ $setting->visi ?? 'Menjadi Gereja yang Mandiri dan Misioner' }}"
                    </h4>
                </div>

                <!-- Misi (List) -->
                <div class="bg-white p-12 rounded-[3rem] border border-slate-100 shadow-xl flex flex-col justify-center">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-8">Misi Pelayanan</p>
                    <ul class="space-y-6">
                        @if(!empty($setting->misi) && is_array($setting->misi))
                            @foreach($setting->misi as $index => $misi)
                            <li class="flex gap-4 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-accent/10 text-accent flex items-center justify-center text-[10px] font-black">{{ $index + 1 }}</span>
                                <span class="text-sm font-bold text-slate-700 leading-relaxed">{{ $misi }}</span>
                            </li>
                            @endforeach
                        @else
                             <li class="text-sm text-slate-400 italic">Data misi belum diatur.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. STRUKTUR PELAYAN -->
    <section class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-20">
                <div>
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4">Kepemimpinan</h2>
                    <h3 class="text-5xl font-serif italic text-slate-900">Majelis & Pelayan</h3>
                </div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest text-right max-w-xs">Mereka yang dipercayakan untuk menggembalakan dan melayani jemaat.</p>
            </div>

            <!-- Pendeta Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-20">
                @forelse($pastors as $pastor)
                <div class="flex items-center gap-8 p-8 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:border-primary/20 transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-3xl bg-slate-200 flex items-center justify-center text-4xl font-serif italic text-slate-400 overflow-hidden relative shadow-inner group-hover:scale-105 transition-transform">
                        <!-- Placeholder Foto (bisa diganti img src jika ada fitur upload foto personil) -->
                        <span>{{ substr($pastor->member->nama, 0, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-accent uppercase tracking-widest bg-white px-3 py-1 rounded-full shadow-sm">{{ $pastor->position->nama }}</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-3 mb-1 group-hover:text-primary transition-colors">{{ $pastor->member->nama }}</h4>
                        <p class="text-xs text-slate-500 font-medium">Periode: {{ $pastor->tanggal_mulai ? $pastor->tanggal_mulai->format('Y') : '-' }} - Sekarang</p>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-10 text-slate-300 font-bold italic">Data Pendeta belum diinput.</div>
                @endforelse
            </div>

            <!-- Majelis Lainnya -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($officers as $off)
                <div class="p-6 rounded-3xl border border-slate-100 hover:border-slate-300 hover:bg-slate-50 transition-all text-center group">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-xl font-serif italic text-slate-400 mb-4 shadow-sm group-hover:bg-white group-hover:shadow-md transition-all">
                        {{ substr($off->member->nama, 0, 1) }}
                    </div>
                    <h5 class="text-sm font-black text-slate-800 uppercase tracking-tight mb-1 truncate">{{ $off->member->nama }}</h5>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $off->position->nama }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. CALL TO ACTION -->
    <section class="py-32 px-6 lg:px-10 bg-primary relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-6xl font-serif italic text-white mb-8">"Melayani Bukan Untuk Dilayani"</h2>
            <p class="text-blue-200 text-sm md:text-base font-medium mb-12 max-w-xl mx-auto leading-relaxed">
                Bergabunglah dalam persekutuan kami. Jika Anda membutuhkan layanan administrasi atau pastoral, silakan hubungi sekretariat.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/{{ $setting->telepon ?? '' }}" class="px-10 py-5 bg-white text-primary rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">Hubungi WhatsApp</a>
                <a href="{{ url('/#jadwal') }}" class="px-10 py-5 bg-primary border border-white/20 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all">Lihat Jadwal</a>
            </div>
        </div>
        
        <!-- Decorative Rings -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-white/5 rounded-full"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/5 rounded-full"></div>
    </section>

</x-layouts.web>