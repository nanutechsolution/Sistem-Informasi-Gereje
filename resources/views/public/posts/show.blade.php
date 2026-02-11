<x-layouts.web>
    <!-- SEO Meta & Title -->
    <x-slot:title>{{ $post->judul }} | GKS Reda Pada</x-slot>

    <!-- Reading Progress Bar -->
    <div class="fixed top-0 left-0 w-full h-1.5 z-[150] pointer-events-none">
        <div id="readingProgress" class="h-full bg-primary transition-all duration-150 shadow-[0_0_10px_rgba(var(--primary-rgb),0.5)]" style="width: 0%"></div>
    </div>

    <div class="bg-white min-h-screen">
        <!-- 1. RESPONSIVE HERO SECTION -->
        <div class="relative h-[50vh] md:h-[75vh] w-full overflow-hidden bg-slate-900">
            <img src="{{ asset('storage/' . $post->gambar_fitur) }}"
                class="absolute inset-0 w-full h-full object-cover opacity-70 scale-100 animate-pulse-slow"
                alt="{{ $post->judul }}"
                onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=1920'">

            <!-- Soft Bottom Fade -->
            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-black/20"></div>

            <!-- Enhanced Back Button (Mobile Friendly Position) -->
            <div class="absolute top-20 md:top-28 left-4 md:left-12 z-30">
                <a href="{{ route('public.warta.index') }}" 
                   class="group flex items-center gap-2 px-4 py-3 bg-black/20 backdrop-blur-md border border-white/20 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all duration-500 shadow-xl">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="hidden md:inline">Kembali Ke Warta</span>
                </a>
            </div>
        </div>

        <!-- 2. ARTICLE WRAPPER -->
        <article class="relative z-20 -mt-20 md:-mt-40 max-w-4xl mx-auto px-4 md:px-0 pb-32">
            
            <!-- Floating Header Card -->
            <div class="bg-white p-6 md:p-16 rounded-[2.5rem] md:rounded-[4rem] shadow-2xl shadow-slate-200/80 border border-slate-50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-accent"></div>

                <!-- Category & Date -->
                <div class="flex items-center justify-between mb-6 md:mb-10 px-2">
                    <span class="px-4 py-1.5 rounded-full bg-primary/5 text-primary text-[9px] font-black uppercase tracking-[0.2em] border border-primary/10">
                        {{ $post->kategori }}
                    </span>
                    <time class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        {{ $post->published_at->isoFormat('D MMMM Y') }}
                    </time>
                </div>

                <h1 class="text-3xl md:text-6xl font-serif italic text-slate-900 leading-[1.15] mb-10 tracking-tight text-center md:text-left">
                    {{ $post->judul }}
                </h1>

                <!-- Mobile Adaptive Meta Info -->
                <div class="grid grid-cols-2 md:flex items-center gap-6 pt-10 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-primary font-black text-sm border border-slate-100">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div class="leading-none">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Ditulis Oleh</p>
                            <p class="text-xs font-bold text-slate-800">{{ $post->author->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-l md:border-l-0 md:pl-0 pl-6 border-slate-100">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-accent border border-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                        </div>
                        <div class="leading-none">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Durasi</p>
                            <p class="text-xs font-bold text-slate-800">{{ ceil(str_word_count(strip_tags($post->konten)) / 200) }} Menit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. BODY CONTENT (Optimized Typography) -->
            <div class="mt-12 md:mt-24 prose prose-base md:prose-xl prose-slate mx-auto 
                        prose-p:text-slate-600 prose-p:leading-[1.8] md:prose-p:leading-[2]
                        prose-headings:font-serif prose-headings:italic prose-headings:text-slate-900
                        prose-img:rounded-3xl prose-img:shadow-xl md:prose-img:rounded-[3rem]
                        prose-blockquote:border-l-primary prose-blockquote:bg-slate-50 prose-blockquote:rounded-r-2xl prose-blockquote:font-serif px-2 md:px-0">
                {!! nl2br(e($post->konten)) !!}
            </div>

            <!-- 4. FLOATING SHARE BAR (Mobile UX) -->
            <div class="mt-20 p-6 md:p-10 bg-slate-50 rounded-[2.5rem] border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Warta ini bermanfaat?</p>
                    <p class="text-sm font-bold text-slate-900">Bagikan kepada jemaat lainnya</p>
                </div>
                
                <div class="flex gap-4">
                    <button onclick="window.open('https://facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}', '_blank')" 
                            class="w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </button>
                    <button onclick="window.open('https://wa.me/?text={{ urlencode($post->judul . ' - ' . url()->current()) }}', '_blank')" 
                            class="w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </button>
                    <button onclick="copyToClipboard('{{ url()->current() }}')" 
                            class="w-14 h-14 rounded-full bg-slate-900 shadow-lg flex items-center justify-center text-white hover:bg-primary transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-link text-sm"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- 5. RELATED READS (Clean Mobile Grid) -->
        @if($relatedPosts->count() > 0)
        <section class="bg-white py-24 border-t border-slate-100 overflow-hidden relative">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center md:text-left mb-16">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.4em]">Saran Bacaan</span>
                    <h3 class="text-3xl md:text-5xl font-serif italic text-slate-900 tracking-tight mt-2">Warta Terkait</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('public.warta.show', $related->slug) }}" class="group flex gap-4 md:gap-8 bg-slate-50/50 p-4 md:p-6 rounded-[2rem] md:rounded-[3rem] border border-transparent hover:border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="w-24 h-24 md:w-40 md:h-40 rounded-2xl md:rounded-[2rem] overflow-hidden shrink-0 shadow-md">
                            <img src="{{ asset('storage/' . $related->gambar_fitur) }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                 onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=400'">
                        </div>
                        <div class="flex flex-col justify-center flex-1 pr-2">
                            <p class="text-[8px] font-black text-accent uppercase tracking-[0.2em] mb-2">{{ $related->kategori }}</p>
                            <h4 class="text-lg md:text-2xl font-serif italic text-slate-900 group-hover:text-primary transition-colors leading-tight mb-3">
                                {{ $related->judul }}
                            </h4>
                            <div class="flex items-center gap-2 text-[9px] font-black text-slate-300 uppercase tracking-widest transition-all group-hover:text-slate-500">
                                Selengkapnya
                                <svg class="w-3 h-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5-5 5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div>

    <!-- Mobile-First Interactions Script -->
    <script>
        // Reading Progress Logic
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById("readingProgress").style.width = scrolled + "%";
        });

        // Clipboard Helper
        function copyToClipboard(text) {
            const input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            
            // Custom modern alert logic can be added here
            alert('Tautan warta berhasil disalin!');
        }
    </script>
</x-layouts.web>