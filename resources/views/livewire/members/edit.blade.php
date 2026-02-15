<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <div class="mb-8">
            <a href="{{ route('families.edit', $member->family->uuid) }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-black text-slate-900 uppercase">Edit Keanggotaan</h1>
        </div>

        <form wire:submit="update" class="bg-white rounded-[40px] p-6 sm:p-10 shadow-xl border border-slate-100 relative overflow-visible">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-amber-400 rounded-t-[40px]"></div>

            <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Anggota</label>
                <p class="font-black text-slate-800 text-lg">{{ $personName }}</p>
                <p class="text-[10px] text-slate-400 mt-1 italic">
                    *Untuk mengubah nama/tgl lahir, silakan edit di menu "Master Data Orang".
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Hubungan</label>
                    <div class="relative">
                        <select wire:model="hubungan_keluarga_id" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-amber-400 appearance-none">
                            @foreach($refHubungans as $hub)
                                <option value="{{ $hub->id }}">{{ $hub->nama }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pekerjaan</label>
                    <div class="relative">
                        <select wire:model="pekerjaan_id" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-amber-400 appearance-none">
                            @foreach($refPekerjaans as $job)
                                <option value="{{ $job->id }}">{{ $job->nama }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Keanggotaan</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['aktif', 'pindah', 'meninggal'] as $st)
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model="status_keanggotaan" value="{{ $st }}" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:border-amber-400 peer-checked:bg-amber-50 text-center transition-all">
                                <span class="block font-black text-slate-600 peer-checked:text-amber-700 uppercase text-[10px]">{{ $st }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.01] transition-transform">
                        <span wire:loading.remove>Simpan Perubahan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>