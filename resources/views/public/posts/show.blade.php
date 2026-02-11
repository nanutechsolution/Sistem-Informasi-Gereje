<x-layouts.web>
    <!-- SEO Meta & Title (Optional) -->
    <x-slot:title>{{ $post->judul }} | GKS Reda Pada</x-slot>

        <!-- 1. IMMERSIVE HERO IMAGE -->
        <div class="relative h-[60vh] w-full overflow-hidden">
            <img src="{{ asset('storage/' . $post->gambar_fitur) }}"
                class="absolute inset-0 w-full h-full object-cover"
                alt="{{ $post->judul }}"
                onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=1920'">

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-slate-900/40 to-slate-900/60"></div>

            <!-- Back Button -->
            <div class="absolute top-24 left-6 lg:left-10 z-20">
                <a href="{{ route('public.warta.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- 2. ARTICLE CONTENT CONTAINER -->
        <article class="relative z-10 -mt-32 max-w-4xl mx-auto px-6">

            <!-- Title Header Card -->
            <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-slate-100 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>

                <!-- Category Badge -->
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-6">
                    {{ $post->kategori }}
                </span>

                <h1 class="text-3xl md:text-5xl font-serif italic text-slate-900 leading-tight mb-8">
                    {{ $post->judul }}
                </h1>

                <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-xs text-slate-500 font-bold uppercase tracking-widest border-t border-slate-100 pt-8">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $post->published_at->isoFormat('dddd, D MMMM Y') }}
                    </div>
                    <div class="hidden md:block w-1 h-1 bg-slate-300 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[9px] text-slate-500">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        Penulis: {{ $post->author->name }}
                    </div>
                </div>
            </div>

            <!-- 3. MAIN TEXT CONTENT -->
            <div class="mt-16 mb-24 prose prose-lg prose-slate mx-auto prose-headings:font-serif prose-headings:italic prose-headings:text-primary prose-p:leading-loose prose-a:text-accent prose-img:rounded-[2rem] prose-img:shadow-xl">
                {!! nl2br(e($post->konten)) !!}
            </div>

            <!-- 4. SHARE & ACTIONS -->
            <div class="border-t border-slate-200 pt-10 pb-20 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bagikan Warta Ini:</p>
                <div class="flex gap-4">
                    <button class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                        <span class="font-black text-xs">FB</span>
                    </button>
                    <button class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                        <span class="font-black text-xs">WA</span>
                    </button>
                    <button class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                        <span class="font-black text-xs">TW</span>
                    </button>
                </div>
            </div>

        </article>

        <!-- 5. RELATED POSTS (BACA JUGA) -->
        <section class="bg-slate-50 py-24 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h3 class="text-3xl font-serif italic text-slate-900">Warta Lainnya</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Tetap Terhubung dengan Informasi Gereja</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('public.warta.show', $related->slug) }}" class="group flex flex-col md:flex-row gap-6 items-center bg-white p-4 rounded-[2.5rem] border border-slate-100 hover:shadow-xl transition-all duration-500">
                        <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0">
                            <img src="{{ asset('storage/' . $related->gambar_fitur) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="flex-1 text-center md:text-left pr-4">
                            <span class="text-[9px] font-black text-accent uppercase tracking-widest">{{ $related->kategori }}</span>
                            <h4 class="text-xl font-black text-slate-900 italic mt-2 mb-2 leading-tight group-hover:text-primary transition-colors">{{ $related->judul }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">{{ $related->published_at->format('d M Y') }}</p>
                        </div>
                        <div class="hidden md:flex w-12 h-12 rounded-full border-2 border-slate-100 items-center justify-center text-slate-300 group-hover:border-primary group-hover:text-primary transition-all mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

</x-layouts.web>