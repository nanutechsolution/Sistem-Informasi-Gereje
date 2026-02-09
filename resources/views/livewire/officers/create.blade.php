<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tambah Personil Baru</h1>
            <p class="text-slate-500 mt-2">Daftarkan pengerja jemaat dan sinkronkan dengan 3 Pos RAPB sekaligus.</p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <!-- 1. IDENTITAS -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <div class="relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Cari Anggota Jemaat</label>
                    <input wire:model.live.debounce.300ms="searchMember" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama...">
                    
                    @if($selectedMemberName)
                        <div class="mt-3 p-4 bg-emerald-50 text-emerald-700 rounded-2xl font-bold flex justify-between items-center">
                            <span>{{ $selectedMemberName }}</span>
                            <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] uppercase font-black underline">Ganti</button>
                        </div>
                    @endif

                    @if(!empty($foundMembers))
                    <div class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y">
                        @foreach($foundMembers as $m)
                        <button type="button" wire:click="selectMember({{ $m->id }}, '{{ $m->nama }}')" class="w-full text-left p-5 hover:bg-slate-50 transition-colors">
                            <p class="font-black text-slate-900">{{ $m->nama }}</p>
                            <p class="text-[10px] text-slate-400">{{ $m->nik }}</p>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- 2. TUGAS & ADMINISTRASI -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Jabatan Struktur</label>
                        <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold">
                            <option value="">-- Pilih --</option>
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status Kepegawaian</label>
                        <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold">
                            <option value="organik">Organik GKS</option>
                            <option value="vicaris">Vicaris</option>
                            <option value="majelis">Majelis</option>
                            <option value="staf">Staf/Karyawan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tanggal Mulai</label>
                        <input wire:model="tanggal_mulai" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nomor SK</label>
                        <input wire:model="nomor_sk" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold" placeholder="SK/001/2026">
                    </div>
                </div>
            </div>

            <!-- 3. KEUANGAN & AUDIT POS -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-8">Pemetaan Pos Anggaran RAPB</h3>
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Gaji Pokok (Nominal)</label>
                            <input wire:model="gaji_pokok" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-3">Pos Anggaran Gaji</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-blue-50 border-none rounded-2xl p-5 font-bold text-primary">
                                <option value="">-- Pilih Pos Gaji --</option>
                                @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3">Tunj. Perumahan (Nominal)</label>
                            <input wire:model="tunjangan_perumahan" type="number" class="w-full bg-emerald-50 border-none rounded-2xl p-5 font-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3">Pos Anggaran Perumahan</label>
                            <select wire:model="ref_perumahan_post_id" class="w-full bg-emerald-50 border-none rounded-2xl p-5 font-bold">
                                <option value="">-- Pilih Pos Perumahan --</option>
                                @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label class="block text-[10px] font-black text-rose-600 uppercase tracking-widest mb-3">Iuran Pensiun (Nominal)</label>
                            <input wire:model="iuran_pensiun" type="number" class="w-full bg-rose-50 border-none rounded-2xl p-5 font-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-rose-600 uppercase tracking-widest mb-3">Pos Anggaran Pensiun</label>
                            <select wire:model="ref_pensiun_post_id" class="w-full bg-rose-50 border-none rounded-2xl p-5 font-bold">
                                <option value="">-- Pilih Pos Pensiun --</option>
                                @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-10 p-6 bg-slate-900 rounded-3xl flex justify-between items-center text-white">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Take Home Pay (THP)</span>
                    <span class="text-3xl font-black">Rp {{ number_format((float)$gaji_pokok + (float)$tunjangan_perumahan - (float)$iuran_pensiun, 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white rounded-[32px] font-black shadow-2xl shadow-blue-500/40 uppercase tracking-widest text-xs hover:scale-[1.02] transition-all">
                Simpan & Hubungkan Audit RAPB
            </button>
        </form>
    </div>
</div>