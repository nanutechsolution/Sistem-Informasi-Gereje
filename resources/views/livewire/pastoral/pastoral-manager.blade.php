<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER & RINGKASAN EKSEKUTIF -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter leading-none italic uppercase">Log Penggembalaan</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-indigo-600 pl-4 uppercase text-[10px] tracking-widest">Sistem Pemantauan Rohani & Kebajikan Jemaat</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <button onclick="window.print()" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-[24px] font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Laporan
                </button>
                <button wire:click="create" class="flex-1 md:flex-none px-8 py-4 bg-indigo-600 text-white rounded-[24px] font-black text-xs shadow-xl shadow-indigo-500/20 hover:scale-105 transition-all active:scale-95 cursor-pointer uppercase tracking-widest">
                    + Catat Lawatan
                </button>
            </div>
        </div>

        <!-- SEARCH & ADVANCED FILTER -->
        <div class="bg-white rounded-[40px] p-6 shadow-sm border border-slate-100 mb-10 flex flex-col lg:flex-row gap-6 items-end print:hidden">
            <div class="w-full lg:flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4">Cari Jemaat / Pokok Doa</label>
                <div class="relative">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-3xl font-bold text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all" placeholder="Ketik nama jemaat atau kandungan doa...">
                </div>
            </div>

            <div class="w-full lg:w-48">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4">Kategori</label>
                <select wire:model.live="filterCategory" class="w-full bg-slate-50 border-none rounded-3xl py-4 px-6 font-black text-sm text-slate-700 appearance-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                    <option value="">Semua</option>
                    <option value="rutin">Rutin</option>
                    <option value="sakit">Sakit</option>
                    <option value="penguatan">Penguatan</option>
                    <option value="duka">Duka</option>
                </select>
            </div>

            <div class="w-full lg:w-48">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4">Tindakan Susulan</label>
                <select wire:model.live="filterFollowUp" class="w-full bg-slate-50 border-none rounded-3xl py-4 px-6 font-black text-sm text-slate-700 appearance-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                    <option value="">Semua</option>
                    <option value="1">Perlu Perhatian</option>
                    <option value="0">Selesai</option>
                </select>
            </div>
        </div>

        <!-- TIMELINE VIEW -->
        <div class="relative">
            <!-- Central Vertical Line -->
            <div class="absolute left-6 md:left-1/2 top-0 bottom-0 w-0.5 bg-slate-200 hidden md:block"></div>

            @forelse($visits as $visit)
            <div class="relative flex flex-col md:flex-row items-center gap-8 mb-12 {{ $loop->index % 2 == 0 ? '' : 'md:flex-row-reverse' }}">
                <!-- Progress Dot -->
                <div class="absolute left-6 md:left-1/2 -translate-x-1/2 w-5 h-5 bg-indigo-600 rounded-full border-4 border-white shadow-lg z-10 hidden md:block"></div>

                <!-- Card Container -->
                <div class="w-full md:w-[45%]">
                    <div class="bg-white rounded-[50px] p-10 border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">

                        <!-- Top Badges -->
                        <div class="flex justify-between items-center mb-6">
                            <span class="px-4 py-1.5 bg-slate-100 text-slate-500 text-[9px] font-black uppercase rounded-full tracking-widest italic border border-slate-200">
                                {{ $visit->tanggal_kunjungan->isoFormat('D MMMM Y') }}
                            </span>
                            <span class="px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest 
                                {{ $visit->kategori == 'sakit' ? 'bg-rose-100 text-rose-700' : ($visit->kategori == 'syukuran' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700') }}">
                                {{ $visit->kategori }}
                            </span>
                        </div>

                        <!-- Content -->
                        <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-tight mb-2">{{ $visit->member->nama }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-50 pb-4 flex items-center gap-2">
                            <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                            Oleh: {{ $visit->visitor->member->nama }}
                        </p>

                        <div class="bg-indigo-50/50 rounded-[32px] p-6 italic text-sm text-slate-700 mb-6 relative group-hover:bg-indigo-50 transition-colors">
                            <span class="block text-[9px] font-black text-indigo-500 uppercase not-italic mb-3 tracking-widest leading-none">Pokok Doa & Harapan:</span>
                            <p class="leading-relaxed">"{{ $visit->pokok_doa }}"</p>
                            <!-- Decorative Quote Icon -->
                            <svg class="absolute right-6 bottom-6 w-10 h-10 text-indigo-100 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V3H21.017V15C21.017 16.1046 20.1216 17 19.017 17H17.017L17.017 21H14.017ZM3.017 21L3.017 18C3.017 16.8954 3.91243 16 5.017 16H8.017C8.56928 16 9.017 15.5523 9.017 15V9C9.017 8.44772 8.56928 8 8.017 8H5.017C3.91243 8 3.017 7.10457 3.017 6V3H10.017V15C10.017 16.1046 9.12157 17 8.017 17H6.017L6.017 21H3.017Z" />
                            </svg>
                        </div>

                        <!-- Follow up status footer -->
                        <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-50">
                            @if($visit->perlu_tindak_lanjut)
                            <div class="flex items-center gap-2 text-rose-500 text-[10px] font-black uppercase tracking-widest animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Perlu Perhatian Pendeta
                            </div>
                            @else
                            <div class="flex items-center gap-2 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Selesai Didoakan
                            </div>
                            @endif

                            <button class="p-2 bg-slate-50 text-slate-300 hover:text-indigo-600 rounded-xl transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Spacer for Timeline -->
                <div class="w-full md:w-[45%] hidden md:block"></div>
            </div>
            @empty
            <div class="py-40 text-center bg-white rounded-[60px] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-300 italic uppercase tracking-widest leading-none">Tiada Rekod Lawatan</h3>
                <p class="text-slate-400 text-sm mt-3 font-medium">Mulakan dengan mencatat lawatan pastoral pertama hari ini.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $visits->links() }}</div>
    </div>

    <!-- MODAL FORM PRO (FULL WIDTH ON MOBILE) -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl transition-opacity" @click="showForm = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[50px] sm:rounded-[60px] p-8 sm:p-12 text-left shadow-2xl transition-all overflow-hidden border-b-8 border-indigo-600 animate-in slide-in-from-bottom duration-300">

                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h3 class="text-4xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Log Kasih</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Pendokumentasian Penggembalaan Jemaat</p>
                    </div>
                    <button @click="showForm = false" class="p-3 bg-slate-50 rounded-2xl text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-8">
                    <!-- SEARCH & SELECT PERSONA -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-3 ml-2">Jemaat Sasaran Lawatan</label>
                        @if($selectedMemberName)
                        <div class="p-6 bg-indigo-50 border border-indigo-100 rounded-[32px] flex justify-between items-center animate-in zoom-in-95">
                            <span class="font-black text-indigo-900 text-lg italic tracking-tight">{{ $selectedMemberName }}</span>
                            <button type="button" wire:click="$set('selectedMemberName', null)" class="text-[9px] font-black uppercase text-indigo-500 underline tracking-tighter">Ganti Jemaat</button>
                        </div>
                        @else
                        <input wire:model.live.debounce.300ms="searchMember" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-[32px] p-6 font-black text-lg text-slate-900 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300 shadow-inner" placeholder="Cari nama jemaat...">
                        @if(count($foundMembers) > 0)
                        <div x-show="open" class="absolute z-50 w-full mt-3 bg-white rounded-[32px] shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                            @foreach($foundMembers as $m)
                            <button type="button" wire:click="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" @click="open = false" class="w-full text-left p-6 hover:bg-indigo-50 transition-colors group flex items-center justify-between">
                                <div>
                                    <p class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $m['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $m['family']['refWilayah']['nama'] ?? 'Tanpa Wilayah' }}</p>
                                </div>
                                <svg class="w-5 h-5 text-slate-200 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @endif
                        @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-4 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <!-- META DATA LAWATAN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Kategori Lawatan</label>
                            <select wire:model="kategori" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black text-slate-700 appearance-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer shadow-inner">
                                <option value="rutin">Rutin / Bulanan</option>
                                <option value="sakit">Pelayanan Orang Sakit</option>
                                <option value="penguatan">Penguatan Iman / Masalah</option>
                                <option value="syukuran">Syukuran & Berkat</option>
                                <option value="duka">Penghiburan Kedukaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Tarikh / Waktu</label>
                            <input wire:model="tanggal_kunjungan" type="date" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black focus:ring-4 focus:ring-indigo-500/10 shadow-inner">
                        </div>
                    </div>

                    <!-- POKOK DOA (CORE OF PASTORAL) -->
                    <div>
                        <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-3 ml-2 italic leading-none">Intipati Doa & Catatan Pastoral</label>
                        <textarea wire:model="pokok_doa" rows="4" class="w-full bg-slate-50 border-none rounded-[40px] p-8 font-bold text-slate-900 shadow-inner focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300" placeholder="Ceritakan keadaan jemaat dan apa yang didoakan..."></textarea>
                        @error('pokok_doa') <span class="text-rose-500 text-[10px] font-bold block mt-3 ml-4 uppercase animate-bounce">{{ $message }}</span> @enderror
                    </div>


                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-3 ml-2">Pelayanan</label>
                        @if($selectedOfficerName)
                        <div class="p-6 bg-indigo-50 border border-indigo-100 rounded-[32px] flex justify-between items-center animate-in zoom-in-95">
                            <span class="font-black text-indigo-900 text-lg italic tracking-tight">{{ $selectedOfficerName }}</span>
                            <button type="button" wire:click="$set('selectedOfficerName', null)" class="text-[9px] font-black uppercase text-indigo-500 underline tracking-tighter">Ganti Jemaat</button>
                        </div>
                        @else
                        <input wire:model.live.debounce.300ms="searchMember" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-[32px] p-6 font-black text-lg text-slate-900 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300 shadow-inner" placeholder="Cari nama jemaat...">
                        @if(count($foundOfficers) > 0)
                        <div x-show="open" class="absolute z-50 w-full mt-3 bg-white rounded-[32px] shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                            @foreach($foundOfficers as $m)
                            <button type="button" wire:click="selectOfficer({{ $m['id'] }}, '{{ $m['member']['nama'] }}')" @click="open = false" class="w-full text-left p-6 hover:bg-indigo-50 transition-colors group flex items-center justify-between">
                                <div>
                                    <p class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $m['member']['nama'] }}</p>
                                </div>
                                <svg class="w-5 h-5 text-slate-200 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @endif
                        @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-4 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <!-- ACTION SWITCH PRO -->
                    <div class="flex items-center justify-between p-8 bg-indigo-50/50 rounded-[40px] border border-indigo-100 shadow-inner group">
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-white rounded-2xl text-indigo-600 shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block text-sm font-black text-indigo-900 leading-none">Perlu Tindakan Susulan?</span>
                                <span class="text-[10px] text-indigo-400 font-bold uppercase mt-2 block tracking-tight italic">Tandai jika memerlukan lawatan khas Pendeta Jemaat.</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="perlu_tindak_lanjut" class="sr-only peer">
                            <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600 shadow-sm"></div>
                        </label>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-6 bg-slate-100 rounded-[32px] font-black text-[10px] uppercase tracking-[0.2em] text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-6 bg-indigo-600 text-white rounded-[32px] font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl shadow-indigo-500/40 hover:bg-indigo-700 transition transform active:scale-95 disabled:opacity-70">
                            <span wire:loading.remove italic>Verifikasi & Arkibkan Log</span>
                            <span wire:loading>Memproses Data Kasih...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>