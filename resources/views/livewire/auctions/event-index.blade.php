<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">Manajemen Lelang</h1>
                <p class="text-slate-500 mt-3 font-medium">Monitoring perolehan lelang jemaat berdasarkan Pos RAPB.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                BUAT KEGIATAN BARU
            </button>
        </div>

        <!-- Toolbar -->
        <div class="mb-10 bg-white rounded-3xl p-3 shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-primary/10" placeholder="Cari nama kegiatan lelang...">
            </div>
        </div>

        <!-- Grid Events -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div class="bg-white rounded-[40px] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl hover:border-primary/20 transition-all duration-500 group flex flex-col relative">
                <!-- Status Badge Overlay -->
                <div class="absolute top-6 right-6 z-10">
                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ $event->tujuan_kas == 'pembangunan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $event->tujuan_kas }}
                    </span>
                </div>

                <div class="p-8 flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black">
                            {{ $event->tanggal_event->format('d') }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ $event->tanggal_event->isoFormat('MMMM Y') }}</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 italic">Tahun Buku {{ $event->fiscalYear->tahun }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4 group-hover:text-primary transition-colors">{{ $event->nama_event }}</h3>
                    
                    <!-- KUA Info -->
                    <div class="flex items-center gap-2 mb-8 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <div class="bg-white p-1.5 rounded-lg shadow-sm">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter leading-none">Mapping Pos Anggaran (KUA)</p>
                            <p class="text-[11px] font-bold text-slate-700 truncate mt-1">
                                {{ $event->budgetPost->kode ?? '1.11' }} - {{ $event->budgetPost->nama ?? 'Lelang Umum' }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Stats -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Piutang</span>
                                <span class="text-xl font-black text-slate-900 italic">Rp {{ number_format($event->auctions->sum('harga_jadi'), 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Realisasi</span>
                                <span class="text-xl font-black text-emerald-600">Rp {{ number_format($event->auctions->sum('total_terbayar_cache'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <!-- Progress Bar Visual -->
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            @php 
                                $total = $event->auctions->sum('harga_jadi');
                                $real = $event->auctions->sum('total_terbayar_cache');
                                $percent = $total > 0 ? ($real / $total) * 100 : 0;
                            @endphp
                            <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 text-right uppercase">{{ number_format($percent, 1) }}% Terkumpul</p>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $event->auctions_count }} Barang Tercatat</span>
                    <a href="{{ route('auctions.items', $event) }}" class="inline-flex items-center gap-2 text-xs font-black text-primary hover:gap-3 transition-all uppercase tracking-widest">
                        Detail & Bayar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $events->links() }}
        </div>

        <!-- MODAL TAMBAH EVENT (PREMIUM DESIGN) -->
        <div x-show="$wire.isOpen" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="$wire.set('isOpen', false)"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl transition-all">
                    <h3 class="text-3xl font-black text-slate-900 mb-2 leading-none italic">Kegiatan Lelang Baru</h3>
                    <p class="text-xs font-bold text-slate-400 mb-8 uppercase tracking-widest">Registrasi Agenda Keuangan Jemaat</p>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Kegiatan / Agenda</label>
                            <input wire:model="nama_event" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 text-lg font-black focus:ring-2 focus:ring-primary/20 placeholder:text-slate-300" placeholder="Lelang Natal / Pembangunan...">
                            @error('nama_event') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Acara</label>
                                <input wire:model="tanggal_event" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Kategori Kas</label>
                                <select wire:model.live="tujuan_kas" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black focus:ring-2 focus:ring-primary/20 appearance-none">
                                    <option value="umum">Kas Umum</option>
                                    <option value="pembangunan">Kas Pembangunan</option>
                                </select>
                            </div>
                        </div>

                        <!-- BARU: Mapping Pos Anggaran Default untuk Event -->
                        <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-3 ml-1">Default Pos Anggaran (KUA)</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-white border-none rounded-2xl p-4 font-black text-primary focus:ring-2 focus:ring-primary/10 shadow-sm appearance-none">
                                <option value="">-- Pilih Pos Pemasukan --</option>
                                @foreach($budgetPosts as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-[9px] text-blue-400 font-bold italic">* Menentukan di mana hasil lelang ini akan tercatat dalam laporan realisasi RAPB.</p>
                            @error('ref_budget_post_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="$wire.set('isOpen', false)" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-xs uppercase tracking-widest text-slate-400">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-3xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">Simpan Agenda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>