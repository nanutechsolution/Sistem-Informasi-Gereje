<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Agenda Pelayanan</h1>
                <p class="text-slate-500 mt-3 font-medium">Manajemen Ibadah Minggu, Rapat, dan Kegiatan Umum (Non-PKS).</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('schedules.pks') }}" class="inline-flex items-center justify-center px-6 py-4 bg-white border border-slate-200 text-slate-700 rounded-[24px] font-black text-xs shadow-sm hover:bg-slate-50 transition-all uppercase tracking-widest">
                    Ke Jadwal PKS &rarr;
                </a>
                
                <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all cursor-pointer z-10">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    JADWAL UMUM BARU
                </button>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari tema, lokasi, atau kegiatan...">
            </div>
            
            <!-- Filter Jenis Kegiatan -->
            <div class="w-full md:w-64">
                <select wire:model.live="filterType" class="w-full bg-slate-50 border-none rounded-2xl p-3 font-bold text-sm focus:ring-4 focus:ring-primary/5 cursor-pointer appearance-none">
                    <option value="">Semua Kegiatan Umum</option>
                    @foreach($types as $t) 
                        <option value="{{ $t->id }}">{{ $t->nama }}</option> 
                    @endforeach
                </select>
            </div>
        </div>

        <!-- GRID JADWAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($schedules as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm hover:shadow-2xl transition-all group flex flex-col relative overflow-hidden h-full">
                <!-- Header Card -->
                <div class="flex justify-between items-start mb-6 z-10 relative">
                    <span class="px-3 py-1 bg-blue-50 text-primary text-[10px] font-black uppercase rounded-full border border-blue-100 tracking-widest shadow-sm">
                        {{ $item->type->nama }}
                    </span>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $item->id }})" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-primary transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus jadwal ini?" class="p-2 bg-white rounded-xl shadow-sm text-slate-300 hover:text-rose-500 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>

                <!-- Info Waktu -->
                <div class="mb-4 z-10 relative">
                    <p class="text-xs font-black text-slate-800 uppercase tracking-tighter flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $item->tanggal->isoFormat('dddd, D MMM Y') }}
                    </p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1 pl-6">Pukul {{ $item->jam_mulai->format('H:i') }} WITA</p>
                </div>

                <!-- Tema & Lokasi -->
                <h3 class="text-xl font-black text-slate-900 leading-tight mb-2 flex-1 italic">{{ $item->tema ?? 'Agenda Rutin' }}</h3>
                
                <div class="flex items-center gap-2 text-sm font-bold text-slate-500 mb-8 bg-slate-50 p-3 rounded-2xl">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="truncate">{{ $item->lokasi_display }}</span>
                </div>

                <!-- Footer Action -->
                <div class="mt-auto pt-6 border-t border-slate-50 flex justify-between items-center z-10 relative">
                    <div class="flex -space-x-2">
                        @foreach($item->servants->take(3) as $servant)
                            <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-primary uppercase shadow-sm" title="{{ $servant->member->nama }}">
                                {{ substr($servant->member->nama, 0, 1) }}
                            </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('schedules.servants', $item) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-white bg-slate-900 hover:bg-primary px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-slate-200">
                        KELOLA TIM &rarr;
                    </a>
                </div>
                
                <!-- Dekorasi -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-slate-50 rounded-full -translate-y-1/2 translate-x-1/2 z-0 group-hover:bg-blue-50/50 transition-colors"></div>
            </div>
            @endforeach
        </div>

        <div class="mt-12">{{ $schedules->links() }}</div>
    </div>

    <!-- MODAL FORM -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showModal = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[40px] p-10 text-left shadow-2xl transition-all overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
                
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">{{ $editId ? 'Edit' : 'Input' }} Agenda Umum</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Kegiatan</label>
                            <select wire:model="ref_activity_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none focus:ring-4 focus:ring-primary/10 cursor-pointer">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($types as $t) 
                                    <option value="{{ $t->id }}">{{ $t->nama }}</option> 
                                @endforeach
                            </select>
                            <p class="mt-1 text-[9px] text-slate-400 italic">* Khusus agenda non-PKS.</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah (Opsional)</label>
                            <select wire:model="ref_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none focus:ring-4 focus:ring-primary/10 cursor-pointer">
                                <option value="">Semua Wilayah / Pusat</option>
                                @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                         <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lokasi (Opsional)</label>
                         <input wire:model="lokasi_manual" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10" placeholder="Contoh: Gedung Gereja / Aula...">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jam</label>
                            <input wire:model="jam_mulai" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10 cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tema / Topik</label>
                        <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10" placeholder="Judul khotbah atau agenda...">
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-5 bg-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-blue-500/30 hover:bg-blue-800 transition transform active:scale-95">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>