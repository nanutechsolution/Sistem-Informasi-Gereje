<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        formatRupiah(v) { 
            return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
        } 
     }">
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- BAGIAN 1: HEADER & KOLEKTE (PKS ONLY) -->
        <div class="mb-8">
            <a href="{{ route('schedules.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-primary transition-all uppercase tracking-widest mb-4 group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Agenda
            </a>
            
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden">
                <div class="flex-1">
                    <div class="flex gap-2 mb-3">
                        <span class="px-3 py-1 bg-primary text-white text-[9px] font-black uppercase rounded-full tracking-widest">{{ $schedule->type->nama }}</span>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[9px] font-black uppercase rounded-full italic tracking-widest">{{ $schedule->tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 leading-none italic uppercase">{{ $schedule->tema ?? 'Ibadah Rutin' }}</h1>
                    <p class="text-slate-400 mt-2 font-bold text-sm uppercase tracking-tighter">{{ $schedule->lokasi_display }} • Pkl {{ $schedule->jam_mulai->format('H:i') }} WITA</p>
                </div>

                <!-- BOX INPUT KOLEKTE (Hanya tampil jika agenda bertipe PKS) -->
                @if($schedule->ref_activity_type_id == 2)
                <div class="bg-blue-50 p-6 rounded-[32px] border border-blue-100 min-w-[250px] shadow-inner">
                    <p class="text-[9px] font-black text-primary uppercase tracking-widest mb-3 text-center">Persembahan Terkumpul</p>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/30 font-black text-lg">Rp</span>
                        <input type="text" 
                               wire:model="nominal_persembahan" 
                               x-on:input="$el.value = formatRupiah($el.value)" 
                               class="w-full pl-10 bg-white border-none rounded-2xl py-3 font-black text-primary text-xl shadow-sm focus:ring-2 focus:ring-primary/20 text-center">
                    </div>
                    <button wire:click="saveCollection" 
                            wire:loading.attr="disabled"
                            class="mt-3 w-full py-3 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                        <span wire:loading.remove>Update Nominal</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                    <p class="mt-2 text-[8px] text-blue-400 font-bold text-center uppercase tracking-tighter italic">* Status: {{ ucfirst($schedule->status_setoran) }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- BAGIAN 2: INPUT TIM PELAYAN (MODERN DARK CARD) -->
        <div class="bg-slate-900 rounded-[40px] p-8 mb-8 text-white shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-amber-400 rounded-full"></div>
                <h2 class="text-xl font-black italic uppercase tracking-tighter">Penugasan Pelayan</h2>
            </div>

            <form wire:submit="addServant" class="space-y-6 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pencarian Nama -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cari Nama Jemaat</label>
                        @if($selectedMemberName)
                            <div class="p-4 bg-white/10 border border-white/10 rounded-2xl flex justify-between items-center animate-in zoom-in-95 duration-200">
                                <span class="font-bold text-white">{{ $selectedMemberName }}</span>
                                <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] font-black uppercase text-amber-400 hover:text-amber-300 underline">Ganti</button>
                            </div>
                        @else
                            <input wire:model.live.debounce.300ms="searchMember" 
                                   @focus="open = true" 
                                   x-on:click.away="open = false"
                                   type="text" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 font-bold text-white focus:ring-2 focus:ring-amber-400/50 outline-none transition-all" 
                                   placeholder="Ketik minimal 3 huruf...">
                            
                            @if(count($foundMembers) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl overflow-hidden divide-y divide-slate-100 animate-in slide-in-from-top-2">
                                @foreach($foundMembers as $m)
                                <button type="button" 
                                        wire:mousedown.prevent="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" 
                                        @mousedown="open = false"
                                        class="w-full text-left p-4 hover:bg-blue-50 transition-colors">
                                    <p class="font-black text-slate-900 text-sm">{{ $m['nama'] }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">NIK: {{ $m['nik'] ?? 'N/A' }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        @endif
                        @error('member_id') <span class="text-rose-400 text-[10px] font-bold mt-1 block ml-1 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <!-- Peran / Jabatan Tugas -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Peran Pelayanan</label>
                        <div class="relative">
                            <input wire:model="peran" type="text" list="roles-hint" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 font-bold text-white outline-none focus:ring-2 focus:ring-amber-400/50 uppercase placeholder:text-slate-600" placeholder="MISAL: LITURGOS">
                            <datalist id="roles-hint">
                                <option value="Pengkhotbah">
                                <option value="Liturgos">
                                <option value="Pemusik">
                                <option value="Pemandu Pujian">
                                <option value="Kolektan">
                                <option value="Penerima Tamu">
                                <option value="Pelayan Firman (PKS)">
                            </datalist>
                        </div>
                        @error('peran') <span class="text-rose-400 text-[10px] font-bold mt-1 block ml-1 uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" wire:loading.attr="disabled" class="px-10 py-4 bg-amber-400 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-amber-500/20 hover:scale-105 transition-all active:scale-95 disabled:opacity-50">
                        <span wire:loading.remove>Daftarkan Pelayan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </form>
            
            <div class="absolute right-[-30px] bottom-[-30px] opacity-5 pointer-events-none rotate-12">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
        </div>

        <!-- BAGIAN 3: DAFTAR TIM TERPADU (CLEAN TABLE) -->
        <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden mb-12">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tim Pelayan Terjadwal</h3>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-black text-slate-400 uppercase">{{ $schedule->servants->count() }} Personil</span>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse($schedule->servants as $servant)
                <div class="px-8 py-6 flex items-center justify-between group hover:bg-slate-50/30 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-blue-50 text-primary border border-blue-100 flex items-center justify-center font-black text-lg shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                            {{ substr($servant->member->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 leading-none group-hover:text-primary transition-colors">{{ $servant->member->nama }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[9px] font-bold text-slate-500 uppercase tracking-widest border border-slate-200 group-hover:bg-blue-50 group-hover:border-blue-200 group-hover:text-blue-600 transition-all">{{ $servant->peran }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <button wire:click="removeServant({{ $servant->id }})" 
                            wire:confirm="Batalkan tugas pelayanan ini?"
                            class="p-3 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-2xl transition-all opacity-100 sm:opacity-0 group-hover:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @empty
                <div class="py-24 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
                    </div>
                    <p class="text-slate-300 font-black uppercase text-[10px] tracking-[0.3em] italic">Daftar tim masih kosong.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="text-center">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">Audit Sistem Keamanan SIG-GKS 2026-2046</p>
        </div>

    </div>
</div>