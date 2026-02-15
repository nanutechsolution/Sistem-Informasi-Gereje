<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('families.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Edit Keluarga</h1>
                    <p class="text-slate-500 mt-1 font-medium">No. KK: {{ $family->nomor_kk }}</p>
                </div>
                <div class="px-4 py-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status KK</span>
                    <div class="font-black text-lg uppercase {{ $status == 'aktif' ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $status }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <form wire:submit="update" class="bg-white rounded-[32px] p-6 sm:p-10 shadow-xl border border-slate-100 relative overflow-visible">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-amber-400 rounded-t-[32px]"></div>
                    
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6">Informasi Kartu Keluarga</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor KK (16 Digit)</label>
                                <input wire:model="nomor_kk" type="text" maxlength="16" class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-400/20 transition-all">
                                @error('nomor_kk') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah</label>
                                <div class="relative">
                                    <select wire:model="wilayah_id" class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-400/20 appearance-none transition-all cursor-pointer">
                                        @foreach($refWilayahs as $w)
                                            <option value="{{ $w->id }}">{{ $w->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-4 pointer-events-none text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Lengkap</label>
                            <textarea wire:model="alamat" rows="3" class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-400/20 transition-all resize-none"></textarea>
                            @error('alamat') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status KK</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach(['aktif', 'pindah', 'keluar', 'disiplin'] as $st)
                                    <label class="cursor-pointer group">
                                        <input type="radio" wire:model="status" value="{{ $st }}" class="peer sr-only">
                                        <div class="p-3 rounded-xl border-2 border-slate-100 bg-slate-50 hover:border-amber-400/30 peer-checked:border-amber-400 peer-checked:bg-amber-50 text-center transition-all">
                                            <span class="block font-black text-slate-600 peer-checked:text-amber-700 uppercase text-[10px] tracking-wider">{{ $st }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Keterangan (Opsional)</label>
                                <input wire:model="keterangan" type="text" class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-400/20 transition-all">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-50">
                            <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-[20px] font-black text-xs uppercase tracking-[0.2em] shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
                                <span wire:loading.remove>Simpan Perubahan</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] p-6 shadow-lg border border-slate-100 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Anggota Keluarga</h3>
                        <a href="{{ route('members.create', $family->uuid) }}" class="p-2 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 transition-colors" title="Tambah Anggota">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </a>
                    </div>

                    <div class="space-y-4 flex-1 overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
                        @forelse($family->members as $member)
                        <div class="relative group bg-slate-50 hover:bg-white border border-slate-100 hover:border-amber-200 hover:shadow-md rounded-2xl p-4 transition-all duration-200">
                            
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-500 font-black text-sm shrink-0 uppercase shadow-sm">
                                    {{ substr($member->churchPeople->full_name ?? '?', 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-black text-slate-800 text-sm truncate leading-tight">
                                        {{ $member->churchPeople->full_name ?? 'Nama Tidak Ditemukan' }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-200 text-[10px] font-bold text-slate-600 uppercase">
                                            {{ $member->refHubunganKeluarga->nama ?? '-' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            {{ $member->churchPeople->gender ?? '-' }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 truncate">
                                        NIK: {{ $member->churchPeople->nik ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200/50 flex justify-end gap-3">
                                <a href="{{ route('members.edit', $member->uuid) }}" class="text-[10px] font-bold text-amber-500 hover:text-amber-600 uppercase tracking-wider">
                                    Edit Status
                                </a>
                                <button wire:click="deleteMember('{{ $member->uuid }}')" 
                                        wire:confirm="Yakin ingin mengeluarkan {{ $member->churchPeople->full_name }} dari KK ini?"
                                        class="text-[10px] font-bold text-rose-400 hover:text-rose-600 uppercase tracking-wider">
                                    Hapus
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-400 font-medium">Belum ada anggota.</p>
                            <p class="text-[10px] text-slate-300 mt-1">Klik tombol + di atas.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>