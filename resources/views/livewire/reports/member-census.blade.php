<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6 print:hidden">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Sensus Jemaat</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Analisis Demografi & Mutasi GKS Jemaat Reda Pada.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    <label class="text-[9px] font-black text-slate-400 uppercase mb-1 ml-1">Pilih Tahun Fiskal</label>
                    <select wire:model.live="yearFilter" class="bg-white border-slate-200 rounded-2xl text-xs font-bold px-6 py-3 shadow-sm focus:ring-primary transition-all">
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->tahun }}">Tahun {{ $fy->tahun }} {{ $fy->is_active ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="window.print()" class="px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-xl hover:bg-primary transition-all uppercase tracking-widest self-end">Cetak Laporan</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">Total KK Aktif</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $stats['kk'] }}</h3>
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Keluarga Terdaftar</span>
            </div>

            <div class="bg-primary p-8 rounded-[40px] shadow-2xl shadow-blue-500/40 text-white text-center">
                <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-2 leading-none">Total Jiwa Aktif</p>
                <h3 class="text-4xl font-black text-white italic tracking-tighter">{{ $stats['jiwa'] }}</h3>
                <span class="text-[9px] font-bold text-blue-100 uppercase tracking-widest italic tracking-tighter">L: {{ $stats['l'] }} | P: {{ $stats['p'] }}</span>
            </div>

            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-2 leading-none">Meninggal ({{ $yearFilter }})</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $stats['meninggal'] }}</h3>
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Jiwa Wafat</span>
            </div>

            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2 leading-none">Pindah ({{ $yearFilter }})</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $stats['pindah'] }}</h3>
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Keluar Jemaat</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <div class="bg-white rounded-[50px] p-10 border border-slate-200 shadow-sm">
                <h3 class="text-xl font-black italic uppercase tracking-tighter text-slate-900 mb-8 border-b border-slate-50 pb-4">Distribusi Per Wilayah</h3>
                <div class="space-y-6">
                    @foreach($wilayahStats as $w)
                    <div class="group">
                        <div class="flex justify-between mb-2">
                            <span class="font-bold text-slate-700 uppercase text-[11px] tracking-wider">{{ $w->nama }}</span>
                            <span class="font-black text-slate-900 text-xs">{{ $w->jiwa_count }} <span class="text-[9px] text-slate-400 uppercase font-bold not-italic">Jiwa</span></span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            @php $wPerc = $stats['jiwa'] > 0 ? ($w->jiwa_count / $stats['jiwa']) * 100 : 0; @endphp
                            <div class="h-full bg-primary group-hover:bg-slate-900 transition-all duration-700" style="width: {{ $wPerc }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[50px] p-10 text-white shadow-2xl">
                    <h3 class="text-lg font-black italic uppercase tracking-widest text-blue-300 mb-8">Struktur Usia Jemaat Aktif</h3>
                    <div class="space-y-6">
                        @foreach($usiaData as $label => $val)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ $label }}</span>
                                <span class="font-black text-lg">{{ $val }}</span>
                            </div>
                            <div class="h-1 bg-white/10 w-full rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 transition-all duration-1000" style="width: {{ $stats['jiwa'] > 0 ? ($val / $stats['jiwa']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($sacramentData as $label => $val)
                    <div class="bg-white p-6 rounded-[35px] border border-slate-100 shadow-sm text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah {{ $label }}</p>
                        <h4 class="text-2xl font-black text-slate-900 italic leading-none">{{ $val }}</h4>
                        <div class="mt-2 h-1 w-8 bg-blue-100 mx-auto rounded-full"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-slate-200 text-center italic text-slate-400 text-[9px] font-bold uppercase tracking-[0.4em] flex items-center justify-center gap-4">
            <span>SISTEM INFORMASI GEREJA GKS JEMAAT REDA PADA</span>
            <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
            <span>LAPORAN TAHUN FISKAL {{ $yearFilter }}</span>
        </div>
    </div>
</div>