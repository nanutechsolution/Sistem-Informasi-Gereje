<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <a href="{{ route('officers.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Personil
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Edit Data Personil</h1>
            <p class="text-gray-500 mt-1">Memperbarui tugas dan struktur gaji untuk <span class="font-bold text-gray-800">{{ $officer->member->nama }}</span>.</p>
        </div>

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: Penugasan & Keuangan -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Identitas & Penugasan -->
                    <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">1. Penugasan Struktur</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jabatan Struktur</label>
                                <select wire:model="ref_position_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20 transition-all">
                                    @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->nama }}</option> @endforeach
                                </select>
                            </div>

                            <!-- DROP DOWN POS ANGGARAN (SINKRONISASI AUDIT) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1 text-primary font-black">Pos Anggaran RAPB (Audit)</label>
                                <select wire:model="ref_budget_post_id" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-bold text-primary focus:ring-2 focus:ring-primary/20 appearance-none">
                                    <option value="">-- Pilih Pos Pengeluaran --</option>
                                    @foreach($budgetPosts as $bp) 
                                        <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> 
                                    @endforeach
                                </select>
                                <p class="mt-2 text-[10px] text-slate-400 italic font-medium">* Pastikan pos ini sesuai dengan baris di RAPB Bapak.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Status Kepegawaian</label>
                                <select wire:model="status_kepegawaian" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="organik">Organik GKS</option>
                                    <option value="vicaris">Vicaris</option>
                                    <option value="majelis">Majelis</option>
                                    <option value="staf">Staf/Karyawan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Lokasi Tugas</label>
                                <select wire:model="lokasi_tugas" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="pusat">Gereja Pusat</option>
                                    <option value="cabang">Gereja Cabang</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Komponen Gaji -->
                    <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">2. Komponen Gaji (Bulanan)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Gaji Pokok</label>
                                <input wire:model="gaji_pokok" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-slate-900" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tunj. Perumahan</label>
                                <input wire:model="tunjangan_perumahan" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-emerald-600" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Potongan Pensiun</label>
                                <input wire:model="iuran_pensiun" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-rose-600" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Status & SK -->
                <div class="space-y-6">
                    <div class="bg-slate-900 rounded-[32px] p-8 text-white shadow-xl">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Administrasi & Legalitas</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Nomor SK Aktif</label>
                                <input wire:model="nomor_sk" type="text" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 font-bold text-white focus:border-blue-500 transition-all" placeholder="SK/XXX/2026">
                            </div>

                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                                <span class="text-xs font-bold text-slate-300">Status Aktif</span>
                                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-slate-700 rounded-full">
                                    <input type="checkbox" wire:model="is_active" class="absolute block w-6 h-6 bg-white border-4 border-slate-700 rounded-full appearance-none cursor-pointer checked:translate-x-6 transition-transform">
                                </div>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-primary text-white rounded-[24px] font-black shadow-xl shadow-blue-500/30 hover:scale-[1.02] transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <span wire:loading.remove>Simpan Perubahan</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-[32px] p-6 border border-blue-100">
                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-2">Take Home Pay Saat Ini</p>
                        <p class="text-2xl font-black text-blue-900">Rp {{ number_format((float)$gaji_pokok + (float)$tunjangan_perumahan - (float)$iuran_pensiun, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>