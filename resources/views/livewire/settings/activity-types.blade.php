<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isOpen').live }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">Master Jenis Kegiatan</h1>
                <p class="text-slate-500 mt-3 font-medium">Atur kategori agenda (Ibadah, Rapat, PKS) dan warna labelnya.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                TAMBAH JENIS
            </button>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 mb-8">
            <div class="relative group">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all" placeholder="Cari nama kegiatan...">
            </div>
        </div>

        <!-- GRID DATA -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($types as $type)
            <div class="bg-white rounded-[40px] p-6 border border-slate-200/60 shadow-sm hover:shadow-xl transition-all group flex items-center justify-between relative overflow-hidden">
                <!-- Color Strip -->
                <div class="absolute left-0 top-0 bottom-0 w-3" style="background-color: {{ $type->warna_label }}"></div>

                <div class="pl-4 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-sm" style="background-color: {{ $type->warna_label }}">
                        {{ substr($type->nama, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-lg leading-none">{{ $type->nama }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                            {{ $type->schedules()->count() }} Jadwal Terkait
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button wire:click="edit({{ $type->id }})" class="p-3 bg-slate-50 text-slate-400 hover:text-primary rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                    <button wire:click="delete({{ $type->id }})" wire:confirm="Hapus jenis kegiatan ini?" class="p-3 bg-slate-50 text-slate-300 hover:text-rose-500 rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 py-20 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Belum ada jenis kegiatan.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $types->links() }}</div>

        <!-- MODAL FORM -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
                <div class="relative w-full max-w-md bg-white rounded-t-[40px] sm:rounded-[40px] p-8 text-left shadow-2xl transition-all">
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-2 italic">{{ $editId ? 'Ubah' : 'Buat' }} Kategori</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">Pengaturan Label & Warna Jadwal</p>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Kegiatan</label>
                            <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Misal: Ibadah Padang">
                            @error('nama') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Warna Label</label>
                            <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                <input wire:model="warna_label" type="color" class="h-12 w-12 rounded-xl border-none cursor-pointer bg-transparent p-0">
                                <span class="text-xs font-mono font-bold text-slate-500 uppercase">{{ $warna_label }}</span>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="openModal = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-500 hover:bg-slate-200">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-500/30 hover:bg-blue-800">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>