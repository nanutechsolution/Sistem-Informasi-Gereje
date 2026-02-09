<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isOpen') }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">Master Pos Anggaran</h1>
                <p class="text-slate-500 mt-3 font-medium">Susun hirarki pendapatan dan belanja (Level 1 s/d 3).</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-6 py-3.5 bg-primary text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-500/30 hover:scale-[1.02] transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                TAMBAH POS BARU
            </button>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 mb-8">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all" placeholder="Cari kode atau nama pos...">
            </div>
        </div>

        <!-- LIST DATA (3-LEVEL HIERARCHICAL) -->
        <div class="space-y-8">
            @forelse($posts as $p1)
            <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
                <!-- Level 1: Group Header (Induk Utama) -->
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center {{ $p1->jenis == 'pemasukan' ? 'bg-emerald-50/50' : 'bg-rose-50/50' }}">
                    <div class="flex items-center gap-4">
                        <span class="font-mono font-black text-xs px-3 py-1.5 rounded-xl {{ $p1->jenis == 'pemasukan' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                            {{ $p1->kode }}
                        </span>
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wide">{{ $p1->nama }}</h2>
                    </div>
                    <button wire:click="edit({{ $p1->id }})" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-primary transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                </div>

                <!-- Level 2 & 3 -->
                <div class="divide-y divide-slate-50">
                    @foreach($p1->children as $p2)
                    <div class="bg-white group/l2">
                        <!-- Level 2 Row -->
                        <div class="px-8 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                                <div>
                                    <span class="font-mono text-[10px] font-black text-slate-400 block mb-0.5">{{ $p2->kode }}</span>
                                    <h4 class="font-black text-slate-800 text-base">{{ $p2->nama }}</h4>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 opacity-100 sm:opacity-0 group-hover/l2:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $p2->id }})" class="p-2 text-slate-400 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                <button wire:click="delete({{ $p2->id }})" wire:confirm="Hapus pos ini?" class="p-2 text-slate-300 hover:text-rose-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>

                        <!-- Level 3 (Sub-Pos) -->
                        @if($p2->children->count() > 0)
                        <div class="bg-slate-50/30 pb-4">
                            @foreach($p2->children as $p3)
                            <div class="ml-16 mr-8 my-2 p-3 bg-white border border-slate-100 rounded-2xl flex items-center justify-between shadow-sm group/l3">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-px bg-slate-200"></div>
                                    <div>
                                        <span class="font-mono text-[9px] font-bold text-slate-400">{{ $p3->kode }}</span>
                                        <p class="text-sm font-bold text-slate-600">{{ $p3->nama }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 opacity-100 sm:opacity-0 group-hover/l3:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $p3->id }})" class="p-1.5 text-slate-300 hover:text-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                    <button wire:click="delete({{ $p3->id }})" wire:confirm="Hapus sub-pos ini?" class="p-1.5 text-slate-200 hover:text-rose-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="py-20 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 italic uppercase tracking-widest">Belum ada struktur anggaran</h3>
                <p class="text-slate-400 text-sm mt-2">Susun hirarki mulai dari Induk (Level 1).</p>
            </div>
            @endforelse
        </div>

        <!-- MODAL FORM (UNIVERSAL FOR ALL LEVELS) -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl transition-all">
                    <div class="absolute top-0 left-0 h-1.5 w-full bg-slate-100">
                        <div class="h-full bg-primary w-full animate-pulse"></div>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mb-2 leading-none italic">{{ $postId ? 'Ubah' : 'Buat' }} Pos Anggaran</h3>
                    <p class="text-sm text-slate-400 mb-8 font-medium">Gunakan hirarki untuk audit 20 tahun yang presisi.</p>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kode Anggaran (KUA)</label>
                                <input wire:model="kode" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold text-primary focus:ring-2 focus:ring-primary/20" placeholder="Contoh: 2.1.1">
                                @error('kode') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Pos</label>
                                <select wire:model="jenis" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none focus:ring-2 focus:ring-primary/20">
                                    <option value="pengeluaran">BELANJA (Keluar)</option>
                                    <option value="pemasukan">PENDAPATAN (Masuk)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pos / Uraian</label>
                            <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Pemeliharaan Pengerja">
                            @error('nama') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Induk Kategori (Pilih untuk Sub-Pos)</label>
                            <select wire:model="parent_id" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-bold text-primary appearance-none focus:ring-2 focus:ring-primary/20">
                                <option value="">-- JADIKAN INDUK (Level 1) --</option>
                                @foreach($allOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->kode }} - {{ $opt->nama }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-[10px] text-orange-500 font-bold italic">* Contoh: Jika ingin membuat "Gaji Pendeta", pilih Induk "Pemeliharaan Pengerja".</p>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="openModal = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-500 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-500/30 hover:bg-blue-800 transition transform active:scale-95">
                                <span wire:loading.remove>SIMPAN STRUKTUR</span>
                                <span wire:loading>PROSES...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>