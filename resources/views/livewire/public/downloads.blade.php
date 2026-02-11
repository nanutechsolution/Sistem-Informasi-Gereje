<div class="min-h-screen bg-slate-50 pb-32">
    <!-- HERO -->
    <section class="relative pt-40 pb-20 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-serif italic mb-6 tracking-tighter animate-in fade-in slide-in-from-bottom-8 duration-700">
                Pustaka <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Digital</span>
            </h1>
            <p class="text-slate-400 font-medium max-w-xl mx-auto uppercase tracking-widest text-xs">Unduh Tata Ibadah, Warta, dan Dokumen Resmi Gereja.</p>
        </div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-blue-900/20 rounded-full blur-[100px] -z-10 pointer-events-none"></div>
    </section>

    <!-- FILTER -->
    <div class="sticky top-24 z-30 px-6 -mt-8">
        <div class="max-w-4xl mx-auto bg-white/80 backdrop-blur-xl border border-white/40 p-2 rounded-full shadow-2xl flex justify-center gap-2 overflow-x-auto no-scrollbar">
            @foreach(['Semua' => '', 'Tata Ibadah' => 'Tata Ibadah', 'Warta' => 'Warta Jemaat', 'Laporan' => 'Laporan Keuangan'] as $label => $val)
            <button wire:click="$set('filterKategori', '{{ $val }}')" 
                    class="px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all {{ $filterKategori == $val ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- GRID -->
    <div class="max-w-6xl mx-auto px-6 mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $doc)
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden">
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-xl font-black {{ $doc->file_icon == 'pdf' ? 'bg-rose-50 text-rose-500' : 'bg-blue-50 text-blue-500' }}">
                    {{ strtoupper($doc->file_icon) }}
                </div>
                <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[9px] font-black uppercase rounded-lg tracking-widest">{{ $doc->kategori }}</span>
            </div>
            
            <h3 class="text-xl font-black text-slate-900 leading-tight mb-2 relative z-10 group-hover:text-primary transition-colors">{{ $doc->judul }}</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">{{ $doc->created_at->format('d M Y') }} • {{ $doc->size }}</p>
            
            <button wire:click="download('{{ $doc->uuid }}')" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg group-hover:bg-primary transition-all relative z-10 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh File
            </button>

            <!-- Deco -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <p class="text-slate-300 font-black uppercase text-xs tracking-[0.2em]">Tidak ada dokumen ditemukan.</p>
        </div>
        @endforelse
    </div>
</div>