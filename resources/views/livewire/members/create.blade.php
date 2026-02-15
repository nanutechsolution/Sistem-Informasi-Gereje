<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <div class="mb-8">
            <a href="{{ route('families.edit', $family->uuid) }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke KK {{ $family->nomor_kk }}
            </a>
            <h1 class="text-3xl font-black text-slate-900 uppercase">Tambah Anggota</h1>
            <p class="text-slate-500 mt-1 font-medium">Cari data orang dan masukkan ke dalam Kartu Keluarga.</p>
        </div>

        <div class="bg-white rounded-[40px] p-6 sm:p-10 shadow-xl border border-slate-100 relative overflow-visible">
            
            @if(!$selectedPerson)
                <div class="space-y-6">
                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cari Data Orang (Master)</label>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all placeholder:text-slate-300" 
                               placeholder="Ketik Nama atau NIK...">
                        
                        @if(strlen($search) > 2)
                            <div class="absolute z-20 top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto">
                                @forelse($searchResults as $person)
                                    <button wire:click="selectPerson({{ $person->id }})" class="w-full text-left p-4 hover:bg-blue-50 transition-colors border-b border-slate-50 last:border-0 group">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <span class="block font-black text-slate-700 text-sm group-hover:text-primary">{{ $person->full_name }}</span>
                                                <span class="text-xs text-slate-400">NIK: {{ $person->nik ?? '-' }} • {{ $person->gender == 'L' ? 'Lk' : 'Pr' }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-slate-300 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                    </button>
                                @empty
                                    <div class="p-4 text-center">
                                        <p class="text-xs text-slate-400 font-bold mb-2">Data tidak ditemukan.</p>
                                        <a href="{{ route('people.create') }}" class="text-xs text-primary underline font-bold">Buat Data Orang Baru</a>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-xs text-amber-800 font-medium leading-relaxed">
                            <strong>Info:</strong> Pastikan data orang sudah terdaftar di menu "Master Data Orang". Jika belum ada, silakan buat baru terlebih dahulu.
                        </p>
                    </div>
                </div>

            @else
                <form wire:submit="save">
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6 flex justify-between items-start animate-in fade-in zoom-in-95 duration-300">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-black text-lg">
                                {{ substr($selectedPerson->full_name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-black text-blue-900 text-lg">{{ $selectedPerson->full_name }}</h3>
                                <p class="text-xs text-blue-600 font-medium">NIK: {{ $selectedPerson->nik ?? '-' }}</p>
                                <p class="text-[10px] text-blue-400 mt-1 uppercase font-bold tracking-wider">
                                    {{ $selectedPerson->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} • {{ \Carbon\Carbon::parse($selectedPerson->date_of_birth)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="cancelSelection" class="text-xs font-bold text-rose-500 hover:underline">
                            Ganti
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Hubungan Keluarga</label>
                            <div class="relative">
                                <select wire:model="hubungan_keluarga_id" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 appearance-none transition-all">
                                    <option value="">Pilih Status...</option>
                                    @foreach($refHubungans as $hub)
                                        <option value="{{ $hub->id }}">{{ $hub->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('hubungan_keluarga_id') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pekerjaan</label>
                            <div class="relative">
                                <select wire:model="pekerjaan_id" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 appearance-none transition-all">
                                    <option value="">Pilih Pekerjaan...</option>
                                    @foreach($refPekerjaans as $job)
                                        <option value="{{ $job->id }}">{{ $job->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('pekerjaan_id') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.01] transition-transform active:scale-[0.98]">
                                <span wire:loading.remove>Simpan ke Keluarga</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>