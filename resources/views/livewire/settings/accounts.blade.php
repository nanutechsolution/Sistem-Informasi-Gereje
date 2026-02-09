<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isOpen') }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">Manajemen Dompet</h1>
                <p class="text-slate-500 mt-2 font-medium">Kelola saldo Kas Tunai dan Rekening Bank jemaat secara terpisah.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                TAMBAH DOMPET
            </button>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white rounded-[24px] p-2 shadow-sm border border-slate-100 mb-8 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
            <div class="flex items-center px-4">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full border-none bg-transparent p-4 font-bold text-sm focus:ring-0 placeholder-slate-300" placeholder="Cari nama dompet atau bank...">
            </div>
        </div>

        <!-- ACCOUNTS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($accounts as $acc)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-primary/20 transition-all group relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 rounded-2xl {{ $acc->jenis == 'bank' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                            @if($acc->jenis == 'bank')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $acc->id }})" class="p-3 bg-slate-50 text-slate-400 hover:text-primary hover:bg-blue-50 rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </div>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 leading-tight mb-1">{{ $acc->nama }}</h3>
                    <div class="flex items-center gap-2">
                        <button wire:click="toggleStatus({{ $acc->id }})" class="text-[10px] font-black uppercase tracking-widest transition-colors {{ $acc->is_active ? 'text-emerald-500 hover:text-emerald-600' : 'text-slate-300 hover:text-slate-400' }}">
                            {{ $acc->is_active ? '● Aktif' : '○ Non-Aktif' }}
                        </button>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">|</span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            {{ $acc->jenis == 'bank' ? 'Rekening Bank' : 'Kas Tunai' }}
                        </span>
                    </div>
                    
                    @if($acc->nomor_rekening)
                    <div class="mt-4 inline-block px-4 py-2 bg-slate-50 rounded-xl font-mono text-xs font-bold text-slate-500 border border-slate-100 italic">
                        {{ $acc->nomor_rekening }}
                    </div>
                    @endif
                </div>
                
                <div class="absolute -right-6 -bottom-6 text-slate-50 group-hover:text-primary/5 transition-colors duration-700 pointer-events-none">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 002-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 py-24 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <h3 class="text-xl font-black text-slate-800 italic uppercase tracking-widest">Belum Ada Dompet</h3>
                <p class="text-slate-400 text-sm mt-2 font-medium">Klik tombol tambah untuk mendaftarkan Kas atau Bank.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $accounts->links() }}
        </div>

        <!-- MODAL FORM -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
                <div class="relative w-full max-w-lg transform overflow-hidden bg-white rounded-t-[40px] sm:rounded-[40px] p-8 text-left shadow-2xl transition-all">
                    
                    <div class="absolute top-0 left-0 h-1.5 w-full bg-slate-100">
                        <div class="h-full bg-primary w-2/3 animate-pulse"></div>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mb-2 leading-none italic">{{ $accountId ? 'Ubah' : 'Buat' }} Dompet Jemaat</h3>
                    <p class="text-sm text-slate-400 mb-8 font-medium">Informasi ini penting untuk sinkronisasi Buku Kas.</p>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Jenis Penyimpanan</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="group cursor-pointer">
                                    <input type="radio" wire:model.live="jenis" value="kas_tunai" class="peer sr-only">
                                    <div class="p-5 text-center rounded-3xl border-2 border-slate-50 bg-slate-50 font-black text-slate-400 peer-checked:border-primary peer-checked:bg-blue-50 peer-checked:text-primary transition-all">
                                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span class="text-[10px] uppercase">Kas Tunai</span>
                                    </div>
                                </label>
                                <label class="group cursor-pointer">
                                    <input type="radio" wire:model.live="jenis" value="bank" class="peer sr-only">
                                    <div class="p-5 text-center rounded-3xl border-2 border-slate-50 bg-slate-50 font-black text-slate-400 peer-checked:border-primary peer-checked:bg-blue-50 peer-checked:text-primary transition-all">
                                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                                        <span class="text-[10px] uppercase">Bank</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Dompet / Rekening</label>
                            <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-300" placeholder="Contoh: Kas Jemaat (Umum)">
                            @error('nama') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span> @enderror
                        </div>

                        @if($jenis == 'bank')
                        <div class="animate-in slide-in-from-top-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Nomor Rekening & Nama Bank</label>
                            <input wire:model="nomor_rekening" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="BRI: 0012-xxx-xxx">
                            @error('nomor_rekening') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-5 bg-slate-50 rounded-3xl">
                            <div>
                                <span class="block text-sm font-black text-slate-900">Aktifkan Dompet?</span>
                                <span class="text-[10px] text-slate-400 font-medium italic">Hanya dompet aktif yang muncul di transaksi.</span>
                            </div>
                            <button type="button" wire:click="$toggle('is_active')" class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus:outline-none {{ $is_active ? 'bg-primary' : 'bg-slate-300' }}">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="openModal = false" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">
                                <span wire:loading.remove>Simpan Dompet</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>