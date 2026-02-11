<x-layouts.web>
    <x-slot:title>Arsip Warta | GKS Jemaat Reda Pada</x-slot>

    <!-- HEADER / HERO -->
    <section class="relative pt-40 pb-20 px-6 lg:px-10 overflow-hidden bg-slate-50">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-3 px-5 py-2 bg-blue-50/50 backdrop-blur-sm rounded-full border border-blue-100 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.3em]">Bulletin Archive</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic text-slate-900 mb-8 tracking-tighter leading-[0.9] animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                Kabar <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">Sukacita</span>
            </h1>
            
            <p class="text-slate-500 text-sm md:text-base font-medium max-w-2xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                Kumpulan warta jemaat, renungan harian, dan berita kegiatan pelayanan GKS Jemaat Reda Pada yang terdokumentasi.
            </p>
        </div>

        <!-- Background Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
    </section>

    <!-- CATEGORY NAVBAR (RESPONSIVE & STICKY) -->
    <div class="sticky top-20 z-40 bg-white/80 backdrop-blur-xl border-y border-slate-200/60 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-16">
                <!-- Label Mobile -->
                <span class="md:hidden text-[10px] font-black uppercase tracking-widest text-slate-400 shrink-0 mr-4">Filter:</span>
                
                <!-- Scrollable Menu -->
                <nav class="flex gap-3 overflow-x-auto no-scrollbar w-full md:w-auto md:mx-auto py-2 items-center">
                    <a href="{{ route('public.warta.index') }}" 
                       class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all {{ !request('kategori') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    
                    @foreach(['Berita', 'Renungan', 'Pengumuman', 'Diakonia'] as $cat)
                    <a href="{{ route('public.warta.index', ['kategori' => $cat]) }}" 
                       class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all {{ request('kategori') == $cat ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT GRID -->
    <section class="py-20 px-6 lg:px-10 min-h-screen bg-white">
        <div class="max-w-7xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                @forelse($posts as $post)
                    <a href="{{ route('public.warta.show', $post->slug) }}" class="group flex flex-col h-full">
                        <!-- Image Card -->
                        <div class="relative w-full aspect-[4/3] rounded-[2.5rem] overflow-hidden mb-8 shadow-2xl shadow-slate-100 border border-slate-100">
                            <img src="{{ asset('storage/' . $post->gambar_fitur) }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                 alt="{{ $post->judul }}"
                                 onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=800'">
                            
                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-colors duration-500"></div>

                            <!-- Category Badge -->
                            <div class="absolute top-6 left-6">
                                <span class="px-5 py-2 bg-white/90 backdrop-blur-md text-primary text-[9px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                    {{ $post->kategori }}
                                </span>
                            </div>
                        </div>

                        <!-- Text Content -->
                        <div class="flex-1 flex flex-col px-2">
                            <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                                <span>{{ $post->published_at->format('d M Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span>{{ $post->author->name }}</span>
                            </div>
                            
                            <h2 class="text-3xl font-serif italic text-slate-900 leading-[1.1] mb-4 group-hover:text-primary transition-colors tracking-tight">
                                {{ $post->judul }}
                            </h2>
                            
                            <p class="text-sm text-slate-500 line-clamp-3 leading-loose mb-6 font-medium">
                                {{ Str::limit(strip_tags($post->konten), 120) }}
                            </p>
                            
                            <div class="mt-auto flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-primary group-hover:gap-4 transition-all">
                                Baca Selengkapnya <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-40 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-6 text-slate-300 shadow-sm border border-slate-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest italic mb-2">Data Kosong</h3>
                        <p class="text-slate-400 font-medium text-sm">Belum ada warta yang dipublikasikan pada kategori ini.</p>
                        <a href="{{ route('public.warta.index') }}" class="inline-block mt-6 text-primary font-bold text-xs uppercase tracking-widest hover:underline">Lihat Semua Warta</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-24">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

</x-layouts.web>