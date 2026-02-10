<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter leading-none italic uppercase">Agenda Lelang</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Pencatatan & Monitoring Dana Lelang Jemaat</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-10 py-5 bg-primary text-white rounded-[24px] font-black text-xs shadow-2xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95 cursor-pointer z-10">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3.5"/></svg>
                TAMBAH AGENDA LELANG
            </button>
        </div>

        <!-- FILTER BAR (USER FRIENDLY) -->
        <div class="bg-white rounded-[40px] p-6 shadow-sm border border-slate-100 mb-10 flex flex-col lg:flex-row gap-6 items-end">
            <!-- Search -->
            <div class="w-full lg:flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-4">Cari Nama Acara</label>
                <div class="relative">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-3xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Contoh: Lelang Pembangunan Tahap 1...">
                </div>
            </div>

            <!-- Date Range -->
            <div class="w-full lg:w-auto">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-4">Rentang Waktu</label>
                <div class="flex items-center bg-slate-50 rounded-3xl p-1 border border-slate-50">
                    <input wire:model.live="startDate" type="date" class="bg-transparent border-none font-bold text-sm text-slate-600 focus:ring-0 px-4 py-3">
                    <span class="text-slate-300 px-2 font-black">/</span>
                    <input wire:model.live="endDate" type="date" class="bg-transparent border-none font-bold text-sm text-slate-600 focus:ring-0 px-4 py-3">
                </div>
            </div>

            <!-- Year Dropdown -->
            <div class="w-full lg:w-48">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-4">Tahun Buku</label>
                <div class="relative">
                    <select wire:model.live="filterYear" class="w-full bg-slate-50 border-none rounded-3xl py-4 pl-6 pr-10 font-black text-sm text-slate-700 appearance-none focus:ring-4 focus:ring-primary/5 cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->id }}">{{ $fy->tahun }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID EVENTS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
            <div class="bg-white rounded-[50px] overflow-hidden shadow-sm border border-slate-200/60 hover:shadow-2xl hover:border-primary/20 transition-all duration-500 group flex flex-col relative">
                
                <!-- Kategori Kas Badge (Floating) -->
                <div class="absolute top-8 right-8 z-10">
                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] shadow-sm border {{ $event->tujuan_kas == 'pembangunan' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-blue-50 text-blue-700 border-blue-100' }}">
                        Target: {{ $event->tujuan_kas }}
                    </span>
                </div>

                <div class="p-10 flex-1 relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-14 w-14 rounded-3xl bg-slate-900 text-white flex flex-col items-center justify-center shadow-xl group-hover:bg-primary transition-colors duration-500">
                            <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($event->tanggal_event)->format('d') }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-tighter">{{ \Carbon\Carbon::parse($event->tanggal_event)->format('M') }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none mb-1">Agenda Acara</p>
                            <p class="text-xs font-bold text-slate-500 italic">{{ \Carbon\Carbon::parse($event->tanggal_event)->isoFormat('dddd, Y') }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4 group-hover:text-primary transition-colors tracking-tighter">{{ $event->nama_event }}</h3>
                    
                    <!-- Mapping Pos -->
                    <div class="flex items-center gap-3 mb-10 bg-slate-50 p-4 rounded-3xl border border-slate-100/50">
                        <div class="bg-white p-2 rounded-xl shadow-sm text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Pos Pelaporan RAPB</p>
                            <p class="text-xs font-bold text-slate-700 truncate mt-1">
                                {{ $event->budgetPost->kode ?? '?' }} - {{ $event->budgetPost->nama ?? 'Umum' }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Finance -->
                    <div class="space-y-5">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Total Piutang (Nota)</span>
                                <span class="text-xl font-black text-slate-900">Rp {{ number_format($event->auctions->sum('harga_jadi'), 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1 italic">Uang Masuk</span>
                                <span class="text-xl font-black text-emerald-600">Rp {{ number_format($event->auctions->sum('total_terbayar_cache'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @php 
                            $total = $event->auctions->sum('harga_jadi');
                            $real = $event->auctions->sum('total_terbayar_cache');
                            $percent = $total > 0 ? ($real / $total) * 100 : 0;
                        @endphp
                        
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div><span class="text-[10px] font-black py-1 px-2 uppercase rounded-full text-slate-500 bg-slate-100">{{ $event->auctions_count }} Barang</span></div>
                                <div class="text-right"><span class="text-xs font-black text-primary">{{ number_format($percent, 1) }}%</span></div>
                            </div>
                            <div class="overflow-hidden h-2.5 text-xs flex rounded-full bg-slate-100 shadow-inner">
                                <div style="width:{{ $percent }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary transition-all duration-1000"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex justify-end items-center relative overflow-hidden group-hover:bg-slate-100/50 transition-colors">
                    <a href="{{ route('auctions.items', $event) }}" class="inline-flex items-center gap-3 text-xs font-black text-slate-900 hover:text-primary transition-all uppercase tracking-[0.2em]">
                        Kelola Barang & Bayar
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center bg-white rounded-[50px] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-400 italic uppercase tracking-widest leading-none">Tidak Ada Acara</h3>
                <p class="text-slate-400 text-sm mt-3 font-medium">Ubah filter atau buat kegiatan baru untuk mulai mencatat.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">{{ $events->links() }}</div>

        <!-- MODAL TAMBAH (PREMIUM FIXED) -->
        @if($isOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="$wire.set('isOpen', false)"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[50px] sm:rounded-[50px] p-10 shadow-2xl transition-all border-b-8 border-primary overflow-hidden">
                    
                    <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                    </div>

                    <h3 class="text-3xl font-black text-slate-900 mb-2 leading-none italic uppercase tracking-tighter">Registrasi Lelang</h3>
                    <p class="text-xs font-bold text-slate-400 mb-10 uppercase tracking-widest border-b border-slate-100 pb-4">Tentukan target kas dan pos pelaporan</p>
                    
                    <form wire:submit="save" class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Kegiatan</label>
                            <input wire:model="nama_event" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 text-lg font-black focus:ring-4 focus:ring-primary/10 placeholder:text-slate-300 shadow-inner" placeholder="Misal: Lelang Natal 2026">
                            @error('nama_event') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-2 uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal</label>
                                <input wire:model="tanggal_event" type="date" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black focus:ring-4 focus:ring-primary/10">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Tujuan Kas</label>
                                <select wire:model.live="tujuan_kas" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black focus:ring-4 focus:ring-primary/10 appearance-none cursor-pointer">
                                    <option value="umum">Kas Umum</option>
                                    <option value="pembangunan">Kas Pembangunan</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-8 rounded-[40px] border border-blue-100 relative">
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-4 ml-1">Kategorisasi RAPB (Penting)</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-white border-none rounded-2xl p-4 font-black text-primary shadow-sm focus:ring-4 focus:ring-primary/10 appearance-none cursor-pointer">
                                <option value="">-- Pilih Pos Pemasukan --</option>
                                @foreach($budgetPosts as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option>
                                @endforeach
                            </select>
                            <p class="mt-4 text-[9px] text-blue-400 font-bold italic leading-relaxed">* Pilih di mana hasil lelang ini akan muncul di Laporan Realisasi RAPB Sinode/Jemaat.</p>
                            @error('ref_budget_post_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-6 flex gap-4">
                            <button type="button" @click="$wire.set('isOpen', false)" class="flex-1 py-6 bg-slate-100 rounded-[30px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-6 bg-slate-900 text-white rounded-[30px] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-slate-200 hover:bg-primary transition transform active:scale-95">
                                <span wire:loading.remove>Simpan Agenda</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>