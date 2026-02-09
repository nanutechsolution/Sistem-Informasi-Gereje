<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Tambah Personil</h1>
            <p class="text-slate-500 mt-2 font-medium">Registrasi pegawai dengan struktur gaji fleksibel.</p>
        </div>

        <form wire:submit="save" class="space-y-8" 
              x-data="{ 
                  formatRupiah(value) { return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } 
              }">
            
            <!-- SECTION 1: PILIH JEMAAT -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm relative overflow-visible">
                <div class="absolute -top-4 left-8 bg-slate-900 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Langkah 1: Identitas</div>
                
                <div class="relative mt-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Cari Nama Anggota</label>
                    <input wire:model.live.debounce.300ms="searchMember" type="text" 
                        class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold focus:ring-4 focus:ring-primary/10 placeholder:text-slate-300 transition-all" 
                        placeholder="Ketik minimal 3 huruf...">
                    
                    @if($selectedMemberName)
                        <div class="mt-3 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold flex justify-between items-center animate-in fade-in slide-in-from-top-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                {{ $selectedMemberName }}
                            </span>
                            <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[9px] uppercase font-black tracking-widest hover:underline text-rose-500">Ganti</button>
                        </div>
                    @endif

                    @if(!empty($foundMembers))
                    <div class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50 max-h-60 overflow-y-auto">
                        @foreach($foundMembers as $m)
                        <button type="button" wire:click="selectMember({{ $m->id }}, '{{ $m->nama }}')" class="w-full text-left p-5 hover:bg-blue-50 transition-colors group">
                            <p class="font-black text-slate-900 group-hover:text-primary">{{ $m->nama }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $m->nik }} • {{ $m->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </button>
                        @endforeach
                    </div>
                    @endif
                    @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-2 block ml-2 uppercase tracking-widest">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- SECTION 2: PENUGASAN -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm relative">
                <div class="absolute -top-4 left-8 bg-blue-600 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Langkah 2: Penugasan</div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jabatan Struktur</label>
                        <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20 cursor-pointer transition-all">
                            <option value="">-- Pilih --</option>
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                        </select>
                        @error('ref_position_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Kepegawaian</label>
                        <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold cursor-pointer">
                            <option value="organik">Organik GKS</option>
                            <option value="vicaris">Vicaris</option>
                            <option value="majelis">Majelis (PHJ/PHM)</option>
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
                        <input wire:model="nomor_sk" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" placeholder="SK/001/GKS/2026">
                    </div>

                    <div class="grid grid-cols-2 gap-4 col-span-1 md:col-span-2">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tgl Mulai</label>
                            <input wire:model="tanggal_mulai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                             @error('tanggal_mulai') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tgl Selesai (Opsional)</label>
                            <input wire:model="tanggal_selesai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: STRUKTUR GAJI FLEKSIBEL -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200/60 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">Rp</span>
                        Komponen Gaji
                    </h3>
                    <button type="button" wire:click="addComponent" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg">
                        + Tambah Baris
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($components as $index => $comp)
                    <div class="p-4 rounded-3xl border border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row gap-4 items-start lg:items-center group hover:bg-white hover:shadow-md transition-all relative">
                        
                        <!-- Nama Komponen -->
                        <div class="flex-1 w-full">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Nama Komponen</label>
                            <input type="text" wire:model="components.{{ $index }}.nama_komponen" class="w-full bg-white border-none rounded-xl p-3 font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Tunjangan Anak">
                            @error("components.$index.nama_komponen") <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis -->
                        <div class="w-full lg:w-32">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Jenis</label>
                            <select wire:model.live="components.{{ $index }}.jenis" class="w-full border-none rounded-xl p-3 font-bold text-xs shadow-sm cursor-pointer {{ $comp['jenis'] == 'penerimaan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                <option value="penerimaan">PEMASUKAN (+)</option>
                                <option value="potongan">POTONGAN (-)</option>
                            </select>
                        </div>

                        <!-- Nominal -->
                        <div class="w-full lg:w-48">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Nominal (Rp)</label>
                            <input type="tel" wire:model="components.{{ $index }}.nominal" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white border-none rounded-xl p-3 font-black text-right shadow-sm focus:ring-2 focus:ring-primary/20" placeholder="0">
                             @error("components.$index.nominal") <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pos Anggaran -->
                        <div class="w-full lg:w-64">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pos RAPB (Audit)</label>
                            <select wire:model="components.{{ $index }}.ref_budget_post_id" class="w-full bg-white border-none rounded-xl p-3 font-bold text-xs text-blue-600 shadow-sm cursor-pointer appearance-none">
                                <option value="">-- Pilih Pos --</option>
                                @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                            </select>
                        </div>

                        <!-- Hapus -->
                        <div class="pt-4 lg:pt-0 self-end lg:self-center">
                            <button type="button" wire:click="removeComponent({{ $index }})" class="p-2 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total THP -->
                <div class="mt-8 p-6 bg-slate-900 rounded-3xl flex justify-between items-center text-white shadow-xl">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Take Home Pay</span>
                    <span class="text-3xl font-black tracking-tighter">Rp {{ number_format($this->estimatedThp, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4 pb-12">
                <a href="{{ route('officers.index') }}" class="flex-1 py-5 bg-white border-2 border-slate-200 rounded-[28px] font-black text-slate-400 text-center uppercase tracking-widest text-xs hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black shadow-2xl shadow-blue-500/40 uppercase tracking-widest text-xs hover:scale-[1.02] active:scale-95 transition-all">
                    <span wire:loading.remove>Simpan Personil</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>