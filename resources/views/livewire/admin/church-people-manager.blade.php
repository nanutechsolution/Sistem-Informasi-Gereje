<div class="min-h-screen bg-slate-50 pb-24" x-data="{ modal: @entangle('isOpen') }">
    <!-- 1. STICKY HEADER & SEARCH -->
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-4 py-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Data Jemaat</h1>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Master Database Jiwa</p>
                </div>
                <!-- Desktop Add Button -->
                <button wire:click="create" class="hidden sm:flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Jemaat Baru
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama atau NIK..." 
                    class="w-full bg-slate-100 border-none rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all shadow-inner">
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 mt-6">
        <!-- 2. MOBILE VIEW: CARD LIST (< sm) -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($people as $person)
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col gap-4 relative overflow-hidden group">
                <!-- Status Badge -->
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-lg">
                            {{ substr($person->full_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 leading-tight uppercase italic">{{ $person->full_name }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">NIK: {{ $person->nik ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase">{{ $person->gender }}</span>
                </div>

                <!-- Attributes -->
                <div class="flex gap-2 border-t border-slate-50 pt-4">
                    @if($person->is_baptized) 
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-xl text-[9px] font-black uppercase border border-blue-100">Baptis</span> 
                    @endif
                    @if($person->is_sidi) 
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase border border-emerald-100">Sidi</span> 
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button wire:click="edit({{ $person->id }})" class="flex-1 py-3 bg-slate-50 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Edit</button>
                    <button wire:click="delete({{ $person->id }})" wire:confirm="Hapus data ini?" class="px-4 py-3 bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="py-20 text-center text-slate-300 italic font-black uppercase text-xs tracking-widest">Data tidak ditemukan.</div>
            @endforelse
        </div>

        <!-- 3. DESKTOP VIEW: TABLE (>= sm) -->
        <div class="hidden sm:block bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Nama Jemaat</th>
                        <th class="px-6 py-5">L/P</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($people as $person)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center font-black group-hover:bg-primary group-hover:text-white transition-colors">
                                    {{ substr($person->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 uppercase italic">{{ $person->full_name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400">{{ $person->nik ?? 'NIK Belum Diisi' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-black text-xs text-slate-400">{{ $person->gender }}</td>
                        <td class="px-6 py-5">
                            <div class="flex gap-2">
                                @if($person->is_baptized) <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[8px] font-black uppercase">Baptis</span> @endif
                                @if($person->is_sidi) <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[8px] font-black uppercase">Sidi</span> @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button wire:click="edit({{ $person->id }})" class="p-2 text-slate-300 hover:text-primary transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5"/></svg></button>
                            <button wire:click="delete({{ $person->id }})" wire:confirm="Hapus data?" class="p-2 text-slate-300 hover:text-rose-500 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"/></svg></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-6 bg-slate-50/30">
                {{ $people->links() }}
            </div>
        </div>
    </div>

    <!-- 4. FLOATING ACTION BUTTON (MOBILE ONLY) -->
    <button wire:click="create" class="sm:hidden fixed bottom-8 right-6 w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center z-50 animate-bounce active:scale-90 transition-transform">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
    </button>

    <!-- 5. MODERN MODAL FORM -->
    <div x-show="modal" x-cloak class="fixed inset-0 z-[150] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="modal = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[50px] p-8 sm:p-12 shadow-2xl overflow-hidden animate-in slide-in-from-bottom sm:zoom-in-95 duration-300 text-left">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 italic uppercase tracking-tighter leading-none">{{ $editId ? 'Perbarui' : 'Tambah' }} Jemaat</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Lengkapi data pokok kependudukan</p>
                    </div>
                    <button @click="modal = false" class="p-2 bg-slate-100 rounded-full text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-full">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input wire:model="full_name" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/10 shadow-inner" placeholder="Masukkan nama jemaat...">
                        @error('full_name') <span class="text-rose-500 text-[9px] font-bold mt-1 uppercase ml-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">NIK (KTP)</label>
                        <input wire:model="nik" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner">
                        @error('nik') <span class="text-rose-500 text-[9px] font-bold mt-1 uppercase ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Kelamin</label>
                        <select wire:model="gender" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner appearance-none cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tempat Lahir</label>
                        <input wire:model="place_of_birth" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Lahir</label>
                        <input wire:model="date_of_birth" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner">
                    </div>

                    <!-- FIX: Menggunakan wire:click untuk toggle status sakramen -->
                    <div class="col-span-full grid grid-cols-2 gap-4 mt-2">
                        <button type="button" 
                                wire:click="$toggle('is_baptized')"
                                class="flex flex-col items-center justify-center p-4 rounded-3xl border-2 transition-all cursor-pointer group {{ $is_baptized ? 'border-primary bg-blue-50' : 'bg-slate-50 border-transparent' }}">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center mb-2 {{ $is_baptized ? 'bg-primary text-white shadow-lg' : 'bg-slate-200 text-slate-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $is_baptized ? 'text-primary' : 'text-slate-400' }}">Sudah Baptis</span>
                        </button>

                        <button type="button" 
                                wire:click="$toggle('is_sidi')"
                                class="flex flex-col items-center justify-center p-4 rounded-3xl border-2 transition-all cursor-pointer group {{ $is_sidi ? 'border-primary bg-blue-50' : 'bg-slate-50 border-transparent' }}">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center mb-2 {{ $is_sidi ? 'bg-primary text-white shadow-lg' : 'bg-slate-200 text-slate-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $is_sidi ? 'text-primary' : 'text-slate-400' }}">Sudah Sidi</span>
                        </button>
                    </div>

                    <div class="col-span-full flex gap-3 mt-6">
                        <button wire:taget="save" type="button" @click="modal = false" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-400 tracking-widest active:scale-95 transition-all">Batal</button>
                        <button wire:taget="save" type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl hover:bg-primary active:scale-95 transition-all disabled:opacity-50">
                            <span wire:taget="save" wire:loading.remove>Simpan Perubahan</span>
                            <span wire:taget="save" wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>