<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showModal: @entangle('isModalOpen').live, showBatch: @entangle('isBatchModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">Jadwal PKS Wilayah</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4">Perencanaan Ibadah Rumah Tangga & Tim Pelayan.</p>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <!-- Tombol Generator -->
                <button wire:click="openBatchModal" class="flex-1 md:flex-none px-6 py-4 bg-emerald-600 text-white rounded-[24px] font-black text-xs shadow-xl shadow-emerald-500/20 hover:scale-105 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    GENERATOR OTOMATIS
                </button>
                
                <!-- Tombol Manual -->
                <button wire:click="create" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-xl hover:scale-105 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    INPUT MANUAL
                </button>
            </div>
        </div>

        <!-- GRID JADWAL (CARD STYLE) - SAMA SEPERTI SEBELUMNYA -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($schedules as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm hover:shadow-xl transition-all group flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full translate-x-10 -translate-y-10 group-hover:bg-blue-50/50 transition-colors"></div>
                
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <span class="px-4 py-1.5 bg-blue-50 text-primary text-[10px] font-black uppercase rounded-full border border-blue-100 italic">
                        {{ $item->tanggal->isoFormat('dddd, D MMM') }}
                    </span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-lg">
                        {{ $item->family->refWilayah->nama ?? 'Wilayah -' }}
                    </span>
                </div>

                <div class="mb-4 relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 uppercase italic leading-tight line-clamp-2">
                        {{ $item->family->kepala_keluarga ?? ($item->lokasi_manual ?? 'Lokasi Belum Set') }}
                    </h3>
                    <div class="flex items-center gap-2 mt-2">
                         <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Pkl {{ $item->jam_mulai->format('H:i') }}
                         </span>
                    </div>
                </div>

                <div class="mt-auto space-y-4 pt-6 border-t border-slate-50 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-[10px] font-black shadow-lg">PF</div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pelayan Firman</p>
                            <p class="text-sm font-bold text-slate-800 truncate">
                                {{ $item->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Belum Ditunjuk' }}
                            </p>
                        </div>
                    </div>
                    
                    <a href="{{ route('schedules.servants', $item) }}" class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl group/btn hover:bg-primary transition-colors">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover/btn:text-white pl-2">Kelola Tim & Kolekte</span>
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-slate-900 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12">{{ $schedules->links() }}</div>

        <!-- MODAL 1: GENERATOR OTOMATIS (BARU) -->
        <div x-show="showBatch" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-emerald-900/90 backdrop-blur-md" @click="showBatch = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[50px] sm:rounded-[50px] p-10 shadow-2xl transition-all">
                    
                    <div class="mb-8">
                        <h2 class="text-3xl font-black italic uppercase tracking-tighter mb-2 text-emerald-800 leading-none">Generator Jadwal</h2>
                        <p class="text-xs font-bold text-emerald-600/60 uppercase tracking-widest">Membuat jadwal rotasi otomatis untuk satu wilayah.</p>
                    </div>
                    
                    <form wire:submit="generateBatch" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">1. Pilih Wilayah Target</label>
                            <select wire:model="batch_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-emerald-500/20">
                                <option value="">-- Pilih Wilayah --</option>
                                @foreach($allWilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">2. Pilih Kelompok Pelayan</label>
                            <select wire:model="batch_group_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-emerald-500/20">
                                <option value="">-- Pilih Tim Bertugas --</option>
                                @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                            </select>
                            <p class="text-[9px] text-slate-400 mt-2 ml-1 italic">* Tim ini akan ditugaskan ke semua keluarga di wilayah tersebut secara berurutan.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Mulai Tanggal</label>
                                <input wire:model="batch_start_date" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-emerald-500/20">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Jam Ibadah</label>
                                <input wire:model="batch_time" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-emerald-500/20">
                            </div>
                        </div>

                        <div class="flex gap-4 pt-6">
                            <button type="button" @click="showBatch = false" class="flex-1 py-5 bg-slate-100 rounded-[30px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-emerald-600 text-white rounded-[30px] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-emerald-500/40 hover:bg-emerald-700 transition transform active:scale-95">
                                Generate Jadwal Otomatis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL 2: INPUT MANUAL (Sama seperti sebelumnya, disembunyikan untuk ringkas) -->
        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="isModalOpen = false"></div>
            <!-- ... (Isi modal manual sama persis dengan versi sebelumnya) ... -->
             <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-3xl bg-white rounded-t-[50px] sm:rounded-[50px] p-10 shadow-2xl transition-all">
                    <h2 class="text-3xl font-black italic uppercase tracking-tighter mb-2 text-slate-900 leading-none">Input Manual</h2>
                    
                    <form wire:submit="save" class="space-y-8">
                        <!-- ... Form Manual (KK, Kelompok, Tanggal) ... -->
                        <!-- Bagian ini sudah ada di kode sebelumnya -->
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- PENCARIAN KK -->
                            <div class="relative group" x-data="{ searchOpen: false }">
                                <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2 ml-1">Cari Keluarga (Tuan Rumah)</label>
                                @if($selectedFamilyLabel)
                                    <div class="p-4 bg-blue-50 border-2 border-blue-100 rounded-2xl flex justify-between items-center animate-in zoom-in-95">
                                        <span class="font-black text-slate-800 text-sm truncate mr-2">{{ $selectedFamilyLabel }}</span>
                                        <button type="button" wire:click="$set('selectedFamilyLabel', null)" class="text-[10px] font-black text-primary uppercase underline hover:text-blue-800 shrink-0">Ganti</button>
                                    </div>
                                @else
                                    <input wire:model.live.debounce.300ms="searchFamily" @focus="searchOpen = true" x-on:click.away="searchOpen = false" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300" placeholder="Ketik nama kepala keluarga...">
                                    @if(count($foundFamilies) > 0)
                                    <div x-show="searchOpen" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50 max-h-60 overflow-y-auto">
                                        @foreach($foundFamilies as $f)
                                        <button type="button" wire:click="selectFamily({{ $f['id'] }}, '{{ $f['kepala_keluarga'] }}', '{{ $f['nomor_kk'] }}')" @click="searchOpen = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors group">
                                            <p class="font-black text-slate-800 text-sm group-hover:text-primary">{{ $f['kepala_keluarga'] }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">KK: {{ $f['nomor_kk'] }}</p>
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                @endif
                                @error('family_id') <p class="text-rose-500 text-[10px] font-bold uppercase mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Pilihan Kelompok -->
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Gunakan Template Tim</label>
                                <select wire:model.live="service_group_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10 appearance-none cursor-pointer">
                                    <option value="">-- Pilih Kelompok Majelis --</option>
                                    @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Tanggal</label>
                                    <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-700 focus:ring-primary/10">
                                </div>
                                <div class="w-1/3">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Jam</label>
                                    <input wire:model="jam_mulai" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-700 focus:ring-primary/10">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pembaca Firman (PF)</label>
                                <select wire:model="selected_pf_id" class="w-full bg-slate-900 text-white border-none rounded-2xl p-5 font-bold focus:ring-4 focus:ring-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- Pilih Pendeta/Majelis --</option>
                                    @foreach($staffList as $staff) <option value="{{ $staff->member_id }}">{{ $staff->member->nama }} ({{ $staff->position->nama }})</option> @endforeach
                                </select>
                                @error('selected_pf_id') <p class="text-rose-500 text-[10px] font-bold uppercase mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                         <!-- INPUT TEMA -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 sm:mb-3 ml-1">Tema / Pokok Doa</label>
                            <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 sm:p-5 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10" placeholder="Contoh: Firman Tuhan...">
                        </div>

                         <!-- TIM PENDAMPING -->
                        <div class="bg-slate-50 rounded-[32px] p-6 border border-slate-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Tim Pendamping (Checklist)</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($staffList as $staff)
                                <label class="flex items-center p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-primary hover:bg-blue-50/50 transition-all group">
                                    <input type="checkbox" wire:model="selected_pendamping_ids" value="{{ $staff->member_id }}" class="h-4 w-4 text-primary rounded border-slate-300 focus:ring-primary">
                                    <div class="ml-3 min-w-0">
                                        <p class="text-[11px] font-bold text-slate-700 truncate group-hover:text-primary leading-none">{{ $staff->member->nama }}</p>
                                        <p class="text-[8px] font-black text-slate-400 uppercase mt-1 tracking-tighter">{{ $staff->position->nama }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-slate-100">
                            <button type="button" @click="$set('isModalOpen', false)" class="flex-1 py-5 bg-white border-2 border-slate-100 rounded-[28px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">Simpan & Validasi Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>