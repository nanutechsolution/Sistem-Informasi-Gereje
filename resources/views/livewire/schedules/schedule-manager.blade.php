<div class="py-4 sm:py-10 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none">Agenda Jemaat</h1>
                <p class="text-slate-500 mt-1 text-xs sm:text-sm font-medium uppercase tracking-[0.1em] border-l-4 border-primary pl-3">
                    Manajemen Kegiatan & Ibadah Umum
                </p>
            </div>
            <button wire:click="create" wire:loading.attr="disabled" wire:target="create" class="w-full md:w-auto px-6 py-3 sm:py-4 bg-slate-900 text-white rounded-2xl sm:rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-primary transition-all flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="create" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Agenda
                </span>
                <span wire:loading wire:target="create">Memproses...</span>
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-[24px] sm:rounded-[40px] p-4 sm:p-6 shadow-sm border border-slate-100 mb-6 sm:mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="relative">
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Cari Kegiatan</label>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl py-3 sm:py-4 pl-10 pr-4 font-bold text-sm focus:ring-2 focus:ring-primary/20" placeholder="Tema atau lokasi...">
                    <svg class="w-4 h-4 text-slate-300 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Kegiatan</label>
                <select wire:model.live="filterType" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-3 sm:p-4 font-bold text-sm focus:ring-2 focus:ring-primary/20 appearance-none">
                    <option value="">Semua Jenis</option>
                    @foreach($types as $type) <option value="{{ $type->id }}">{{ $type->nama }}</option> @endforeach
                </select>
            </div>
            <div class="hidden md:flex justify-end pb-3">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $schedules->total() }} Data Ditemukan</span>
            </div>
        </div>

        <!-- Agenda List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
            @forelse($schedules as $item)
            <div class="bg-white rounded-[32px] sm:rounded-[45px] p-6 sm:p-8 border border-slate-200/60 shadow-sm hover:shadow-lg transition-all group flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 sm:p-6">
                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WITA
                    </span>
                </div>

                <div class="flex items-center gap-4 sm:gap-5 mb-6 sm:mb-8">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-slate-900 text-white rounded-2xl sm:rounded-3xl flex flex-col items-center justify-center shadow-lg group-hover:bg-primary transition-colors shrink-0">
                        <span class="text-xl sm:text-2xl font-black leading-none">{{ $item->tanggal->format('d') }}</span>
                        <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-widest">{{ $item->tanggal->format('M') }}</span>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[8px] sm:text-[9px] font-black text-primary uppercase tracking-widest block mb-0.5 truncate">{{ $item->type->nama }}</span>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 leading-tight uppercase truncate">{{ $item->tema }}</h3>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-[24px] sm:rounded-[30px] p-4 sm:p-5 border border-slate-100 mb-4 sm:mb-6 flex-1">
                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase mb-1.5 sm:mb-2">Lokasi:</p>
                    <div class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-700 leading-snug">
                                @if($item->family_id)
                                {{ $item->family->members->sortBy('hubungan_keluarga_id')->first()->churchPeople->full_name ?? 'Keluarga' }}
                                @else
                                {{ $item->lokasi_manual }}
                                @endif
                            </p>
                            @if($item->family_id)
                            <span class="block text-[9px] sm:text-[10px] font-normal text-slate-500 mt-0.5 truncate">{{ $item->family->alamat }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 sm:pt-4 border-t border-slate-50">
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $item->id }})" class="p-2 sm:p-3 bg-white border border-slate-100 text-slate-400 hover:text-amber-500 rounded-xl sm:rounded-2xl transition-all shadow-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus agenda ini?" class="p-2 sm:p-3 bg-white border border-slate-100 text-slate-400 hover:text-rose-500 rounded-xl sm:rounded-2xl transition-all shadow-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-black text-slate-300 uppercase tracking-widest truncate max-w-[80px]">{{ $item->wilayah->nama ?? 'Umum' }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 sm:py-24 text-center bg-white rounded-[32px] sm:rounded-[50px] border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada agenda terdaftar.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8 sm:mt-10">{{ $schedules->links() }}</div>
    </div>

    <!-- MODAL FORM (Responsive Bottom Sheet on Mobile) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 animate-in fade-in duration-300">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('isModalOpen', false)"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-t-[32px] sm:rounded-[40px] shadow-2xl overflow-hidden animate-in slide-in-from-bottom sm:slide-in-from-top-4 duration-300 max-h-[95vh] flex flex-col">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>

            <div class="p-6 sm:p-8 border-b border-slate-50 flex justify-between items-center sticky top-0 bg-white z-10">
                <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight">{{ $editId ? 'Edit Agenda' : 'Agenda Baru' }}</h2>
                <button wire:click="$set('isModalOpen', false)" class="text-slate-300 hover:text-rose-500 p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-5 sm:space-y-6 custom-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Agenda</label>
                        <select wire:model="ref_activity_type_id" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                        </select>
                        @error('ref_activity_type_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tema / Judul</label>
                        <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm" placeholder="Cth: Ibadah Hari Minggu">
                        @error('tema') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal</label>
                        <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm">
                        @error('tanggal') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Waktu Mulai</label>
                        <input wire:model="jam_mulai" type="time" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm">
                    </div>
                </div>

                <div class="relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Cari Tuan Rumah (Opsional)</label>
                    @if($selectedFamilyLabel)
                    <div class="flex justify-between items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <span class="font-black text-blue-900 text-sm truncate mr-2">{{ $selectedFamilyLabel }}</span>
                        <button type="button" wire:click="$set('selectedFamilyLabel', null); $set('family_id', null)" class="text-[10px] font-black text-rose-500 uppercase hover:underline">Ganti</button>
                    </div>
                    @else
                    <input wire:model.live.debounce.300ms="searchFamily" type="text" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm" placeholder="Ketik nama keluarga...">
                    @if(!empty($foundFamilies))
                    <div class="absolute z-20 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                        @foreach($foundFamilies as $f)
                        @php $head = $f->members->sortBy('hubungan_keluarga_id')->first(); @endphp
                        <button wire:click="selectFamily({{ $f->id }}, '{{ $head->churchPeople->full_name ?? '-' }}')" class="w-full text-left p-3 hover:bg-slate-50 transition-colors">
                            <p class="font-black text-slate-800 text-xs sm:text-sm uppercase">{{ $head->churchPeople->full_name ?? '-' }}</p>
                            <p class="text-[8px] sm:text-[9px] text-slate-400 font-bold uppercase">No. KK: {{ $f->nomor_kk }}</p>
                        </button>
                        @endforeach
                    </div>
                    @endif
                    @endif
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lokasi / Alamat Manual</label>
                    <input wire:model="lokasi_manual" type="text" class="w-full bg-slate-50 border-none rounded-xl p-3 sm:p-4 font-bold text-sm" placeholder="Cth: Gedung Gereja Pusat">
                    @error('lokasi_manual') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                    <textarea wire:model="keterangan" rows="3" class="w-full bg-slate-50 border-none rounded-xl p-4 font-bold text-sm resize-none"></textarea>
                </div>
            </div>

            <div class="p-6 sm:p-8 border-t border-slate-50 bg-slate-50/50 sticky bottom-0">
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="w-full py-4 sm:py-5 bg-slate-900 text-white rounded-2xl sm:rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="save">Simpan Agenda</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

</div>