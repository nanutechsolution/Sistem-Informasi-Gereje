<div class="min-h-screen bg-slate-50 pb-32">
    <!-- HERO SECTION -->
    <section class="relative pt-40 pb-20 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.2em]">Koleksi Video</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-6 tracking-tighter animate-in fade-in slide-in-from-bottom-8 duration-700">
                Firman <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-200">Hidup.</span>
            </h1>
            <p class="text-slate-400 text-sm md:text-lg font-medium max-w-xl mx-auto uppercase tracking-widest text-xs animate-in fade-in slide-in-from-bottom-12 duration-700 delay-100">Arsip Khotbah Minggu & Rekaman Ibadah Streaming.</p>
        </div>
        
        <!-- Background Elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-red-900/20 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent z-10"></div>
    </section>

    <!-- SEARCH BAR (STICKY) -->
    <div class="sticky top-24 z-30 px-4 md:px-6 -mt-10">
        <div class="max-w-xl mx-auto">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-white/90 backdrop-blur-xl border border-slate-200/60 rounded-full pl-14 pr-6 py-4 font-bold text-sm shadow-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500/20 transition-all placeholder:text-slate-400" placeholder="Cari judul khotbah atau nama pendeta...">
                
                <!-- Loading Spinner -->
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-6 flex items-center">
                    <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- VIDEO GRID -->
    <section class="py-20 px-4 md:px-8 max-w-7xl mx-auto">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-300">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($videos as $video)
                <div class="group cursor-pointer flex flex-col gap-4" wire:click="play('{{ $video->id }}')">
                    <!-- Thumbnail Card -->
                    <div class="relative aspect-video rounded-[2rem] overflow-hidden shadow-lg bg-slate-200 group-hover:shadow-2xl transition-all duration-500 group-hover:-translate-y-1">
                        <img src="{{ $video->thumbnail_url }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $video->judul }}">
                        
                        <!-- Overlay & Play Button -->
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/40 group-hover:scale-110 transition-transform duration-300 group-hover:bg-red-600 group-hover:border-red-600">
                                <svg class="w-6 h-6 ml-1 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                        <!-- Date Badge -->
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white border border-white/10">
                            {{ $video->tanggal->format('d M Y') }}
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="px-2">
                        <h3 class="text-xl font-bold text-slate-900 leading-tight group-hover:text-red-600 transition-colors line-clamp-2">
                            {{ $video->judul }}
                        </h3>
                        <div class="flex items-center gap-2 mt-2 text-xs font-medium text-slate-500">
                            <span class="uppercase tracking-wider font-bold text-slate-700">{{ $video->pengkhotbah }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ number_format($video->views) }} ditonton</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-6 text-slate-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Video tidak ditemukan.</p>
                    <button wire:click="$set('search', '')" class="mt-4 text-red-600 text-xs font-black uppercase hover:underline">Reset Pencarian</button>
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-16">
            {{ $videos->links() }}
        </div>
    </section>

    <!-- VIDEO PLAYER MODAL -->
    @if($activeVideo)
    <div class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl flex items-center justify-center p-4 sm:p-6 animate-in fade-in duration-300" @click.self="closePlayer">
        <div class="w-full max-w-5xl bg-slate-900 rounded-[2rem] overflow-hidden shadow-2xl relative flex flex-col max-h-[90vh]">
            
            <!-- Close Button -->
            <button wire:click="closePlayer" class="absolute top-4 right-4 z-50 p-2 bg-black/50 hover:bg-red-600 text-white rounded-full transition-colors backdrop-blur-sm group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Video Wrapper -->
            <div class="relative w-full" style="padding-top: 56.25%;">
                <iframe class="absolute top-0 left-0 w-full h-full" src="{{ $activeVideo->embed_url }}?autoplay=1&rel=0&modestbranding=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <!-- Info Area -->
            <div class="p-8 overflow-y-auto">
                <div class="flex flex-col md:flex-row gap-6 md:items-start justify-between">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-serif italic text-white mb-2 leading-tight">{{ $activeVideo->judul }}</h2>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-400">
                            <span class="font-bold text-red-400 uppercase tracking-wider">{{ $activeVideo->pengkhotbah }}</span>
                            <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                            <span>{{ $activeVideo->tanggal->isoFormat('D MMMM Y') }}</span>
                        </div>
                    </div>
                    
                    <!-- Share Button (Optional) -->
                    <div class="flex gap-2">
                         <a href="https://wa.me/?text=Simak khotbah ini: {{ urlencode(route('public.sermons')) }}" target="_blank" class="px-4 py-2 bg-white/10 hover:bg-emerald-600 rounded-full text-white text-xs font-bold uppercase tracking-widest transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Bagikan
                        </a>
                    </div>
                </div>

                @if($activeVideo->ringkasan)
                    <div class="mt-6 p-6 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Ringkasan Khotbah</p>
                        <p class="text-slate-300 text-sm leading-relaxed">{{ $activeVideo->ringkasan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>