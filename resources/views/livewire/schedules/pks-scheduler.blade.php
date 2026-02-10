<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showModal: @entangle('isModalOpen').live, showBatch: @entangle('isBatchModalOpen').live, showAudit: @entangle('isAuditModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter leading-none italic uppercase">PKS Scheduler</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest leading-relaxed">
                    Manajemen Keadilan Pelayanan & Jadwal Ibadah Rumah Tangga
                </p>
            </div>

            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="{{ route('schedules.pks.print', ['startDate' => $filterStartDate, 'endDate' => $filterEndDate, 'wilayah' => $filterWilayah]) }}"
                    target="_blank"
                    class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-[24px] font-black text-xs shadow-sm hover:bg-slate-50 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    CETAK JADWAL
                </a>

                <button wire:click="$set('isAuditModalOpen', true)" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-[24px] font-black text-xs shadow-sm hover:bg-slate-50 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    CEK ANTRIAN
                </button>
                <button wire:click="$set('isBatchModalOpen', true)" class="flex-1 md:flex-none px-6 py-4 bg-emerald-600 text-white rounded-[24px] font-black text-xs shadow-xl shadow-emerald-500/20 hover:scale-105 transition-all uppercase tracking-widest">
                    GENERATE WILAYAH
                </button>
                <button wire:click="create" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-xl hover:scale-105 transition-all uppercase tracking-widest">
                    INPUT MANUAL
                </button>
            </div>
        </div>

        <!-- STATISTICS WIDGETS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 text-center sm:text-left">
            <div class="bg-white p-8 rounded-[40px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Total Terjadwal</p>
                <h3 class="text-3xl font-black text-slate-900 italic">{{ $stats['total'] }} <span class="text-xs font-bold text-slate-400 not-italic uppercase tracking-normal">Rumah</span></h3>
            </div>
            <div class="bg-white p-8 rounded-[40px] border border-slate-200 shadow-sm border-l-4 border-emerald-500">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1 leading-none">Sudah Terlaksana</p>
                <h3 class="text-3xl font-black text-slate-900 italic">{{ $stats['terlaksana'] }} <span class="text-xs font-bold text-slate-400 not-italic">LOKASI</span></h3>
            </div>
            <div class="bg-white p-8 rounded-[40px] border border-slate-200 shadow-sm border-l-4 border-blue-500">
                <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1 leading-none">Menunggu Rencana</p>
                <h3 class="text-3xl font-black text-slate-900 italic">{{ $stats['rencana'] }} <span class="text-xs font-bold text-slate-400 not-italic">LOKASI</span></h3>
            </div>
            <div @click="showAudit = true" class="bg-rose-50 p-8 rounded-[40px] border border-rose-100 shadow-sm relative overflow-hidden group cursor-pointer hover:bg-rose-100 transition-all">
                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1 leading-none">Belum Masuk Jadwal</p>
                <h3 class="text-3xl font-black text-rose-600 italic">{{ $stats['belum_terjadwal'] }} <span class="text-xs font-bold text-rose-400 not-italic uppercase tracking-normal">Keluarga</span></h3>
                <div class="absolute -right-4 -bottom-4 text-rose-200/30 group-hover:scale-110 transition-transform"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg></div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4 items-end">
            <div class="relative flex-1 w-full">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Cari Tuan Rumah</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Ketik nama...">
                <svg class="absolute left-4 top-[58px] -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" />
                </svg>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Wilayah</label>
                <select wire:model.live="filterWilayah" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-sm cursor-pointer appearance-none">
                    <option value="">Semua</option>
                    @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Status</label>
                <select wire:model.live="filterStatus" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-sm cursor-pointer appearance-none">
                    <option value="">Semua Status</option>
                    <option value="rencana">📅 Rencana</option>
                    <option value="terlaksana">✅ Terlaksana</option>
                    <option value="batal">❌ Batal</option>
                </select>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <div class="flex-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Dari</label>
                    <input wire:model.live="filterStartDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-xs">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Sampai</label>
                    <input wire:model.live="filterEndDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-xs">
                </div>
            </div>
        </div>

        <!-- GRID JADWAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($schedules as $item)
            <div class="bg-white rounded-[50px] p-10 border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 group flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-8 relative z-10">
                    <div class="h-14 w-14 rounded-3xl bg-slate-900 text-white flex flex-col items-center justify-center shadow-xl group-hover:bg-primary transition-colors">
                        <span class="text-xl font-black leading-none">{{ $item->tanggal->format('d') }}</span>
                        <span class="text-[9px] font-bold uppercase tracking-tighter">{{ $item->tanggal->format('M') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-[9px] font-black uppercase rounded-full border {{ $item->status == 'terlaksana' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($item->status == 'rencana' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-rose-50 text-rose-600 border-rose-100') }}">
                            {{ $item->status }}
                        </span>
                        <p class="text-[10px] font-black text-slate-400 mt-2 uppercase tracking-widest">{{ $item->family->refWilayah->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-10 relative z-10 flex-1">
                    <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-1 leading-none">Tuan Rumah:</p>
                    <h3 class="text-2xl font-black text-slate-900 leading-tight uppercase italic mb-4">{{ $item->family->kepala_keluarga ?? '-' }}</h3>

                    <div class="bg-slate-50 p-5 rounded-[32px] border border-slate-100/50">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-[8px] font-black shadow-lg">PF</div>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $item->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Belum Ada' }}</p>
                        </div>

                        @if($item->tema)
                        <div class="mb-4">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Tema Firman:</p>
                            <p class="text-xs font-bold text-primary italic leading-snug">"{{ $item->tema }}"</p>
                        </div>
                        @endif

                        <div class="flex -space-x-2 pl-1">
                            @foreach($item->servants->where('peran', 'Pendamping')->take(5) as $p)
                            <div class="w-7 h-7 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center text-[8px] font-black text-slate-400 uppercase shadow-sm" title="{{ $p->member->nama }}">
                                {{ substr($p->member->nama, 0, 1) }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-8 border-t border-slate-100 relative z-10 flex justify-between items-end">
                    <div class="flex gap-2">
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus jadwal ini?" class="p-4 bg-rose-50 text-rose-400 rounded-3xl hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        <a href="{{ route('schedules.servants', $item) }}" class="p-4 bg-slate-900 text-white rounded-3xl hover:bg-primary transition-all shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center bg-white rounded-[50px] border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-black uppercase text-xs tracking-widest italic animate-pulse">Belum ada jadwal ibadah yang sesuai filter.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $schedules->links() }}</div>
    </div>

    <!-- MODAL AUDIT ANTRIAN -->
    <div x-show="showAudit" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showAudit = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-3xl bg-white rounded-[50px] p-10 shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500"></div>

                <h3 class="text-2xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Daftar Antrian Pelayanan</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-50 pb-4">
                    KK yang belum dijadwalkan antara {{ \Carbon\Carbon::parse($filterStartDate)->format('d M') }} - {{ \Carbon\Carbon::parse($filterEndDate)->format('d M Y') }}
                </p>

                <div class="max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($unscheduledList as $un)
                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 flex items-center justify-between group hover:bg-rose-50 transition-colors">
                            <div>
                                <p class="font-black text-slate-800 text-sm uppercase italic leading-none mb-1">{{ $un->kepala_keluarga }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $un->refWilayah->nama ?? '-' }}</p>
                            </div>
                            <button wire:click="selectFamily({{ $un->id }}, '{{ $un->kepala_keluarga }}', '{{ $un->nomor_kk }}')" @click="showAudit = false; showModal = true" class="p-3 bg-white rounded-2xl text-slate-300 hover:text-primary shadow-sm border border-slate-100 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        @empty
                        <div class="col-span-2 py-20 text-center">
                            <p class="text-slate-300 font-black uppercase text-xs italic tracking-widest">Luar biasa! Semua KK sudah masuk dalam jadwal.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-50 text-center">
                    <button @click="showAudit = false" class="px-10 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-transform">Selesai</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GENERATOR WILAYAH -->
    <div x-show="showBatch" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-emerald-900/90 backdrop-blur-md" @click="showBatch = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[50px] sm:rounded-[50px] p-10 shadow-2xl transition-all overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500 shadow-lg shadow-emerald-500/50"></div>

                <h3 class="text-3xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Generator Wilayah</h3>
                <p class="text-xs font-bold text-emerald-600 mb-10 uppercase tracking-widest border-b border-slate-50 pb-4">Membuat jadwal rutin mingguan per Wilayah</p>

                <form wire:submit="generateBatch" class="space-y-6 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Wilayah</label>
                        <select wire:model="batch_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-slate-700 shadow-inner">
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                        </select>
                        @error('batch_wilayah_id') <span class="text-rose-600 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Kelompok Pelayanan</label>
                        <select wire:model="batch_group_id" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-slate-700 shadow-inner">
                            <option value="">-- Pilih Kelompok --</option>
                            @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                        </select>
                        @error('batch_group_id') <span class="text-rose-600 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mulai Tanggal</label>
                            <input wire:model="batch_start_date" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jam Ibadah</label>
                            <input wire:model="batch_time" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner">
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showBatch = false" class="flex-1 py-5 bg-slate-100 rounded-[24px] font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-emerald-600 text-white rounded-[24px] font-black text-[10px] uppercase shadow-2xl hover:bg-emerald-700 transition transform active:scale-95">Mulai Generator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL MANUAL INPUT -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showModal = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl text-left overflow-hidden transition-all animate-in slide-in-from-bottom duration-300">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter leading-none">Input Manual PKS</h3>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- TUAN RUMAH -->
                        <div class="relative" x-data="{ open: false }">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tuan Rumah (KK)</label>
                            @if($selectedFamilyLabel)
                            <div class="p-5 bg-blue-50 border border-blue-100 rounded-3xl flex justify-between items-center animate-in zoom-in-95">
                                <span class="font-black text-slate-800 text-sm truncate mr-2">{{ $selectedFamilyLabel }}</span>
                                <button type="button" wire:click="$set('selectedFamilyLabel', null)" class="text-[9px] font-black text-primary uppercase underline">Ganti</button>
                            </div>
                            @else
                            <input wire:model.live.debounce.300ms="searchFamily" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-4 focus:ring-primary/5 shadow-inner" placeholder="Ketik nama KK...">
                            @if(count($foundFamilies) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundFamilies as $f)
                                <button type="button" wire:mousedown.prevent="selectFamily({{ $f['id'] }}, '{{ $f['kepala_keluarga'] }}', '{{ $f['nomor_kk'] }}')" @click="open = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors">
                                    <p class="font-black text-slate-900 text-sm uppercase leading-none">{{ $f['kepala_keluarga'] }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">KK: {{ $f['nomor_kk'] }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                            @endif
                            @error('family_id') <span class="text-rose-600 text-[10px] font-bold mt-1 block ml-2">{{ $message }}</span> @enderror
                        </div>

                        <!-- KELOMPOK TIM -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Kelompok Tim</label>
                            <select wire:model.live="service_group_id" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black shadow-inner appearance-none cursor-pointer">
                                <option value="">-- Gunakan Kelompok --</option>
                                @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- TEMA / FIRMAN TUHAN -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tema Ibadah / Firman Tuhan</label>
                        <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold shadow-inner focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Contoh: Keluarga Yang Diberkati Untuk Memberkati">
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold shadow-inner">
                            @error('tanggal') <span class="text-rose-600 text-[10px] font-bold mt-1 block ml-2">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jam Mulai</label>
                            <input wire:model="jam_mulai" type="time" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold shadow-inner">
                        </div>
                    </div>

                    <!-- PELAYAN FIRMAN (AUTO FILLED FROM GROUP) -->
                    <div class="bg-slate-900 rounded-[32px] p-8 text-white relative overflow-hidden group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Penugasan Utama (Pelayan Firman)</label>
                        <select wire:model="selected_pf_id" class="w-full bg-white/10 border-none rounded-2xl p-4 font-black text-sm text-white focus:ring-2 focus:ring-primary appearance-none cursor-pointer">
                            <option value="">-- Pilih PF --</option>
                            @foreach($staffList as $staff) <option value="{{ $staff->member_id }}">{{ $staff->member->nama }} ({{ $staff->position->nama }})</option> @endforeach
                        </select>
                        <p class="mt-4 text-[9px] font-bold text-amber-400 uppercase italic">* Pelayan Firman terisi otomatis jika kelompok dipilih.</p>
                        @error('selected_pf_id') <span class="text-rose-400 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror

                        <div class="absolute right-[-20px] bottom-[-20px] opacity-10 rotate-12 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.993 7.993 0 002 12a7.998 7.998 0 003 6.31V15c0-1.105.895-2 2-2h6c1.105 0 2 .895 2 2v3.31A7.998 7.998 0 0018 12a7.993 7.993 0 00-7-7.196V4a1 1 0 00-2 0v.804z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl hover:bg-blue-800 transition transform active:scale-95 shadow-blue-500/30">
                            <span wire:loading.remove>Simpan Jadwal</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>