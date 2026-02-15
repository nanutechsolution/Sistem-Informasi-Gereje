<div class="py-6 sm:py-10 bg-slate-50 min-h-screen text-slate-900" x-data="{ showModal: @entangle('isModalOpen').live, showBatch: @entangle('isBatchModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none">Penjadwalan PKS</h1>
                <p class="text-slate-500 mt-2 font-medium text-xs uppercase tracking-[0.2em] border-l-4 border-primary pl-3">Manajemen Ibadah Sektor</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                  <button onclick="window.open('{{ route('schedules.pks.print', ['startDate' => $filterStartDate, 'endDate' => $filterEndDate, 'wilayah' => $filterWilayah]) }}')" 
                    class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Agenda
                </button>
                <button @click="showBatch = true" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 v2M7 7h10" />
                    </svg>
                    Generator Massal
                </button>
                <button wire:click="create" class="flex-1 md:flex-none px-8 py-4 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-200 hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Manual
                </button>


            </div>
        </div>

        <!-- FILTERS -->
        <div class="bg-white rounded-[40px] p-6 shadow-sm border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pencarian</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Nama Tuan Rumah...">
            </div>
            <div>
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah</label>
                <select wire:model.live="filterWilayah" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <div class="flex-1">
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                    <input wire:model.live="filterStartDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                    <input wire:model.live="filterEndDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                </div>
            </div>
        </div>

        <!-- GRID JADWAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($schedules as $item)
            @php
            $head = $item->family->members->sortBy('hubungan_keluarga_id')->first();
            $hostName = $head->churchPeople->full_name ?? 'Keluarga';
            @endphp
            <div class="bg-white rounded-[45px] p-8 border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6">
                    <span class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WITA</span>
                </div>

                <div class="flex items-center gap-5 mb-8">
                    <div class="h-16 w-16 bg-slate-900 text-white rounded-3xl flex flex-col items-center justify-center shadow-lg group-hover:bg-primary transition-colors">
                        <span class="text-2xl font-black leading-none">{{ $item->tanggal->format('d') }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">{{ $item->tanggal->format('M') }}</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 leading-tight uppercase truncate w-40">{{ $hostName }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            {{ $item->family->wilayah->nama ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-[30px] p-5 border border-slate-100 mb-6 space-y-3">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Tema / Firman:</p>
                        <p class="text-xs font-bold text-slate-700 italic">"{{ $item->tema }}"</p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-200/50">
                        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-[10px] font-black text-primary">PF</div>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $item->servants->where('peran', 'Pembaca Firman')->first()->member->churchPeople->full_name ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-auto pt-4 flex justify-between items-center">

                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus jadwal ini?" class="p-3 text-rose-300 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[40px] border border-dashed border-slate-200 text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada jadwal.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $schedules->links() }}</div>
    </div>

    <!-- MODAL INPUT MANUAL -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" @click="showModal = false"></div>
        <div class="relative bg-white w-full max-w-xl rounded-[50px] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
            <div class="p-10">
                <h2 class="text-2xl font-black uppercase tracking-tight mb-8">Input Jadwal PKS</h2>

                @if($clashWarning)
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-3xl flex items-start gap-3 animate-in fade-in shadow-sm border-l-4 border-l-rose-500">
                    <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-xs font-bold text-rose-800 leading-snug">{{ $clashWarning }}</p>
                    </div>
                </div>
                @endif

                <div class="space-y-6">
                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pilih Tuan Rumah</label>
                        @if($selectedFamilyLabel)
                        <div class="flex justify-between items-center bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <span class="font-black text-blue-900 text-sm truncate mr-2">{{ $selectedFamilyLabel }}</span>
                            <button wire:click="$set('selectedFamilyLabel', null)" class="text-[10px] font-black text-rose-500 uppercase hover:underline">Ganti</button>
                        </div>
                        @else
                        <input wire:model.live.debounce.300ms="searchFamily" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama keluarga / No KK...">
                        @if(!empty($foundFamilies))
                        <div class="absolute z-10 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                            @foreach($foundFamilies as $f)
                            <button wire:click="selectFamily({{ $f->id }}, '{{ $f->members->sortBy('hubungan_keluarga_id')->first()->churchPeople->full_name ?? 'Keluarga' }}')" class="w-full text-left p-4 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors">
                                <p class="font-black text-slate-800 text-sm uppercase">{{ $f->members->sortBy('hubungan_keluarga_id')->first()->churchPeople->full_name ?? 'Keluarga' }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">KK: {{ $f->nomor_kk }} • {{ $f->wilayah->nama }}</p>
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Gunakan Kelompok</label>
                            <select wire:model.live="service_group_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900">
                                <option value="">-- Pilih Kelompok --</option>
                                @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Tema Ibadah</label>
                            <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900" placeholder="Cth: Keluarga Beriman">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Tanggal & Jam</label>
                            <div class="flex gap-2">
                                <input wire:model.live="tanggal" type="date" class="flex-[2] bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                <input wire:model="jam_mulai" type="time" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Pelayan Firman</label>
                            <select wire:model.live="selected_pf_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900">
                                <option value="">-- Pilih PF --</option>
                                @foreach($staffList as $s) <option value="{{ $s->member_id }}">{{ $s->member->churchPeople->full_name }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <button wire:click="save" wire:loading.attr="disabled" {{ $clashWarning ? 'disabled' : '' }} class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all mt-4 disabled:bg-slate-300 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">Simpan Jadwal</span>
                        <span wire:loading wire:target="save">Memproses...</span>
                    </button>
                    @if($clashWarning) <p class="text-[9px] text-center font-black text-rose-500 uppercase mt-2 italic">Tombol dikunci karena ada jadwal bentrok.</p> @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GENERATOR MASSAL (BATCH) -->
    <div x-show="showBatch" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" @click="showBatch = false"></div>
        <div class="relative bg-white w-full max-w-lg rounded-[50px] shadow-2xl p-10 overflow-hidden animate-in zoom-in-95">
            <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-8 text-emerald-600">Generator Massal</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Target Wilayah</label>
                    <select wire:model="batch_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 appearance-none">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kelompok Pelayan</label>
                    <select wire:model="batch_group_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 appearance-none">
                        <option value="">-- Pilih Tim --</option>
                        @foreach($groups as $g) <option value="{{ $g->id }}">{{ $g->nama_kelompok }}</option> @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Tanggal Mulai</label>
                        <input wire:model="batch_start_date" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Jam Ibadah</label>
                        <input wire:model="batch_time" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                    </div>
                </div>

                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <p class="text-[10px] text-emerald-700 font-bold leading-snug">
                        Sistem akan otomatis menjadwalkan keluarga di wilayah ini satu per satu setiap minggu. Keluarga yang baru saja melayani (< 2 bulan) akan dilewati secara otomatis.
                            </p>
                </div>

                <button wire:click="generateBatch" wire:loading.attr="disabled" class="w-full py-5 bg-emerald-600 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-700 transition-all mt-4">
                    <span wire:loading.remove wire:target="generateBatch">Mulai Generate</span>
                    <span wire:loading wire:target="generateBatch">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>