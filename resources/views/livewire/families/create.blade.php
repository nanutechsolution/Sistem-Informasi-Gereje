<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <a href="{{ route('families.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">KK Baru</h1>
            <p class="text-slate-500 mt-2 font-medium">Registrasi Nomor Kartu Keluarga (KK) baru.</p>
        </div>

        <form wire:submit="save" class="bg-white rounded-[40px] p-8 sm:p-12 shadow-xl border border-slate-100 relative overflow-visible">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary rounded-t-[40px]"></div>

            <div class="space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor KK (16 Digit)</label>
                        <input wire:model="nomor_kk" type="text" maxlength="16" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all placeholder:text-slate-300" placeholder="Contoh: 7371...">
                        @error('nomor_kk') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Wilayah Pelayanan</label>
                        <div class="relative">
                            <select wire:model="wilayah_id" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all appearance-none">
                                <option value="">Pilih Wilayah</option>
                                @foreach($refWilayahs as $w)
                                    <option value="{{ $w->id }}">{{ $w->nama }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-4 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('wilayah_id') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Lengkap</label>
                    <textarea wire:model="alamat" rows="3" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all resize-none"></textarea>
                    @error('alamat') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status KK</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['aktif', 'pindah', 'keluar', 'disiplin'] as $st)
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model="status" value="{{ $st }}" class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-slate-100 bg-slate-50 hover:border-primary/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                                    <span class="block font-black text-slate-600 peer-checked:text-primary uppercase text-[10px] tracking-wider">{{ $st }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('status') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Keterangan (Opsional)</label>
                        <input wire:model="keterangan" type="text" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.01] transition-transform active:scale-[0.98]">
                        <span wire:loading.remove>Simpan Data Keluarga</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>