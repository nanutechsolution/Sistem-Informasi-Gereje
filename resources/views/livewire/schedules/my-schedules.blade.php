<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ formatRupiah(v) { return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 italic uppercase leading-none">Tugas Saya</h1>
                <p class="text-slate-500 mt-2 font-medium">Daftar pelayanan dan tanggung jawab kolekte.</p>
            </div>
            
            <!-- Avatar User -->
            <div class="h-12 w-12 rounded-2xl bg-slate-200 flex items-center justify-center font-black text-slate-500 text-lg">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>

        <!-- List Jadwal -->
        <div class="space-y-6">
            @forelse($schedules as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
                
                <!-- Tanggal & Badge -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-900 text-white rounded-2xl h-14 w-14 flex flex-col items-center justify-center leading-none shadow-lg">
                            <span class="text-xl font-black">{{ $item->tanggal->format('d') }}</span>
                            <span class="text-[9px] font-bold uppercase">{{ $item->tanggal->format('M') }}</span>
                        </div>
                        <div>
                            <span class="px-3 py-1 bg-blue-50 text-primary text-[9px] font-black uppercase rounded-full border border-blue-100 tracking-widest block w-fit mb-1">
                                {{ $item->type->nama }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                Pukul {{ $item->jam_mulai->format('H:i') }} WITA
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Konten Utama -->
                <div class="mb-8">
                    @if($item->family)
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tuan Rumah</p>
                        <h3 class="text-2xl font-black text-slate-900 leading-tight uppercase italic">{{ $item->family->kepala_keluarga }}</h3>
                        <p class="text-xs font-bold text-slate-500 mt-1">{{ $item->wilayah->nama ?? 'Wilayah -' }}</p>
                    @else
                        <h3 class="text-2xl font-black text-slate-900 leading-tight">{{ $item->tema ?? 'Ibadah Rutin' }}</h3>
                        <p class="text-sm font-bold text-slate-500 mt-1">{{ $item->lokasi_manual ?? 'Gedung Gereja' }}</p>
                    @endif
                </div>

                <!-- Peran & Aksi -->
                <div class="bg-slate-50 rounded-3xl p-5 border border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tugas Anda</p>
                        <p class="text-lg font-black text-accent uppercase italic">
                            {{ $item->servants->where('member_id', auth()->user()->member_id)->first()->peran ?? 'Anggota Tim' }}
                        </p>
                    </div>

                    <!-- TOMBOL INPUT KOLEKTE (Hanya untuk PKS) -->
                    @if(Str::contains(strtolower($item->type->nama), 'pks'))
                        <div class="w-full sm:w-auto">
                            @if($item->status_setoran == 'disetor')
                                <button disabled class="w-full px-6 py-3 bg-emerald-100 text-emerald-700 rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed opacity-70">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Sudah Disetor
                                </button>
                            @else
                                <button wire:click="openCollectionModal({{ $item->id }})" class="w-full px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Input Kolekte
                                </button>
                                @if($item->nominal_persembahan > 0)
                                    <p class="text-[9px] text-center text-slate-400 font-bold mt-2 uppercase tracking-wide">Tercatat: Rp {{ number_format($item->nominal_persembahan, 0, ',', '.') }}</p>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-24 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest italic">Belum ada jadwal tugas.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $schedules->links() }}
        </div>

        <!-- MODAL INPUT KOLEKTE -->
        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="isModalOpen = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-md bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl transition-all">
                    
                    <div class="text-center mb-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Laporan Ibadah</p>
                        <h2 class="text-2xl font-black text-slate-900 italic uppercase leading-none">{{ $modalTitle }}</h2>
                    </div>
                    
                    <form wire:submit="saveCollection" class="space-y-6">
                        <div class="bg-blue-50 rounded-[32px] p-6 border border-blue-100">
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-3 text-center">Total Persembahan (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-xl font-black text-primary/30">Rp</span>
                                <input type="text" wire:model="nominal_persembahan" x-on:input="$el.value = formatRupiah($el.value)" 
                                       class="w-full bg-white border-none rounded-2xl py-4 pl-14 pr-6 text-center font-black text-3xl text-slate-900 focus:ring-4 focus:ring-primary/20 shadow-sm" 
                                       placeholder="0">
                            </div>
                        </div>

                        <div class="pt-2 flex gap-4">
                            <button type="button" @click="isModalOpen = false" class="flex-1 py-5 bg-slate-100 rounded-[24px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[24px] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">
                                Simpan & Lapor
                            </button>
                        </div>
                    </form>
                    
                    <p class="mt-6 text-[9px] text-center text-slate-400 font-bold uppercase tracking-widest mx-auto w-2/3 leading-relaxed">
                        Data akan berstatus "Pending" sampai uang fisik disetor ke Bendahara Jemaat.
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>