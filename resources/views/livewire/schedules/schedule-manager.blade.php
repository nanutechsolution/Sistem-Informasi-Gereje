<div class="py-6 sm:py-12 bg-slate-50 min-h-screen"
    x-data="{ 
        formatRupiah(value) {
            if(!value) return '0';
            let number = value.toString().replace(/[^0-9]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Agenda Pelayanan</h1>
                <p class="text-slate-500 mt-3 font-medium">Manajemen jadwal Ibadah Minggu, PKS, dan pemantauan setoran persembahan.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-10 py-5 bg-primary text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all">
                + BUAT JADWAL BARU
            </button>
        </div>

        <!-- Toolbar -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4">
            <input wire:model.live.debounce.300ms="search" type="text" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/5" placeholder="Cari tema atau kegiatan...">
        </div>

        <!-- Schedule List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($schedules as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden flex flex-col">
                <!-- Status Badge -->
                <div class="flex justify-between items-start mb-6">
                    <div class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                        {{ $item->type->nama }}
                    </div>
                    @if($item->ref_activity_type_id == 2) {{-- Jika PKS --}}
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full {{ $item->status_setoran == 'disetor' ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                        <span class="text-[9px] font-black uppercase text-slate-400 tracking-tighter">
                            {{ $item->status_setoran == 'disetor' ? 'Terverifikasi' : 'Menunggu Setoran' }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="mb-6">
                    <p class="text-xs font-black text-primary uppercase tracking-tighter">{{ $item->tanggal->isoFormat('dddd, D MMMM Y') }}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase italic">{{ $item->wilayah->nama ?? 'Pusat' }} • Pkl {{ $item->jam_mulai->format('H:i') }}</p>
                </div>

                <h3 class="text-xl font-black text-slate-900 leading-tight mb-2 flex-1">{{ $item->tema ?? 'Ibadah Rutin' }}</h3>

                <div class="flex items-center gap-2 text-sm font-bold text-slate-400 mb-6">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $item->lokasi_display }}</span>
                </div>

                <!-- Bagian Persembahan (Hanya PKS) -->
                @if($item->ref_activity_type_id == 2)
                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kolekte Tercatat</p>
                        <p class="text-base font-black text-slate-900">Rp {{ number_format($item->nominal_persembahan ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pembawa Uang</p>
                        <p class="text-[10px] font-bold text-primary truncate w-24 italic">{{ $item->servants->where('peran', 'Pelayan Firman')->first()?->member->nama ?? 'Majelis' }}</p>
                    </div>
                </div>
                @endif

                <div class="pt-6 border-t border-slate-50 flex justify-between items-center">
                    <div class="flex -space-x-2">
                        @foreach($item->servants->take(3) as $servant)
                        <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-primary uppercase" title="{{ $servant->member->nama }}">
                            {{ substr($servant->member->nama, 0, 1) }}
                        </div>
                        @endforeach
                        @if($item->servants->count() > 3)
                        <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-slate-400">
                            +{{ $item->servants->count() - 3 }}
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('schedules.servants', $item) }}" class="text-xs font-black text-primary uppercase tracking-widest hover:underline decoration-2 underline-offset-4 flex items-center gap-1">
                        Atur Tim & Kolekte &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">{{ $schedules->links() }}</div>
    </div>

    <!-- MODAL FORM JADWAL -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="$set('isModalOpen', false)"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl">
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter leading-none">Registrasi Agenda</h3>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Kegiatan</label>
                            <select wire:model.live="ref_activity_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah Pelayanan</label>
                            <select wire:model="ref_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none">
                                <option value="">Semua Wilayah / Pusat</option>
                                @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    @if($ref_activity_type_id == 2) {{-- Khusus PKS --}}
                    <div class="p-6 bg-blue-50 rounded-[32px] border border-blue-100 space-y-6 animate-in slide-in-from-top-2">
                        <div class="relative" x-data="{ open: false }">
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-2 ml-1">Tuan Rumah (Kepala Keluarga)</label>
                            <input wire:model.live="searchFamily" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-white border-none rounded-2xl p-4 font-bold shadow-sm" placeholder="Cari nama bapak/ibu...">
                            @if(count($foundFamilies) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border overflow-hidden">
                                @foreach($foundFamilies as $f)
                                <button type="button" wire:mousedown.prevent="selectFamily({{ $f['id'] }}, '{{ $f['kepala_keluarga'] }}')" @mousedown="open = false" class="w-full text-left p-4 hover:bg-blue-50 font-bold text-sm">
                                    {{ $f['kepala_keluarga'] }} ({{ $f['nomor_kk'] }})
                                </button>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-2 ml-1 italic">Prakiraan / Catatan Kolekte (Rp)</label>
                            <input type="text"
                                wire:model="nominal_persembahan"
                                x-on:input="$el.value = formatRupiah($el.value)"
                                class="w-full bg-white border-none rounded-2xl p-4 font-black text-primary text-xl shadow-sm" placeholder="0">
                            <p class="text-[9px] text-blue-400 font-bold mt-2 uppercase tracking-tighter">* Anggota Keuangan akan memverifikasi setoran ini di hari Minggu.</p>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jam Mulai</label>
                            <input wire:model="jam_mulai" type="time" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tema / Topik Pelayanan</label>
                        <input wire:model="tema" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" placeholder="Misal: Keluarga yang Taat">
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" wire:click="$set('isModalOpen', false)" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl shadow-blue-500/30">Simpan Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>