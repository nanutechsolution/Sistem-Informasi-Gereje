<div>
    <!-- HERO SECTION -->
    <section class="relative pt-24 pb-32 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-5 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Portal Agenda Pelayanan</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-none animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                Waktu <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-200 text-nowrap text-shadow-sm">Tuhan.</span>
            </h1>
            <p class="text-slate-400 text-sm md:text-lg font-medium max-w-xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                "Untuk segala sesuatu ada masanya, untuk apapun di bawah langit ada waktunya." <br>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mt-4 block">— Pengkhotbah 3:1</span>
            </p>
        </div>
        
        <!-- Decorative Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-primary/20 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-slate-50 to-transparent z-10"></div>
    </section>

    <!-- FILTER BAR (STICKY) -->
    <div class="sticky top-20 z-40 px-4 md:px-6 -mt-16">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/95 backdrop-blur-xl border border-slate-200 p-5 rounded-[3rem] shadow-2xl shadow-slate-200/50 flex flex-col lg:flex-row gap-4 items-center relative overflow-hidden ring-1 ring-slate-900/5">
                
                <!-- Loading Progress Bar -->
                <div wire:loading class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-amber-400 to-primary animate-pulse"></div>

                <!-- Search Input -->
                <div class="relative w-full lg:flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.400ms="search" type="text" class="w-full pl-14 pr-12 py-4 bg-slate-50 border-none rounded-[2rem] font-bold text-sm focus:ring-4 focus:ring-primary/10 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Cari pelayan, keluarga, atau tema ibadah...">
                    
                    @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-4 flex items-center text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    @endif
                </div>

                <!-- Filters Group -->
                <div class="flex gap-3 w-full lg:w-auto overflow-x-auto no-scrollbar items-center px-1">
                    <select wire:model.live="type_id" class="min-w-[180px] bg-slate-50 border-none rounded-[2rem] py-4 px-6 font-bold text-xs text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer appearance-none hover:bg-slate-100 transition-colors">
                        <option value="">Semua Kegiatan</option>
                        @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                    </select>

                    <select wire:model.live="wilayah_id" class="min-w-[180px] bg-slate-50 border-none rounded-[2rem] py-4 px-6 font-bold text-xs text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer appearance-none hover:bg-slate-100 transition-colors">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                    </select>

                    <input type="date" wire:model.live="start_date" class="min-w-[160px] bg-slate-50 border-none rounded-[2rem] py-4 px-6 font-bold text-xs text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer hover:bg-slate-100 transition-colors">
                </div>
            </div>
        </div>
    </div>

    <!-- LIST SECTION -->
    <section class="py-24 px-6 lg:px-10 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto">
            
            <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10">
                    @forelse($schedules as $sch)
                        @php
                            // Ambil Tuan Rumah (Kepala Keluarga Pertama)
                            $head = $sch->family?->members->sortBy('hubungan_keluarga_id')->first();
                            $hostName = $head->churchPeople->full_name ?? 'Keluarga';
                            
                            // Ambil Pelayan Utama (PF)
                            $pf = $sch->servants->whereIn('peran', ['Pembaca Firman', 'Pengkhotbah'])->first()?->member?->churchPeople?->full_name ?? 'Akan Ditentukan';
                            $team = $sch->servants->whereNotIn('peran', ['Pembaca Firman', 'Pengkhotbah']);
                        @endphp
                        
                        <div class="bg-white rounded-[3.5rem] p-8 sm:p-10 border border-slate-100 shadow-xl hover:shadow-2xl transition-all duration-500 group flex flex-col h-full relative overflow-hidden">
                            
                            <!-- Card Header: Date & Label -->
                            <div class="flex justify-between items-start mb-8 relative z-10">
                                <div class="bg-slate-900 text-white p-5 rounded-[24px] text-center min-w-[85px] group-hover:bg-primary transition-colors duration-500 shadow-lg group-hover:shadow-primary/20 group-hover:scale-105 transition-all">
                                    <span class="block text-3xl font-black leading-none tracking-tighter">{{ $sch->tanggal->format('d') }}</span>
                                    <span class="block text-[10px] font-black uppercase mt-1 opacity-70 tracking-widest">{{ $sch->tanggal->format('M') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] bg-blue-50 text-primary mb-2 border border-blue-100 shadow-sm">{{ $sch->type->nama }}</span>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center justify-end gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $sch->jam_mulai->format('H:i') }} WITA
                                    </p>
                                </div>
                            </div>

                            <!-- Card Body: Location & Theme -->
                            <div class="flex-1 relative z-10 mb-8">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2">Lokasi / Tuan Rumah</p>
                                <h3 class="text-2xl font-black text-slate-900 leading-tight uppercase tracking-tighter mb-4 italic group-hover:text-primary transition-colors">
                                    {{ $sch->family_id ? 'Kel. ' . $hostName : ($sch->lokasi_manual ?? 'Gedung Gereja') }}
                                </h3>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="px-3 py-1.5 rounded-xl bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider border border-slate-100 flex items-center gap-1.5">
                                        <svg class="w-3 h-3 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                        {{ $sch->family->wilayah->nama ?? ($sch->wilayah->nama ?? 'Sektor Umum') }}
                                    </span>
                                    @if(!$sch->family_id)
                                        <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-wider border border-amber-100 italic">Ibadah Umum</span>
                                    @endif
                                </div>

                                @if($sch->tema)
                                    <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 group-hover:bg-white group-hover:border-primary/10 transition-all">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tema Ibadah</p>
                                        <p class="text-sm font-bold text-slate-700 italic leading-relaxed line-clamp-3">"{{ $sch->tema }}"</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer: Servants Team -->
                            <div class="mt-auto relative z-10 space-y-4">
                                <div class="flex items-center gap-4 bg-slate-900 p-5 rounded-[2.5rem] shadow-xl group-hover:bg-primary transition-all group-hover:scale-[1.02]">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-[10px] font-black text-white shrink-0 shadow-inner">PF</div>
                                    <div class="min-w-0">
                                        <p class="text-[8px] font-black text-white/50 uppercase tracking-widest leading-none mb-1">Pelayan Firman</p>
                                        <p class="text-sm font-bold text-white truncate uppercase tracking-tighter">{{ $pf }}</p>
                                    </div>
                                </div>

                                @if($team->count() > 0)
                                    <div class="px-2">
                                        <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-2 ml-1">Anggota Tim Pelayan</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($team->take(3) as $servant)
                                                <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-500 shadow-sm truncate max-w-[120px] group-hover:bg-white transition-colors">
                                                    {{ $servant->member->churchPeople->full_name }}
                                                </span>
                                            @endforeach
                                            @if($team->count() > 3)
                                                <span class="px-2.5 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-[9px] font-black">+{{ $team->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Aesthetics Decoration -->
                            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-slate-50 rounded-full group-hover:scale-110 group-hover:bg-primary/5 transition-all duration-1000 -z-0"></div>
                        </div>
                    @empty
                        <div class="col-span-full py-40 text-center bg-white rounded-[4rem] border-2 border-dashed border-slate-200">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 ring-8 ring-slate-50/50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 uppercase tracking-widest italic mb-2">Agenda Tidak Ditemukan</h3>
                            <p class="text-slate-400 font-medium mb-8">Silakan sesuaikan filter tanggal atau kata kunci pencarian Anda.</p>
                            <button wire:click="$set('search', '')" class="px-10 py-4 bg-slate-900 text-white rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-primary transition-all shadow-xl hover:shadow-primary/20">Reset Semua Filter</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-20">
                {{ $schedules->links() }}
            </div>
        </div>
    </section>

    <!-- FOOTER CTA -->
    <section class="py-24 bg-white border-t border-slate-100 overflow-hidden relative">
        <div class="max-w-4xl mx-auto text-center px-6 relative z-10">
            <h4 class="text-4xl font-serif italic text-slate-900 mb-6 tracking-tighter">Layanan Administrasi?</h4>
            <p class="text-slate-500 mb-12 leading-relaxed max-w-2xl mx-auto">Informasi lebih lanjut mengenai permohonan pelayanan sakramen, ibadah khusus, atau pastoral dapat diajukan melalui sekretariat jemaat.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/{{ $setting->telepon ?? '' }}" class="inline-flex items-center justify-center gap-3 px-12 py-5 bg-emerald-500 text-white rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-emerald-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.237 3.483 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.65zm6.236-3.72c1.607.955 3.468 1.457 5.363 1.458h.005c5.339 0 9.687-4.348 9.689-9.688.001-2.586-1.008-5.017-2.845-6.853s-4.267-2.846-6.853-2.847c-5.34 0-9.688 4.348-9.69 9.688-.001 1.899.501 3.761 1.458 5.368l-1.016 3.71 3.892-1.02z"/></svg>
                    Chat Sekretariat
                </a>
            </div>
        </div>
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-64 h-64 bg-primary/5 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-amber-400/5 rounded-full blur-[120px]"></div>
    </section>

<style>
    /* Utility for hiding scrollbar but keeping scroll functionality */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Smooth transition for hover effects */
    .group:hover .text-shadow-sm { text-shadow: 0 1px 2px rgba(0,0,0,0.1); }
</style>

</div>
