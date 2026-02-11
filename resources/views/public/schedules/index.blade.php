<div>
    <!-- HERO SECTION -->
    <section class="relative pt-40 pb-24 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.2em]">Real-time Schedule</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-6 tracking-tighter animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                Waktu <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200">Tuhan.</span>
            </h1>
            <p class="text-slate-400 text-sm md:text-base font-medium max-w-xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                "Untuk segala sesuatu ada masanya, untuk apapun di bawah langit ada waktunya." (Pengkhotbah 3:1)
            </p>
        </div>
        
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-primary/20 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent z-10"></div>
    </section>

    <!-- FILTER BAR (LIVEWIRE POWERED) -->
    <div class="sticky top-20 z-40 px-4 md:px-6 -mt-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl border border-white/40 p-4 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex flex-col lg:flex-row gap-4 items-center ring-1 ring-slate-900/5 relative overflow-hidden">
                
                <!-- Loading Progress Bar (Top) -->
                <div wire:loading class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-emerald-400 to-primary animate-gradient-x"></div>

                <!-- Search -->
                <div class="relative w-full lg:flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-14 pr-10 py-4 bg-slate-50 border-none rounded-[2rem] font-bold text-sm focus:ring-4 focus:ring-primary/10 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Cari Pelayan, Keluarga, atau Topik...">
                    
                    <!-- Clear Search Button -->
                    @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-4 flex items-center text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    @endif
                </div>

                <!-- Filters Group -->
                <div class="flex gap-3 w-full lg:w-auto overflow-x-auto no-scrollbar pb-2 lg:pb-0 items-center">
                    
                    <!-- Filter Jenis -->
                    <div class="relative min-w-[160px]">
                        <select wire:model.live="type_id" class="w-full bg-slate-50 border-none rounded-[2rem] py-4 pl-6 pr-10 font-bold text-sm text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer appearance-none hover:bg-slate-100 transition-colors">
                            <option value="">Semua Kegiatan</option>
                            @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                    </div>

                    <!-- Filter Wilayah -->
                    <div class="relative min-w-[160px]">
                        <select wire:model.live="wilayah_id" class="w-full bg-slate-50 border-none rounded-[2rem] py-4 pl-6 pr-10 font-bold text-sm text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer appearance-none hover:bg-slate-100 transition-colors">
                            <option value="">Semua Wilayah</option>
                            @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                    </div>
                    
                    <!-- Filter Tanggal -->
                    <div class="relative min-w-[140px]">
                        <input type="date" wire:model.live="start_date" class="w-full bg-slate-50 border-none rounded-[2rem] py-4 px-6 font-bold text-sm text-slate-600 focus:ring-4 focus:ring-primary/10 cursor-pointer hover:bg-slate-100 transition-colors">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LIST SECTION -->
    <section class="py-20 px-6 lg:px-10 min-h-screen bg-slate-50 relative">
        <div class="max-w-7xl mx-auto">
            
            <!-- Loading State Overlay (Membuat grid agak transparan saat loading) -->
            <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($schedules as $sch)
                    <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 group flex flex-col h-full relative overflow-hidden">
                        
                        <!-- Header: Date & Badge -->
                        <div class="flex justify-between items-start mb-8 relative z-10">
                            <div class="bg-slate-50 p-4 rounded-[20px] text-center min-w-[80px] group-hover:bg-primary group-hover:text-white transition-colors duration-500 shadow-sm">
                                <span class="block text-3xl font-black leading-none tracking-tighter">{{ $sch->tanggal->format('d') }}</span>
                                <span class="block text-[10px] font-black uppercase mt-1">{{ $sch->tanggal->format('M') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-primary mb-2 border border-blue-100 shadow-sm">{{ $sch->type->nama }}</span>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center justify-end gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $sch->jam_mulai->format('H:i') }} WITA
                                </p>
                            </div>
                        </div>

                        <!-- Body: Content -->
                        <div class="flex-1 relative z-10 mb-8">
                            @if($sch->family)
                                <div class="mb-4">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tuan Rumah</p>
                                    <h3 class="text-2xl font-black text-slate-900 leading-tight italic line-clamp-2 group-hover:text-primary transition-colors">Kel. {{ $sch->family->kepala_keluarga }}</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-wide border border-slate-200">
                                        {{ $sch->family->refWilayah->nama ?? $sch->wilayah->nama ?? 'Wilayah -' }}
                                    </span>
                                </div>
                            @else
                                <div class="mb-4">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tema / Topik</p>
                                    <h3 class="text-2xl font-black text-slate-900 leading-tight italic mb-2 group-hover:text-primary transition-colors">{{ $sch->tema ?? 'Ibadah Rutin' }}</h3>
                                </div>
                                <p class="text-xs font-bold text-slate-500 flex items-center gap-2 bg-slate-50 w-fit px-3 py-1.5 rounded-lg border border-slate-100">
                                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                                    {{ $sch->lokasi_manual ?? 'Gedung Gereja' }}
                                </p>
                            @endif
                        </div>

                        <!-- Footer: Pelayan -->
                        <div class="mt-auto relative z-10 bg-slate-50 rounded-[24px] p-6 border border-slate-100 group-hover:border-primary/20 group-hover:bg-blue-50/30 transition-all">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[9px] font-black shrink-0 shadow-md">PF</div>
                                <div class="min-w-0 pt-0.5">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Pelayan Firman</p>
                                    <p class="text-xs font-bold text-slate-800 truncate">
                                        {{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? ($sch->servants->where('peran', 'Pengkhotbah')->first()->member->nama ?? 'TBA') }}
                                    </p>
                                </div>
                            </div>
                            @php $pendampings = $sch->servants->whereNotIn('peran', ['Pembaca Firman', 'Pengkhotbah']); @endphp
                            @if($pendampings->count() > 0)
                            <div class="pt-3 border-t border-slate-200/60 border-dashed">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Tim Pelayan</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($pendampings->take(3) as $p)
                                        <span class="inline-block px-2.5 py-1 bg-white border border-slate-200 rounded-md text-[9px] font-bold text-slate-600 truncate max-w-[100px] shadow-sm">
                                            {{ explode(' ', $p->member->nama)[0] }}
                                        </span>
                                    @endforeach
                                    @if($pendampings->count() > 3)
                                        <span class="inline-block px-2 py-1 bg-slate-200 rounded-md text-[9px] font-black text-slate-500 shadow-sm">+{{ $pendampings->count() - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Decoration -->
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-gradient-to-br from-primary/5 to-accent/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                    </div>
                    @empty
                    <div class="col-span-full py-40 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-6 text-slate-300 shadow-lg border border-slate-100">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-widest italic mb-2">Tidak Ada Jadwal</h3>
                        <p class="text-slate-400 text-sm font-medium mb-6">Silakan ubah filter tanggal atau kata kunci pencarian.</p>
                        
                        <!-- Reset Button yang berfungsi -->
                        <button wire:click="$set('search', '')" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full font-black text-xs uppercase tracking-widest transition-colors">
                            Reset Pencarian
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $schedules->links() }}
            </div>
        </div>
    </section>
</div>