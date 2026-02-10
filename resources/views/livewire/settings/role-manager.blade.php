<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Struktur Jabatan</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Pengaturan Hak Akses Aplikasi</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-xl hover:bg-primary transition-all active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                TAMBAH JABATAN BARU
            </button>
        </div>

        <!-- SEARCH -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari nama jabatan (bendahara, sekretaris...)...">
            </div>
        </div>

        <!-- GRID ROLE -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($roles as $role)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full relative overflow-hidden">
                <div class="flex justify-between items-start mb-6 z-10">
                    <div class="h-12 w-12 rounded-2xl bg-blue-50 text-primary flex items-center justify-center font-black text-xl group-hover:bg-primary group-hover:text-white transition-colors">
                        {{ substr($role->name, 0, 1) }}
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $role->id }})" class="p-3 bg-slate-50 text-slate-400 hover:text-primary rounded-xl transition-all shadow-sm cursor-pointer" title="Ubah Izin">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        @if($role->name !== 'admin')
                        <button wire:click="delete({{ $role->id }})" wire:confirm="Hapus jabatan ini? Semua pengguna dengan jabatan ini akan kehilangan akses." class="p-3 bg-slate-50 text-slate-300 hover:text-rose-500 rounded-xl transition-all shadow-sm cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </div>

                <h3 class="text-2xl font-black text-slate-900 leading-none uppercase italic mb-4 z-10">{{ $role->name }}</h3>
                
                <div class="flex-1 space-y-2 z-10">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kewenangan Sistem:</p>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @forelse($role->permissions as $p)
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-[9px] font-black uppercase tracking-tighter">
                                {{ str_replace('_', ' ', $p->name) }}
                            </span>
                        @empty
                            <span class="text-[10px] text-slate-400 italic">Belum ada izin khusus.</span>
                        @endforelse
                    </div>
                </div>
                
                <!-- Background Decoration -->
                <div class="absolute -right-6 -bottom-6 opacity-5 pointer-events-none group-hover:scale-110 transition-transform duration-700 text-primary">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/></svg>
                </div>
            </div>
            @endforeach
        </div>

        <!-- MODAL KONFIGURASI -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" @click="$wire.closeModal()"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
                <div class="relative w-full max-w-3xl transform overflow-hidden bg-white rounded-t-[40px] sm:rounded-[50px] p-8 sm:p-12 text-left shadow-2xl transition-all animate-in slide-in-from-bottom sm:zoom-in duration-300">
                    
                    <h3 class="text-3xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Setting Jabatan</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-10 border-b border-slate-50 pb-4">Tentukan nama dan cakupan izin akses.</p>
                    
                    <form wire:submit="save" class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Jabatan (Tanpa Spasi)</label>
                            <input wire:model="name" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black text-lg focus:ring-4 focus:ring-primary/10 placeholder:text-slate-300 transition-all shadow-inner" placeholder="contoh: bendahara_pembangunan">
                            @error('name') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-2 uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 ml-1">Cakupan Izin Akses (Pilih Kelompok)</label>
                            
                            <div class="space-y-8 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                                {{-- FIX: Cek jika variabel $groupedPermissions tersedia untuk mencegah error --}}
                                @if(isset($groupedPermissions) && count($groupedPermissions) > 0)
                                    @foreach($groupedPermissions as $groupName => $perms)
                                    @if($perms->isNotEmpty())
                                    <div>
                                        <h4 class="text-[10px] font-black text-primary uppercase bg-blue-50 px-3 py-1.5 rounded-lg inline-block mb-4 tracking-widest shadow-sm">{{ $groupName }}</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($perms as $perm)
                                            <label class="flex items-center p-4 bg-slate-50 rounded-2xl border-2 transition-all cursor-pointer group {{ in_array($perm->name, $selectedPermissions) ? 'border-primary bg-blue-50/50 shadow-inner' : 'border-transparent' }}">
                                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" class="h-5 w-5 rounded-lg border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all">
                                                <div class="ml-4">
                                                    <span class="block text-xs font-black text-slate-700 uppercase tracking-tight group-hover:text-primary transition-colors leading-none">{{ str_replace('_', ' ', $perm->name) }}</span>
                                                    <span class="block text-[9px] text-slate-400 font-bold italic leading-tight mt-1">
                                                        @php
                                                            $desc = match($perm->name) {
                                                                'manage_finance' => 'Bisa input kas dan lelang',
                                                                'approve_transaction' => 'Bisa memverifikasi setoran PKS',
                                                                'manage_database' => 'Bisa edit data jemaat/keluarga',
                                                                'manage_schedules' => 'Bisa mengatur jadwal ibadah',
                                                                'view_reports' => 'Bisa melihat laporan keuangan',
                                                                'manage_users' => 'Bisa mengelola akun login',
                                                                'manage_budget' => 'Bisa mengatur angka RAPB',
                                                                'manage_settings' => 'Bisa mengubah master data wilayah',
                                                                default => 'Akses fitur sistem terkait'
                                                            };
                                                        @endphp
                                                        {{ $desc }}
                                                    </span>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                @else
                                    <div class="p-8 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest animate-pulse">Menghubungkan Database Izin...</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-6 flex gap-4 border-t border-slate-50">
                            {{-- FIX: Tombol Batal kini memanggil method closeModal di Backend --}}
                            <button type="button" wire:click="closeModal" class="flex-1 py-5 bg-slate-100 rounded-[32px] font-black text-[10px] uppercase tracking-[0.2em] text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition-all cursor-pointer">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-[32px] font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95 disabled:opacity-70">
                                <span wire:loading.remove italic>Simpan Konfigurasi</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>