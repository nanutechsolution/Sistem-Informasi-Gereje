<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Kelompok Majelis</h1>
                <p class="text-slate-500 mt-3 font-medium">Atur grup pelayanan tetap untuk mempercepat pembuatan jadwal PKS.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all">
                + BUAT KELOMPOK BARU
            </button>
        </div>

        <!-- SEARCH -->
        <div class="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 mb-8">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-primary/10" placeholder="Cari nama kelompok...">
            </div>
        </div>

        <!-- GROUP CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($groups as $group)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm hover:shadow-xl transition-all group flex flex-col relative overflow-hidden">
                <!-- Header Card -->
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase rounded-full tracking-widest italic border border-slate-200">
                        {{ $group->wilayah->nama ?? 'Semua Wilayah' }}
                    </span>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $group->id }})" class="p-2 text-slate-300 hover:text-primary transition-colors bg-white rounded-xl shadow-sm border border-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button wire:click="delete({{ $group->id }})" wire:confirm="Hapus kelompok ini?" class="p-2 text-slate-300 hover:text-rose-500 transition-colors bg-white rounded-xl shadow-sm border border-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-6 italic uppercase leading-none relative z-10">{{ $group->nama_kelompok }}</h3>
                
                <!-- List Anggota -->
                <div class="flex-1 space-y-4 relative z-10 bg-slate-50/50 rounded-3xl p-5 border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Personil Terdaftar ({{ $group->officers->count() }})</p>
                    <div class="max-h-48 overflow-y-auto pr-2 custom-scrollbar space-y-3">
                        @foreach($group->officers as $officer)
                        <div class="flex items-center gap-3">
                            <!-- Avatar dengan indikator peran -->
                            <div class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center text-[10px] font-black shadow-sm 
                                {{ $officer->pivot->peran_default == 'Pembaca Firman' ? 'bg-slate-900 text-white' : 'bg-white text-primary' }}">
                                {{ substr($officer->member->nama, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-700 truncate leading-none">{{ $officer->member->nama }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase mt-1 tracking-wider flex items-center gap-1">
                                    {{ $officer->position->nama }} 
                                    @if($officer->pivot->peran_default == 'Pembaca Firman')
                                        <span class="text-amber-500">• Ketua Tim</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="absolute right-[-20px] top-[-20px] w-32 h-32 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center border-2 border-dashed border-slate-200 rounded-[40px]">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Belum ada kelompok dibentuk.</p>
            </div>
            @endforelse
        </div>

        <!-- MODAL FORM -->
        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="$set('isModalOpen', false)"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl transition-all">
                    
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter">Pengaturan Kelompok</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Buat template tim untuk jadwal rutin</p>
                        </div>
                        <button @click="$set('isModalOpen', false)" class="p-2 bg-slate-100 rounded-full hover:bg-slate-200"><svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <form wire:submit="save" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Kelompok</label>
                                <input wire:model="nama_kelompok" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-4 focus:ring-primary/10" placeholder="Misal: Kelompok A">
                                @error('nama_kelompok') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah (Opsional)</label>
                                <select wire:model="ref_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold appearance-none focus:ring-4 focus:ring-primary/10">
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Pilih Anggota Tim (Ceklis & Atur Peran)</label>
                            
                            @if($allOfficers->isEmpty())
                                <div class="p-6 bg-rose-50 rounded-2xl border border-rose-100 text-center">
                                    <p class="text-xs font-bold text-rose-600">Belum ada data personil aktif.</p>
                                    <a href="{{ route('officers.create') }}" class="text-[10px] font-black text-rose-800 underline mt-1 block">Tambah Personil Dulu</a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 gap-3 max-h-64 overflow-y-auto p-2 pr-4 custom-scrollbar">
                                    @foreach($allOfficers as $off)
                                    @php
                                        // Cek apakah dia sudah punya grup lain (selain grup ini yg sedang diedit)
                                        $hasOtherGroup = $off->current_group && $off->current_group->id != $editId;
                                    @endphp

                                    <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-200 transition-all {{ in_array($off->id, $selectedOfficers) ? 'border-primary ring-1 ring-primary bg-blue-50/30' : '' }} {{ $hasOtherGroup ? 'opacity-50 grayscale' : '' }}">
                                        
                                        <!-- Checkbox Selection -->
                                        <label class="flex items-center cursor-pointer flex-1">
                                            <input type="checkbox" wire:model.live="selectedOfficers" value="{{ $off->id }}" 
                                                   class="h-5 w-5 text-primary rounded border-slate-300 focus:ring-primary" 
                                                   {{ $hasOtherGroup ? 'disabled' : '' }}>
                                            <div class="ml-3 min-w-0">
                                                <p class="text-xs font-black text-slate-900 leading-none">{{ $off->member->nama }}</p>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-tighter">
                                                    {{ $off->position->nama }} 
                                                    @if($hasOtherGroup) 
                                                        <span class="text-rose-500 font-black ml-1">(Sudah di {{ $off->current_group->nama_kelompok }})</span> 
                                                    @endif
                                                </p>
                                            </div>
                                        </label>

                                        <!-- Role Selector (Muncul jika dicentang) -->
                                        @if(in_array($off->id, $selectedOfficers))
                                        <select wire:model="defaultRoles.{{ $off->id }}" class="ml-2 w-32 bg-white border border-slate-200 text-[10px] font-bold text-slate-600 rounded-lg py-1 px-2 focus:ring-0 focus:border-primary">
                                            <option value="Pendamping">Anggota</option>
                                            <option value="Pembaca Firman">Ketua / PF</option>
                                        </select>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @error('selectedOfficers') <span class="text-rose-500 text-[10px] font-bold mt-2 block uppercase">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-slate-100">
                            <button type="button" @click="$set('isModalOpen', false)" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl hover:bg-blue-800 transition transform active:scale-95">Simpan Kelompok</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>