<div class="py-4 sm:py-8 md:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8 md:mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="w-full md:w-auto">
                <a href="{{ route('officers.index') }}" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-primary gap-2 mb-3 transition-colors group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 uppercase tracking-tight leading-tight">Profil Pejabat</h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">Perbarui jabatan dan sinkronisasi anggaran gaji.</p>
            </div>

            <div class="inline-flex self-start md:self-auto px-4 py-2 bg-amber-50 rounded-2xl border border-amber-100 text-[10px] font-black text-amber-600 uppercase tracking-widest items-center gap-2 shadow-sm">
                <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                Mode Pengeditan
            </div>
        </div>

        <form wire:submit="update" class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- DATA JABATAN (KOLOM KIRI) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[24px] sm:rounded-[32px] p-5 sm:p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden transition-all">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-amber-400"></div>

                    <!-- Info Nama (Readonly) -->
                    <div class="bg-slate-50 p-4 sm:p-6 rounded-[20px] sm:rounded-[24px] border border-slate-100 mb-6 sm:mb-8 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Nama Pejabat</label>
                            <p class="font-black text-slate-900 text-lg sm:text-xl truncate leading-tight">{{ $member_name }}</p>
                        </div>
                    </div>

                    <div class="space-y-6 sm:space-y-8">
                        <!-- Grid Input 1 -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jabatan Struktural</label>
                                <div class="relative group">
                                    <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-amber-400/20 appearance-none transition-all cursor-pointer">
                                        @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 transition-colors group-hover:text-amber-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIP Gereja</label>
                                <input wire:model="nip_gereja" type="text" placeholder="Contoh: 1980..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-amber-400/20 transition-all placeholder:text-slate-300">
                            </div>
                        </div>

                        <!-- Grid Input 2 -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Kepegawaian</label>
                                <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-amber-400/20 appearance-none transition-all cursor-pointer">
                                    <option value="organik">Organik</option>
                                    <option value="majelis">Majelis</option>
                                    <option value="vicaris">Vicaris</option>
                                    <option value="non_organik">Non Organik</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Masa Tugas</label>
                                <div class="flex flex-col sm:flex-row items-center gap-3">
                                    <div class="relative w-full">
                                        <input wire:model="tanggal_mulai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-amber-400/20 text-sm">
                                    </div>
                                    <span class="hidden sm:inline text-slate-300 font-black">—</span>
                                    <div class="relative w-full">
                                        <input wire:model="tanggal_selesai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-amber-400/20 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STRUKTUR GAJI (KOLOM KANAN) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[24px] sm:rounded-[32px] p-5 sm:p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col h-full min-h-[500px] transition-all">
                    <div class="flex justify-between items-center mb-6 px-1">
                        <div>
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sinkronisasi Gaji</h3>
                            <p class="text-[9px] text-slate-400 font-medium">Atur rincian pendapatan/potongan</p>
                        </div>
                        <button type="button" wire:click="addComponent" class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 hover:text-primary transition-all shadow-sm active:scale-95" title="Tambah Komponen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 space-y-4 max-h-[400px] lg:max-h-none overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($components as $index => $comp)
                        <div class="p-4 bg-slate-50 rounded-[20px] border border-slate-100 relative group hover:border-amber-200 hover:shadow-md transition-all animate-in slide-in-from-right-4 duration-300" wire:key="edit-comp-{{ $index }}">
                            <button type="button" wire:click="removeComponent({{ $index }})"
                                class="absolute -top-2 -right-2 w-7 h-7 bg-white shadow-lg rounded-full flex items-center justify-center text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all border border-slate-100 z-10 active:scale-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tight ml-1">Nama Komponen</label>
                                    <select wire:model.live="components.{{ $index }}.ref_salary_component_id" class="w-full bg-white border-none rounded-xl text-xs font-black text-slate-700 focus:ring-2 focus:ring-amber-400/10 appearance-none shadow-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach($refSalaryComponents as $ref)
                                        <option value="{{ $ref->id }}">{{ $ref->nama }} ({{ $ref->jenis == 'penerimaan' ? '▲' : '▼' }})</option>
                                        @endforeach
                                    </select>
                                    @error("components.$index.ref_salary_component_id") <span class="text-rose-500 text-[8px] font-bold mt-1 block ml-1">Wajib dipilih</span> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tight ml-1">Pos Anggaran</label>
                                    <select wire:model.live="components.{{ $index }}.ref_budget_post_id" class="w-full bg-white border-none rounded-xl text-xs font-black text-slate-700 focus:ring-2 focus:ring-amber-400/10 appearance-none shadow-sm">
                                        <option value="">-- Pilih Pos --</option>
                                        @foreach($budgetPosts as $bp)
                                        <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("components.$index.ref_budget_post_id") <span class="text-rose-500 text-[8px] font-bold mt-1 block ml-1">Harus diisi</span> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tight ml-1">Nominal (Rp)</label>
                                    <input type="number" wire:model.live="components.{{ $index }}.nominal" class="w-full bg-white border-none rounded-xl py-2.5 px-3 text-sm font-mono font-bold text-right text-slate-800 focus:ring-2 focus:ring-amber-400/10 shadow-inner">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                        <div class="flex justify-between items-center mb-6 px-1">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Take Home Pay</span>
                                <p class="text-[9px] text-slate-400 font-medium italic">Estimasi bersih</p>
                            </div>
                            <span class="text-xl sm:text-2xl font-black text-emerald-500">Rp {{ number_format($this->estimatedThp, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="update"
                            class="group w-full py-5 bg-slate-900 text-white rounded-[20px] sm:rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="update">Update Profil</span>
                            <span wire:loading wire:target="update" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
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

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>

</div>