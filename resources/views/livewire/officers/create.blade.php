<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="mb-8">
            <a href="{{ route('officers.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" />
                </svg> Kembali
            </a>
            <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Daftarkan Pejabat</h1>
            <p class="text-slate-500 mt-2 font-medium">Input data kepegawaian dan rincian anggaran gaji.</p>
        </div>

        <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[32px] p-6 sm:p-10 shadow-xl border border-slate-100 relative overflow-visible">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-primary rounded-t-[32px]"></div>

                    <div class="space-y-8">
                        <!-- Cari Jemaat -->
                        <div class="relative">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Jemaat</label>
                            @if($selectedMemberName)
                            <div class="flex justify-between items-center bg-blue-50 p-5 rounded-2xl border border-blue-100 animate-in zoom-in-95">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-black text-sm">
                                        {{ substr($selectedMemberName, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-blue-900">{{ $selectedMemberName }}</span>
                                </div>
                                <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] text-rose-500 font-black uppercase hover:underline">Ganti</button>
                            </div>
                            @else
                            <div class="relative">
                                <input wire:model.live.debounce.300ms="searchMember" type="text" placeholder="Ketik Nama atau NIK..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 transition-all">
                                <div class="absolute right-4 top-4 text-slate-300" wire:loading wire:target="searchMember">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            @if(!empty($searchResults))
                            <div class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($searchResults as $m)
                                <button type="button" wire:click="selectMember({{ $m->id }}, '{{ $m->churchPeople->full_name }}')" class="w-full text-left p-4 hover:bg-slate-50 transition-colors group">
                                    <span class="block font-black text-slate-700 group-hover:text-primary">{{ $m->churchPeople->full_name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono tracking-wider uppercase">{{ $m->churchPeople->nik ?? 'TANPA NIK' }}</span>
                                </button>
                                @endforeach
                            </div>
                            @endif
                            @endif
                            @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jabatan Struktural</label>
                                <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none transition-all cursor-pointer">
                                    <option value="">Pilih Jabatan...</option>
                                    @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                                </select>
                                @error('ref_position_id') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">NIP Gereja (Opsional)</label>
                                <input wire:model="nip_gereja" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 border-t border-slate-50 pt-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mulai Tugas</label>
                                <input wire:model="tanggal_mulai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Kepegawaian</label>
                                <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none transition-all cursor-pointer">
                                    <option value="organik">Organik</option>
                                    <option value="majelis">Majelis</option>
                                    <option value="vicaris">Vicaris</option>
                                    <option value="non_organik">Non Organik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STRUKTUR GAJI -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[32px] p-6 shadow-xl border border-slate-100 flex flex-col h-full min-h-[500px]">
                    <div class="flex justify-between items-center mb-6 px-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Struktur Gaji</h3>
                        <button type="button" wire:click="addComponent" class="p-2 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors shadow-sm">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 space-y-4 max-h-[450px] overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($components as $index => $comp)
                        <div class="p-4 bg-slate-50 rounded-[24px] border border-slate-100 relative group animate-in slide-in-from-right" wire:key="comp-{{ $index }}">
                            <button type="button" wire:click="removeComponent({{ $index }})" class="absolute -top-2 -right-2 w-7 h-7 bg-white shadow-md rounded-full flex items-center justify-center text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all border border-slate-100 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1">Jenis Komponen</label>
                                    <select wire:model.live="components.{{ $index }}.ref_salary_component_id" class="w-full bg-white border-none rounded-xl text-xs font-black text-slate-700 focus:ring-2 focus:ring-primary/10 appearance-none">
                                        <option value="">-- Pilih --</option>
                                        @foreach($refSalaryComponents as $ref)
                                        <option value="{{ $ref->id }}">{{ $ref->nama }} ({{ $ref->jenis == 'penerimaan' ? '+' : '-' }})</option>
                                        @endforeach
                                    </select>
                                    @error("components.$index.ref_salary_component_id") <span class="text-rose-500 text-[8px] font-bold mt-1 block">Wajib dipilih</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1">Pos Anggaran (RAPB)</label>
                                    <select wire:model.live="components.{{ $index }}.ref_budget_post_id" class="w-full bg-white border-none rounded-xl text-xs font-black text-slate-700 focus:ring-2 focus:ring-primary/10 appearance-none">
                                        <option value="">-- Pilih Pos --</option>
                                        @foreach($budgetPosts as $bp)
                                        <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("components.$index.ref_budget_post_id") <span class="text-rose-500 text-[8px] font-bold mt-1 block">Pos anggaran wajib</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1">Nominal (Rp)</label>
                                    <input type="number" wire:model.live="components.{{ $index }}.nominal" class="w-full bg-white border-none rounded-xl py-2 px-3 text-sm font-mono font-bold text-right text-slate-800 focus:ring-2 focus:ring-primary/10 shadow-inner">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Take Home Pay</span>
                            <span class="text-xl font-black text-emerald-500">Rp {{ number_format($this->estimatedThp, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="group w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="save">Simpan Pejabat</span>
                            <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

</div>