<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6 print:hidden">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Sensus Jemaat</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-blue-600 pl-4 uppercase text-[10px] tracking-widest">Analisis Demografi & Pertumbuhan Jiwa Jemaat Reda Pada.</p>
            </div>
            <button onclick="window.print()" class="px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all uppercase tracking-widest">Cetak Laporan</button>
        </div>

        <!-- 1. KEY NUMBERS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">Total Keluarga</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $stats['kk'] }}</h3>
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Kepala Keluarga</span>
            </div>
            <div class="bg-slate-900 p-8 rounded-[40px] shadow-2xl text-white text-center">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 leading-none">Total Jiwa</p>
                <h3 class="text-4xl font-black text-white italic tracking-tighter">{{ $stats['jiwa'] }}</h3>
                <span class="text-[9px] font-bold text-blue-400 uppercase tracking-widest tracking-tighter italic">L: {{ $stats['l'] }} | P: {{ $stats['p'] }}</span>
            </div>
            <!-- Progress Sakramen -->
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Status Gerejawi (Persentase Sidi)</h4>
                @php $sidiPerc = $stats['jiwa'] > 0 ? ($sacramentData['Sudah Sidi'] / $stats['jiwa']) * 100 : 0; @endphp
                <div class="flex items-center gap-6">
                    <div class="flex-1">
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ $sidiPerc }}%"></div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 italic">{{ number_format($sidiPerc, 1) }}%</span>
                </div>
                <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase italic tracking-wide">{{ $sacramentData['Sudah Sidi'] }} dari {{ $stats['jiwa'] }} Jiwa sudah Sidi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- 2. DISTRIBUSI WILAYAH -->
            <div class="bg-white rounded-[50px] p-10 border border-slate-200 shadow-sm">
                <h3 class="text-xl font-black italic uppercase tracking-tighter text-slate-900 mb-8 border-b border-slate-50 pb-4">Distribusi Wilayah Pelayanan</h3>
                <div class="space-y-6">
                    @foreach($wilayahStats as $w)
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-2xl bg-blue-50 text-primary flex items-center justify-center font-black text-xs shadow-sm">{{ substr($w->nama, -1) }}</div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="font-bold text-slate-700 uppercase text-xs">{{ $w->nama }}</span>
                                <span class="font-black text-slate-900 text-xs">{{ $w->jiwa_count }} <span class="text-[9px] text-slate-400 not-italic uppercase font-bold">Jiwa</span></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                @php $wPerc = $stats['jiwa'] > 0 ? ($w->jiwa_count / $stats['jiwa']) * 100 : 0; @endphp
                                <div class="h-full bg-primary rounded-full" style="width: {{ $wPerc }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. KELOMPOK USIA & SAKRAMEN -->
            <div class="space-y-8">
                <!-- Kelompok Usia -->
                <div class="bg-slate-900 rounded-[50px] p-10 text-white shadow-2xl relative overflow-hidden group">
                    <h3 class="text-lg font-black italic uppercase tracking-widest text-blue-300 mb-8">Piramida Usia Jemaat</h3>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($usiaData as $label => $val)
                        <div class="flex items-center justify-between group/row">
                            <span class="text-xs font-bold text-slate-400 group-hover/row:text-white transition-colors uppercase tracking-widest">{{ $label }}</span>
                            <div class="flex items-center gap-4">
                                <div class="h-1 bg-white/10 w-24 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-400 transition-all duration-1000" style="width: {{ $stats['jiwa'] > 0 ? ($val / $stats['jiwa']) * 100 : 0 }}%"></div>
                                </div>
                                <span class="font-black text-lg group-hover/row:scale-110 transition-transform">{{ $val }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-white/5 pointer-events-none group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                </div>

                <!-- Status Sakramen Mini Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($sacramentData as $label => $val)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm text-center hover:border-blue-500 transition-colors">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                        <h4 class="text-2xl font-black text-slate-900 leading-tight italic">{{ $val }}</h4>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- FOOTER DOKUMEN -->
        <div class="mt-20 pt-8 border-t-2 border-slate-200 text-center italic text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] flex items-center justify-center gap-4">
            <span>SIG-GKS JEMAAT REDA PADA</span>
            <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
            <span>DATA PER {{ date('d F Y') }}</span>
        </div>
    </div>
</div