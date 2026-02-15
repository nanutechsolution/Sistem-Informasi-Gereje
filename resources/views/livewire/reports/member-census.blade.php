<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Filter -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">Sensus Jemaat</h1>
                <p class="text-slate-500 mt-2 font-medium">Laporan statistik kependudukan dan demografi jemaat.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white p-2 rounded-3xl shadow-sm border border-slate-100">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3">Tahun Laporan:</span>
                <select wire:model.live="yearFilter" class="bg-slate-50 border-none rounded-2xl py-2 pl-4 pr-10 font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
                    @foreach($fiscalYears as $fy)
                        <option value="{{ $fy->id }}">{{ $fy->tahun }}</option>
                    @endforeach
                </select>
                <button class="p-3 bg-slate-900 text-white rounded-2xl hover:bg-primary transition-all shadow-lg shadow-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
        </div>

        <!-- Ringkasan Utama -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-10">
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm transition-all hover:shadow-md">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Keluarga</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-slate-900 leading-none">{{ number_format($stats['kk']) }}</h3>
                    <span class="text-xs font-bold text-slate-400 uppercase">KK</span>
                </div>
            </div>
            <div class="bg-slate-900 p-6 rounded-[32px] shadow-xl shadow-slate-200 text-white">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Total Jiwa Aktif</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-white leading-none">{{ number_format($stats['jiwa']) }}</h3>
                    <span class="text-xs font-bold text-primary uppercase">Jiwa</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Laki-Laki</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-blue-600 leading-none">{{ number_format($stats['l']) }}</h3>
                    <div class="w-8 h-1 bg-blue-100 rounded-full mb-1"></div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Perempuan</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-rose-500 leading-none">{{ number_format($stats['p']) }}</h3>
                    <div class="w-8 h-1 bg-rose-100 rounded-full mb-1"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Demografi & Sakramen -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Distribusi Usia (Visual Bar) -->
                <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-2">
                        <span class="w-2 h-2 bg-primary rounded-full"></span> Komposisi Kelompok Usia
                    </h3>
                    <div class="space-y-6">
                        @foreach($usiaData as $label => $val)
                            @php $percent = $stats['jiwa'] > 0 ? ($val / $stats['jiwa']) * 100 : 0; @endphp
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $label }}</span>
                                    <span class="text-xs font-bold text-slate-400">{{ $val }} Jiwa ({{ round($percent, 1) }}%)</span>
                                </div>
                                <div class="h-3 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                    <div class="h-full bg-slate-900 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Statistik Wilayah -->
                <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Penyebaran Wilayah Pelayanan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($wilayahStats as $w)
                            <div class="flex items-center justify-between p-5 bg-slate-50 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-primary font-black shadow-sm group-hover:scale-110 transition-transform">
                                        {{ substr($w->nama, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-black text-slate-700 uppercase">{{ $w->nama }}</span>
                                </div>
                                <span class="text-sm font-black text-slate-900">{{ number_format($w->jiwa_count) }} <span class="text-[9px] text-slate-400 uppercase ml-1">Jiwa</span></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Sakramen & Mutasi -->
            <div class="space-y-8">
                
                <!-- Status Sakramen -->
                <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100 overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 relative z-10">Capaian Sakramen</h3>
                    <div class="space-y-4 relative z-10">
                        @foreach($sacramentData as $label => $val)
                            <div class="p-4 bg-slate-50 rounded-[24px] border border-slate-100 flex justify-between items-center">
                                <span class="text-xs font-black text-slate-700 uppercase tracking-widest">{{ $label }}</span>
                                <div class="text-right">
                                    <span class="text-xl font-black text-emerald-600 leading-none">{{ number_format($val) }}</span>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">Arsip Terdata</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ringkasan Mutasi Tahun Berjalan -->
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-xl shadow-slate-200">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-8">Mutasi Tahun {{ $currentYearLabel }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-5 bg-white/5 rounded-3xl border border-white/10 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-2 tracking-widest">Meninggal Dunia</p>
                            <h4 class="text-2xl font-black text-rose-400">{{ $stats['meninggal'] }}</h4>
                        </div>
                        <div class="p-5 bg-white/5 rounded-3xl border border-white/10 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-2 tracking-widest">Pindah / Mutasi Keluar</p>
                            <h4 class="text-2xl font-black text-amber-400">{{ $stats['pindah'] }}</h4>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[9px] text-slate-400 leading-tight">Data mutasi dihitung berdasarkan periode Tahun Anggaran (Fiskal) yang aktif atau dipilih.</p>
                    </div>
                </div>

                <!-- Info Branding -->
                <div class="bg-white rounded-[32px] p-6 border border-slate-100 flex items-center justify-center gap-3 grayscale opacity-40">
                    <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center font-black text-slate-400">RP</div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">SIG-GKS Jemaat Reda Pada<br>Sistem Informasi Gereja</p>
                </div>

            </div>
        </div>
    </div>
</div>