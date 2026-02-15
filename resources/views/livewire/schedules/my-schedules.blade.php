<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none">Agenda</h1>
                    <p class="text-slate-500 mt-2 font-medium text-xs uppercase tracking-widest border-l-4 border-primary pl-3">
                        {{ $activeTab === 'active' ? 'Jadwal Mendatang & Tugas' : 'Riwayat Pelayanan Selesai' }}
                    </p>
                </div>
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-center min-w-[70px]">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Item</span>
                    <span class="text-2xl font-black text-primary leading-none">{{ $schedules->total() }}</span>
                </div>
            </div>

            @if($error_message)
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-3xl flex items-center gap-3 text-rose-600 mb-6">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-bold">{{ $error_message }}</p>
                </div>
            @endif

            <!-- Tab Switcher -->
            <div class="flex bg-slate-200/50 p-1.5 rounded-[24px] gap-1 shadow-inner border border-slate-200/20">
                <button wire:click="$set('activeTab', 'active')" 
                    class="flex-1 py-3 rounded-[20px] text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'active' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                    Aktif & Tugas
                </button>
                <button wire:click="$set('activeTab', 'history')" 
                    class="flex-1 py-3 rounded-[20px] text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'history' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                    Riwayat Selesai
                </button>
            </div>
        </div>

        <!-- Agenda List (Calendar Style) -->
        <div class="space-y-8 relative">
            <!-- Garis Alur Waktu -->
            <div class="absolute left-[27px] top-2 bottom-2 w-0.5 bg-slate-200 z-0"></div>

            @forelse($schedules as $item)
                @php
                    $head = $item->family?->members->sortBy('hubungan_keluarga_id')->first();
                    $hostName = $head ? ($head->churchPeople->full_name ?? 'Keluarga') : 'Keluarga';
                    
                    $tanggal = \Carbon\Carbon::parse($item->tanggal);
                    $isToday = $tanggal->isToday();
                    $isPast = $tanggal->isPast();
                    
                    $myServantRecord = $item->servants->where('member_id', $myMemberId)->first();
                    $isPF = $myServantRecord && $myServantRecord->peran === 'Pembaca Firman';
                @endphp

                <div class="relative z-10 flex gap-6 group animate-in slide-in-from-bottom-4 duration-300">
                    <!-- Penanda Tanggal -->
                    <div class="shrink-0 flex flex-col items-center">
                        <div class="h-14 w-14 rounded-2xl flex flex-col items-center justify-center shadow-sm border transition-all duration-300 {{ $isToday ? 'bg-primary text-white border-primary scale-110 shadow-primary/30' : ($isPF ? 'bg-amber-400 text-white border-amber-400' : 'bg-white text-slate-400 border-slate-100 group-hover:border-primary/30') }}">
                            <span class="text-lg font-black leading-none">{{ $tanggal->format('d') }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-widest">{{ $tanggal->format('M') }}</span>
                        </div>
                        @if($isPF)
                            <div class="mt-2 w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                        @endif
                    </div>

                    <!-- Agenda Card -->
                    <div class="flex-1 bg-white rounded-[32px] shadow-sm border border-slate-200/60 overflow-hidden transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1 {{ $isPF ? 'ring-2 ring-amber-400/50' : '' }}">
                        
                        <!-- Header Card -->
                        <div class="px-6 py-4 flex justify-between items-center {{ $isPF ? 'bg-amber-400' : ($isToday ? 'bg-primary' : ($isPast ? 'bg-slate-400' : 'bg-slate-900')) }}">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white">
                                    {{ $item->type->nama ?? 'IBADAH' }}
                                </span>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase">
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WITA
                            </span>
                        </div>

                        <div class="p-6">
                            <!-- Informasi Utama -->
                            <div class="mb-5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tuan Rumah</p>
                                <h3 class="text-xl font-black text-slate-900 leading-none uppercase tracking-tight">{{ $hostName }}</h3>
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $item->family->alamat ?? $item->lokasi_manual ?? 'Alamat belum diisi' }}
                                </p>
                            </div>

                            <!-- Highlight Tema -->
                            <div class="mb-6 p-4 rounded-2xl {{ $isPF ? 'bg-amber-50 border border-amber-100' : 'bg-slate-50 border border-slate-100' }}">
                                <p class="text-[9px] font-black {{ $isPF ? 'text-amber-500' : 'text-slate-400' }} uppercase tracking-widest mb-1">Tema Ibadah</p>
                                <p class="text-sm font-bold text-slate-800 italic">"{{ $item->tema ?? 'Ibadah Rumah Tangga' }}"</p>
                            </div>

                            <!-- Tim Rekan Pelayan -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tim Pelayanan</p>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $item->servants->count() }} Orang</span>
                                </div>
                                @foreach($item->servants as $servant)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black {{ $servant->member_id == $myMemberId ? 'bg-primary text-white' : 'bg-slate-100 text-slate-500' }}">
                                                {{ substr($servant->member->churchPeople->full_name, 0, 1) }}
                                            </div>
                                            <span class="text-xs font-bold {{ $servant->member_id == $myMemberId ? 'text-primary' : 'text-slate-700' }}">
                                                {{ $servant->member->churchPeople->full_name }}
                                            </span>
                                        </div>
                                        <span class="text-[9px] font-black px-2 py-0.5 rounded {{ $servant->peran === 'Pembaca Firman' ? 'bg-amber-100 text-amber-600' : 'bg-slate-50 text-slate-400' }} uppercase tracking-tighter">
                                            {{ $servant->peran }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                            @if($item->status_setoran == 'disetor')
                                <div class="flex items-center gap-2 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Tuntas (Disetor)</span>
                                </div>
                            @else
                                <div class="flex flex-col flex-1 gap-3">
                                    @if($isPast && $item->status == 'rencana')
                                        <div class="p-2 bg-rose-50 text-rose-600 rounded-xl text-[9px] font-bold text-center border border-rose-100 italic">
                                            Kegiatan lampau belum diubah status ke 'Terlaksana'.
                                        </div>
                                    @endif
                                    
                                    <button wire:click="openCollectionModal({{ $item->id }})" class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-50 hover:border-primary/30 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $item->nominal_persembahan > 0 ? 'Update Kolekte' : 'Input Kolekte' }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center bg-white rounded-[50px] border-2 border-dashed border-slate-200 px-8">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-400 uppercase tracking-tighter">Tidak Ada Data</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">Daftar agenda dalam kategori ini masih kosong.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $schedules->links() }}
        </div>
    </div>

    <!-- MODAL INPUT KOLEKTE -->
    <div x-show="openModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6" style="display: none;">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
        
        <div class="relative bg-white w-full max-w-sm rounded-t-[40px] sm:rounded-[40px] shadow-2xl overflow-hidden animate-in slide-in-from-bottom duration-300">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>
            <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
            
            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-2">Input Kolekte</h3>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none">{{ $modalTitle }}</p>
                </div>

                <div class="space-y-6">
                    <div class="relative">
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-black text-lg">Rp</div>
                        <input type="text" 
                            wire:model="nominal_persembahan" 
                            class="w-full bg-slate-50 border-none rounded-[24px] py-6 pl-14 pr-6 font-mono font-black text-2xl text-slate-900 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-200" 
                            placeholder="0"
                            inputmode="numeric">
                    </div>
                    @error('nominal_persembahan') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest block text-center italic">{{ $message }}</span> @enderror

                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-2">
                        <p class="text-[9px] text-amber-700 font-bold leading-tight">
                            <span class="font-black">PENTING:</span> Mengisi kolekte akan otomatis mengubah status kegiatan menjadi "Terlaksana". Serahkan fisik uang ke Bendahara untuk status "Disetor".
                        </p>
                    </div>

                    <button wire:click="saveCollection" 
                            wire:loading.attr="disabled" 
                            class="w-full py-5 bg-emerald-600 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                        <span wire:loading.remove wire:target="saveCollection">Simpan & Konfirmasi</span>
                        <span wire:loading wire:target="saveCollection" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                    
                    <button @click="openModal = false" class="w-full text-xs font-black text-slate-300 hover:text-slate-500 uppercase tracking-widest transition-colors py-2">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 0px; }
    [x-cloak] { display: none !important; }
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    .animate-slide-up { animation: slideUp 0.3s ease-out; }
</style>
</div>
