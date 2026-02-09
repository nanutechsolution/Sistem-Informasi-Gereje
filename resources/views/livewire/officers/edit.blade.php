<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex items-center justify-between">
            <div>
                <a href="{{ route('officers.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Edit Personil</h1>
                <p class="text-slate-500 mt-2 font-medium">Perbarui jabatan atau struktur gaji fleksibel.</p>
            </div>
            <div class="{{ $is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} px-4 py-2 rounded-2xl font-black text-xs uppercase tracking-widest">
                {{ $is_active ? 'Aktif' : 'Non-Aktif' }}
            </div>
        </div>

        <form wire:submit="update" class="space-y-8" 
              x-data="{ 
                  formatRupiah(value) { return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } 
              }">
            
            <!-- SECTION 1: PROFIL (READ ONLY) -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm flex items-center gap-6">
                <div class="h-16 w-16 rounded-3xl bg-slate-900 text-white flex items-center justify-center font-black text-2xl shadow-lg">
                    {{ substr($officer->member->nama, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 leading-none">{{ $officer->member->nama }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                        NIK: {{ $officer->member->nik ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- SECTION 2: PENUGASAN -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm relative">
                <div class="absolute -top-4 left-8 bg-blue-600 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Penugasan</div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jabatan</label>
                        <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                        <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold cursor-pointer">
                            <option value="organik">Organik GKS</option>
                            <option value="vicaris">Vicaris</option>
                            <option value="majelis">Majelis</option>
                            <option value="staf">Staf / Karyawan</option>
                            <option value="relawan">Relawan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lokasi Tugas</label>
                        <select wire:model="lokasi_tugas" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold cursor-pointer">
                            <option value="pusat">Gereja Pusat</option>
                            <option value="cabang">Gereja Cabang</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor SK</label>
                        <input wire:model="nomor_sk" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-4 col-span-1 md:col-span-2">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tgl Mulai</label>
                            <input wire:model="tanggal_mulai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tgl Selesai</label>
                            <input wire:model="tanggal_selesai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-600">
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span class="block text-sm font-black text-slate-800">Status Aktif Bertugas</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Non-aktifkan jika sudah berhenti.</span>
                        </div>
                        <div class="relative inline-block w-14 h-8 transition duration-200 ease-in-out bg-slate-200 rounded-full" :class="{'bg-emerald-500': @entangle('is_active')}">
                            <input type="checkbox" wire:model="is_active" class="absolute block w-6 h-6 bg-white border-4 border-slate-200 rounded-full appearance-none cursor-pointer transition-transform top-1 left-1" :class="{'translate-x-6 border-emerald-500': @entangle('is_active')}">
                        </div>
                    </label>
                </div>
            </div>

            <!-- SECTION 3: GAJI FLEKSIBEL (SAMA DENGAN CREATE) -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-600"></div>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">Rp</span>
                        Update Komponen Gaji
                    </h3>
                    <button type="button" wire:click="addComponent" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg">
                        + Tambah Baris
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($components as $index => $comp)
                    <div class="p-4 rounded-3xl border border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row gap-4 items-start lg:items-center group hover:bg-white hover:shadow-md transition-all relative">
                        
                        <div class="flex-1 w-full">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Nama Komponen</label>
                            <input type="text" wire:model="components.{{ $index }}.nama_komponen" class="w-full bg-white border-none rounded-xl p-3 font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-primary/20">
                            @error("components.$index.nama_komponen") <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="w-full lg:w-32">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Jenis</label>
                            <select wire:model.live="components.{{ $index }}.jenis" class="w-full border-none rounded-xl p-3 font-bold text-xs shadow-sm cursor-pointer {{ $comp['jenis'] == 'penerimaan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                <option value="penerimaan">PEMASUKAN</option>
                                <option value="potongan">POTONGAN</option>
                            </select>
                        </div>

                        <div class="w-full lg:w-48">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Nominal (Rp)</label>
                            <input type="tel" wire:model="components.{{ $index }}.nominal" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white border-none rounded-xl p-3 font-black text-right shadow-sm focus:ring-2 focus:ring-primary/20">
                             @error("components.$index.nominal") <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="w-full lg:w-64">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pos RAPB (Audit)</label>
                            <select wire:model="components.{{ $index }}.ref_budget_post_id" class="w-full bg-white border-none rounded-xl p-3 font-bold text-xs text-blue-600 shadow-sm cursor-pointer">
                                <option value="">-- Pilih Pos --</option>
                                @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                            </select>
                        </div>

                        <div class="pt-4 lg:pt-0 self-end lg:self-center">
                            <button type="button" wire:click="removeComponent({{ $index }})" class="p-2 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 p-6 bg-slate-900 rounded-3xl flex justify-between items-center text-white shadow-xl">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Take Home Pay</span>
                    <span class="text-3xl font-black tracking-tighter">Rp {{ number_format($this->estimatedThp, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4 pb-12">
                <a href="{{ route('officers.index') }}" class="flex-1 py-5 bg-white border-2 border-slate-200 rounded-[28px] font-black text-slate-400 text-center uppercase tracking-widest text-xs hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black shadow-2xl shadow-blue-500/40 uppercase tracking-widest text-xs hover:scale-[1.02] active:scale-95 transition-all">
                    <span wire:loading.remove>Update Data</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>