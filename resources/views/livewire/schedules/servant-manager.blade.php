<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        formatRupiah(value) { 
            return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
        } 
     }">
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER & NAVIGASI -->
        <div class="mb-8">
            <a href="{{ route('schedules.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-primary transition-all uppercase tracking-widest mb-4 group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Agenda
            </a>
            
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-8 relative overflow-hidden">
                <div class="relative z-10 flex-1">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="px-3 py-1 bg-primary text-white text-[9px] font-black uppercase rounded-full tracking-widest shadow-sm">{{ $schedule->type->nama }}</span>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[9px] font-black uppercase rounded-full italic tracking-widest">{{ $schedule->tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 leading-none italic uppercase">{{ $schedule->tema ?? 'Ibadah Rutin' }}</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase text-xs tracking-widest flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $schedule->lokasi_display }} • Pkl {{ $schedule->jam_mulai->format('H:i') }} WITA
                    </p>
                </div>

                <!-- BOX INPUT KOLEKTE (KHUSUS PKS) -->
                @if($schedule->ref_activity_type_id == 2)
                <div class="w-full md:w-auto bg-blue-50/50 p-6 rounded-[32px] border border-blue-100 relative z-10">
                    <label class="block text-[9px] font-black text-primary uppercase tracking-widest mb-2 text-center">Persembahan Masuk</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/40 font-black text-sm">Rp</span>
                        <input type="text" wire:model="nominal_persembahan" x-on:input="$el.value = formatRupiah($el.value)" 
                               class="w-full pl-10 bg-white border-none rounded-2xl py-3 font-black text-primary text-xl shadow-sm focus:ring-2 focus:ring-primary/20 text-center" placeholder="0">
                    </div>
                    <button wire:click="saveCollection" class="mt-3 w-full py-3 bg-primary text-white rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                        Simpan Kolekte
                    </button>
                    <p class="mt-2 text-[8px] text-center uppercase tracking-tight font-bold text-blue-300">Status: {{ $schedule->status_setoran == 'disetor' ? 'SUDAH DISETOR' : 'PENDING DI MAJELIS' }}</p>
                </div>
                @endif
                
                <!-- Dekorasi -->
                <div class="absolute right-0 top-0 w-64 h-64 bg-slate-50 rounded-full translate-x-20 -translate-y-20 opacity-50 pointer-events-none"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: FORM INPUT TIM -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-2xl relative overflow-hidden sticky top-8">
                    <h2 class="text-xl font-black italic uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-amber-400 rounded-full"></span>
                        Input Pelayan
                    </h2>

                    <form wire:submit="addServant" class="space-y-6 relative z-10">
                        <!-- Pencarian Nama -->
                        <div class="relative" x-data="{ open: false }">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cari Jemaat</label>
                            
                            @if($selectedMemberName)
                                <div class="p-4 bg-white/10 border border-white/10 rounded-2xl flex justify-between items-center animate-in fade-in zoom-in-95">
                                    <span class="font-bold text-white text-sm">{{ $selectedMemberName }}</span>
                                    <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[9px] font-black uppercase text-amber-400 hover:text-amber-300 underline">Ganti</button>
                                </div>
                            @else
                                <input wire:model.live.debounce.300ms="searchMember" 
                                       @focus="open = true" 
                                       x-on:click.away="open = false"
                                       type="text" 
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 font-bold text-white text-sm focus:ring-2 focus:ring-amber-400/50 outline-none placeholder:text-slate-600 transition-all" 
                                       placeholder="Ketik nama...">
                                
                                @if(count($foundMembers) > 0)
                                <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl overflow-hidden divide-y divide-slate-100 text-slate-900">
                                    @foreach($foundMembers as $m)
                                    {{-- Menggunakan mousedown.prevent untuk memastikan klik terbaca sebelum blur --}}
                                    <button type="button" 
                                            wire:mousedown.prevent="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" 
                                            @mousedown="open = false"
                                            class="w-full text-left p-4 hover:bg-blue-50 transition-colors group">
                                        <p class="font-black text-xs group-hover:text-primary">{{ $m['nama'] }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">NIK: {{ $m['nik'] ?? '-' }}</p>
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                            @endif
                            @error('member_id') <span class="text-rose-400 text-[9px] font-bold mt-2 block uppercase tracking-widest">{{ $message }}</span> @enderror
                        </div>

                        <!-- Peran -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Peran Pelayanan</label>
                            <input wire:model="peran" type="text" list="roles-hint" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 font-bold text-white text-sm outline-none focus:ring-2 focus:ring-amber-400/50 uppercase placeholder:text-slate-600" placeholder="CONTOH: LITURGOS">
                            <datalist id="roles-hint">
                                <option value="Pengkhotbah">
                                <option value="Liturgos">
                                <option value="Pemusik">
                                <option value="Pemandu Pujian">
                                <option value="Kolektan">
                                <option value="Operator Slide">
                                <option value="Penerima Tamu">
                            </datalist>
                            @error('peran') <span class="text-rose-400 text-[9px] font-bold mt-2 block uppercase tracking-widest">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" wire:loading.attr="disabled" class="w-full py-4 bg-amber-400 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-amber-400/20 hover:scale-[1.02] transition-all active:scale-95 disabled:opacity-50">
                                <span wire:loading.remove>Tambahkan</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none rotate-12">
                        <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: DAFTAR TIM -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tim Pelayan Terdaftar</h3>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-black text-slate-600">{{ $schedule->servants->count() }} Orang</span>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($schedule->servants as $servant)
                        <div class="px-8 py-5 flex items-center justify-between group hover:bg-slate-50/50 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-primary border border-blue-100 flex items-center justify-center font-black text-lg shadow-sm">
                                    {{ substr($servant->member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 leading-none text-sm group-hover:text-primary transition-colors">{{ $servant->member->nama }}</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                        {{ $servant->peran }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(in_array(auth()->user()->role, ['admin', 'pendeta', 'sekretaris']))
                            <button wire:click="removeServant({{ $servant->id }})" 
                                    wire:confirm="Hapus {{ $servant->member->nama }} dari tim?"
                                    class="p-3 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all opacity-100 sm:opacity-0 group-hover:opacity-100" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                        </div>
                        @empty
                        <div class="py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
                            </div>
                            <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] italic">Belum ada tim yang ditugaskan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>