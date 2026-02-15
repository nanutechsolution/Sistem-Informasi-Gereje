<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none">Tim Pelayanan</h1>
                <p class="text-slate-500 mt-2 font-medium text-xs uppercase tracking-widest border-l-4 border-primary pl-3">
                    Manajemen Kelompok Majelis & Pelayan
                </p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button wire:click="create" wire:loading.attr="disabled" wire:target="create" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-[24px] font-black text-[10px] shadow-xl hover:scale-105 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="create" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Kelompok Baru
                    </span>
                    <span wire:loading wire:target="create" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memuat...
                    </span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-slate-400" placeholder="Cari Nama Kelompok...">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <div wire:loading wire:target="search" class="absolute right-4 top-3.5">
                    <svg class="animate-spin h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Grid Kelompok -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col relative overflow-hidden">
                <!-- Header Card -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kelompok</p>
                        <h3 class="text-xl font-black text-slate-900 uppercase leading-tight">{{ $group->nama_kelompok }}</h3>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[9px] font-bold uppercase tracking-wider">
                        {{ $group->wilayah->nama ?? 'Umum' }}
                    </span>
                </div>

                <!-- Anggota Preview -->
                <div class="flex-1 space-y-3 mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[9px] font-black text-primary uppercase bg-blue-50 px-2 py-1 rounded">Ketua / PF</span>
                        @php
                            $pf = $group->officers->first(fn($o) => $o->pivot->peran_default === 'Pembaca Firman' || $o->pivot->peran_default === 'Ketua');
                        @endphp
                        <span class="text-xs font-bold text-slate-700 truncate">
                            {{ $pf->member->churchPeople->full_name ?? 'Belum Ditentukan' }}
                        </span>
                    </div>
                    
                    <div class="pl-1 border-l-2 border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-2 pl-2">Anggota Tim ({{ $group->officers->count() }})</p>
                        <div class="flex flex-wrap gap-1 pl-2">
                            @foreach($group->officers->take(5) as $off)
                                <div class="w-6 h-6 rounded-full bg-slate-100 border border-white shadow-sm flex items-center justify-center text-[8px] font-black text-slate-500 uppercase" title="{{ $off->member->churchPeople->full_name }}">
                                    {{ substr($off->member->churchPeople->full_name, 0, 1) }}
                                </div>
                            @endforeach
                            @if($group->officers->count() > 5)
                                <span class="text-[9px] text-slate-400 self-center ml-1">+{{ $group->officers->count() - 5 }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="mt-auto border-t border-slate-50 pt-4 flex justify-between items-center opacity-40 group-hover:opacity-100 transition-opacity">
                    <span class="text-[10px] font-bold text-slate-300 uppercase">ID: {{ substr($group->uuid, 0, 8) }}</span>
                    <div class="flex gap-2">
                        <button wire:click="edit('{{ $group->uuid }}')" class="p-2 text-amber-400 hover:bg-amber-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button wire:click="delete('{{ $group->uuid }}')" wire:confirm="Hapus kelompok ini?" wire:loading.attr="disabled" class="p-2 text-rose-400 hover:bg-rose-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-400 font-bold text-sm">Belum ada kelompok pelayanan.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-8">{{ $groups->links() }}</div>
    </div>

    <!-- MODAL INPUT KELOMPOK -->
    <div x-show="$wire.isModalOpen" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 sm:p-6" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="$set('isModalOpen', false)"></div>
        
        <div class="relative bg-white w-full max-w-2xl rounded-t-[40px] sm:rounded-[40px] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden animate-in slide-in-from-bottom duration-300">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
            
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Form Kelompok</h3>
                    <p class="text-slate-500 text-xs font-medium uppercase tracking-widest mt-1">Atur anggota dan peran tim</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-rose-100 hover:text-rose-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">
                
                <!-- Nama & Wilayah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Kelompok</label>
                        <input wire:model="nama_kelompok" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-300" placeholder="Cth: Kelompok Ester">
                        @error('nama_kelompok') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah Pelayanan</label>
                        <select wire:model="ref_wilayah_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none">
                            <option value="">-- Umum / Lintas Wilayah --</option>
                            @foreach($wilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <!-- Anggota Tim -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Anggota Tim</label>
                        <div class="relative w-48">
                            <input wire:model.live.debounce.300ms="searchOfficer" type="text" class="w-full bg-slate-50 border-none rounded-xl py-2 pl-8 pr-3 text-xs font-bold" placeholder="Cari nama...">
                            <svg class="w-3 h-3 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <div wire:loading wire:target="searchOfficer" class="absolute right-3 top-2.5">
                                <svg class="animate-spin h-3 w-3 text-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-3xl p-2 border border-slate-100 max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                        @foreach($allOfficers as $off)
                            @php
                                $isSelected = in_array($off->id, $selectedOfficers);
                                $isAvailable = !$off->current_group || $off->current_group->id == $editId;
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-2xl transition-all {{ $isSelected ? 'bg-white shadow-sm border border-primary/20' : ($isAvailable ? 'hover:bg-white hover:shadow-sm' : 'opacity-50 grayscale') }}">
                                <label class="flex items-center gap-3 cursor-pointer flex-1">
                                    <input type="checkbox" wire:model.live="selectedOfficers" value="{{ $off->id }}" 
                                           wire:change="toggleOfficer({{ $off->id }})"
                                           class="rounded-lg border-slate-300 text-primary focus:ring-primary/20 w-5 h-5"
                                           {{ !$isAvailable ? 'disabled' : '' }}>
                                    <div>
                                        <p class="font-bold text-xs text-slate-800 {{ !$isAvailable ? 'line-through' : '' }}">{{ $off->member->churchPeople->full_name }}</p>
                                        <p class="text-[9px] text-slate-400 uppercase">{{ $off->position->nama }}</p>
                                    </div>
                                </label>
                                
                                <!-- Role Selector (Muncul jika dipilih) -->
                                @if($isSelected)
                                    <select wire:model="defaultRoles.{{ $off->id }}" class="bg-slate-100 border-none rounded-lg text-[10px] font-bold text-slate-600 py-1 pl-2 pr-6 focus:ring-0">
                                        <option value="Pendamping">Pendamping</option>
                                        <option value="Pembaca Firman">Pembaca Firman</option>
                                        <option value="Ketua">Ketua</option>
                                    </select>
                                @elseif(!$isAvailable)
                                    <span class="text-[9px] font-bold text-rose-400 bg-rose-50 px-2 py-1 rounded">Di {{ $off->current_group->nama_kelompok }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('selectedOfficers') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="p-8 border-t border-slate-50 bg-slate-50/50 flex gap-4">
                <button wire:click="$set('isModalOpen', false)" class="flex-1 py-4 bg-white border border-slate-200 rounded-[20px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-colors">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="flex-[2] py-4 bg-slate-900 text-white rounded-[20px] font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-primary transition-colors flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Kelompok</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

</div>
