<x-layouts.web>
    <x-slot:title>Profil & Struktur Pelayanan | GKS Jemaat Reda Pada</x-slot>

    <!-- 1. HERO SECTION -->
    <section class="relative pt-24 pb-36 px-6 lg:px-10 overflow-hidden bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-3 px-5 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Profil Gereja</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-[0.9] animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                Bertumbuh dalam <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-200">Kasih & Pelayanan</span>
            </h1>
            
            <p class="text-slate-400 text-sm md:text-lg font-medium max-w-3xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                Mengenal sejarah, visi, dan para pelayan Tuhan yang mendedikasikan hidupnya bagi pertumbuhan iman di GKS Jemaat Reda Pada.
            </p>
        </div>

        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-primary/20 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
    </section>

    <!-- 2. SEJARAH SINGKAT -->
    <section class="py-24 px-6 lg:px-10 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row gap-16 items-start">
                <div class="md:w-1/3 sticky top-32">
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] mb-4 border-l-4 border-amber-500 pl-4">Sejarah Iman</h2>
                    <h3 class="text-4xl font-serif text-slate-900 italic leading-tight">Jejak Perjalanan Jemaat</h3>
                </div>
                <div class="md:w-2/3 prose prose-lg prose-slate text-slate-600 font-medium leading-loose">
                    @if($setting?->sejarah_singkat)
                        {!! nl2br(e($setting->sejarah_singkat)) !!}
                    @else
                        <p class="italic text-slate-400">Narasi sejarah sedang dalam tahap penyusunan oleh Majelis Jemaat.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- 3. VISI & MISI -->
    <section class="py-32 px-6 lg:px-10 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Visi -->
                <div class="lg:col-span-2 bg-slate-900 p-12 rounded-[3.5rem] text-white relative overflow-hidden group border border-slate-800">
                    <div class="absolute -right-10 -bottom-10 opacity-5 group-hover:scale-110 transition-transform duration-1000">
                        <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-400 mb-8">Visi Pelayanan</p>
                    <h4 class="text-3xl md:text-5xl font-serif italic leading-tight">
                        "{{ $setting->visi ?? 'Menjadi Jemaat yang Mandiri, Berbuah, dan Menjadi Berkat bagi Sesama.' }}"
                    </h4>
                </div>

                <!-- Misi -->
                <div class="bg-white p-12 rounded-[3.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 flex flex-col justify-center">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-10">Misi Kami</p>
                    <ul class="space-y-6">
                        @if($setting && $setting->misi && is_array($setting->misi))
                            @foreach($setting->misi as $index => $misi)
                                <li class="flex gap-4 items-start group">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black group-hover:bg-primary group-hover:text-white transition-all">{{ $index + 1 }}</span>
                                    <span class="text-sm font-bold text-slate-700 leading-relaxed">{{ $misi }}</span>
                                </li>
                            @endforeach
                        @else
                             <li class="text-sm text-slate-400 italic">Data misi belum diinput.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. PIMPINAN JEMAAT (PENDETA/VIKARIS) -->
    <section class="py-32 px-6 lg:px-10 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.5em] block mb-4">Pelayan Firman</span>
                <h3 class="text-5xl md:text-6xl font-serif italic text-slate-900 tracking-tight leading-none">Pimpinan Jemaat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @forelse($pastors as $pastor)
                <div class="group flex flex-col sm:flex-row items-center gap-10 p-12 rounded-[4rem] bg-slate-50 border border-transparent hover:bg-white hover:border-slate-100 hover:shadow-2xl transition-all duration-700">
                    <div class="w-40 h-40 md:w-52 md:h-52 rounded-[3rem] bg-slate-200 overflow-hidden relative shadow-inner flex items-center justify-center text-6xl font-serif italic text-slate-400 shrink-0 group-hover:scale-105 transition-transform duration-700 border-4 border-white">
                        {{ substr($pastor->member->churchPeople->full_name, 0, 1) }}
                    </div>
                    <div class="text-center sm:text-left">
                        <span class="inline-block px-5 py-2 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-6">{{ $pastor->position->nama }}</span>
                        <h4 class="text-3xl font-black text-slate-900 leading-tight mb-4 group-hover:text-primary transition-colors">{{ $pastor->member->churchPeople->full_name }}</h4>
                        <div class="space-y-1">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Aktif Sejak: {{ $pastor->tanggal_mulai ? $pastor->tanggal_mulai->format('Y') : '-' }}</p>
                            @if($pastor->nip_gereja)
                                <p class="text-[10px] text-slate-300 font-mono italic">Reg: {{ $pastor->nip_gereja }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border border-dashed border-slate-200 text-slate-400 font-medium">Data Pimpinan Jemaat belum diperbarui.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. MAJELIS JEMAAT (PENATUA, DIAKEN, DLL) -->
    <section class="py-32 px-6 lg:px-10 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end gap-10 mb-20 border-b border-slate-200 pb-12">
                <div class="max-w-2xl text-left">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.5em] block mb-4">Badan Majelis</span>
                    <h3 class="text-5xl font-serif italic text-slate-900 tracking-tight leading-none">Majelis Jemaat</h3>
                </div>
                <p class="text-slate-400 text-[9px] font-bold uppercase tracking-[0.3em] text-right italic leading-relaxed">
                    "Setiap orang haruslah dipandang sebagai pelayan Kristus yang dipercayakan rahasia Allah."
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($officers as $off)
                <div class="p-8 rounded-[3rem] border border-white bg-white/60 backdrop-blur-sm hover:shadow-2xl hover:bg-white hover:-translate-y-2 transition-all duration-500 text-center group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <div class="w-20 h-20 mx-auto rounded-[2rem] bg-slate-100 flex items-center justify-center text-2xl font-serif italic text-slate-300 mb-6 shadow-inner group-hover:bg-slate-900 group-hover:text-white transition-all duration-500 uppercase">
                        {{ substr($off->member->churchPeople->full_name, 0, 1) }}
                    </div>
                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-tight mb-2 truncate px-2">{{ $off->member->churchPeople->full_name }}</h5>
                    <p class="text-[9px] font-black text-primary uppercase tracking-widest leading-relaxed">{{ $off->position->nama }}</p>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center text-slate-400 italic">Data Majelis Jemaat sedang dimigrasikan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 6. CALL TO ACTION -->
    <section class="py-32 px-6 lg:px-10 bg-primary relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-6xl font-serif italic text-white mb-8">Informasi Administrasi?</h2>
            <p class="text-blue-200 text-sm md:text-base font-medium mb-12 max-w-xl mx-auto leading-relaxed">
                Jika Anda membutuhkan layanan surat keterangan, pastoral, atau administrasi jemaat lainnya, tim sekretariat kami siap melayani Anda.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/{{ $setting->telepon ?? '' }}" class="px-10 py-5 bg-white text-primary rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">Hubungi Sekretariat</a>
            </div>
        </div>
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-white/5 rounded-full"></div>
    </section>

</x-layouts.web>