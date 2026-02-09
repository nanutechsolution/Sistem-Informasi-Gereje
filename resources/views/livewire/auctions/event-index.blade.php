<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Lelang</h1>
                <p class="text-slate-500 mt-1">Daftar kegiatan lelang jemaat per periode.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:scale-105 transition-transform active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Buat Kegiatan Baru
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative group max-w-md">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live="search" type="text" class="w-full pl-12 pr-4 py-3.5 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-primary/20 font-medium" placeholder="Cari nama kegiatan lelang...">
            </div>
        </div>

        <!-- Grid Events -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:border-primary/20 transition-all duration-300 group flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $event->tujuan_kas == 'pembangunan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            Kas {{ $event->tujuan_kas }}
                        </span>
                        <span class="text-[11px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($event->tanggal_event)->isoFormat('D MMM Y') }}</span>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 leading-tight mb-2 group-hover:text-primary transition-colors">{{ $event->nama_event }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-6">{{ $event->keterangan ?? 'Tidak ada deskripsi tambahan.' }}</p>

                    <!-- Statistik Singkat -->
                    <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-50">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Total Barang</span>
                            <span class="text-lg font-black text-slate-700">{{ $event->auctions_count }} Item</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Total Nilai</span>
                            <span class="text-lg font-black text-slate-700">Rp {{ number_format($event->auctions->sum('harga_jadi'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    <!-- LINK UPDATED: Mengarah ke Item Manager -->
                    <a href="{{ route('auctions.items', $event) }}" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl flex items-center justify-center gap-2 text-sm font-bold text-slate-700 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                        Kelola Barang & Bayar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>

        <!-- MODAL TAMBAH EVENT -->
        @if($isOpen)
        <div class="fixed inset-0 z-[60] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="$wire.set('isOpen', false)"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl transition-all">
                    <h3 class="text-2xl font-black text-slate-900 mb-6">Kegiatan Lelang Baru</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Kegiatan</label>
                            <input wire:model="nama_event" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-lg font-bold focus:ring-2 focus:ring-primary/20" placeholder="Misal: Lelang Natal 2026">
                            @error('nama_event') <span class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Acara</label>
                                <input wire:model="tanggal_event" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Tujuan Kas</label>
                                <select wire:model="tujuan_kas" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20 appearance-none">
                                    <option value="umum">Kas Umum</option>
                                    <option value="pembangunan">Kas Pembangunan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Keterangan (Opsional)</label>
                            <textarea wire:model="keterangan" rows="3" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-medium focus:ring-2 focus:ring-primary/20" placeholder="Catatan tambahan kegiatan..."></textarea>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="$wire.set('isOpen', false)" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-500">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-primary text-white rounded-2xl font-bold shadow-xl shadow-blue-500/30">Simpan Kegiatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>