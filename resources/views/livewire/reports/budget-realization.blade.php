<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 print:hidden">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic">Audit Realisasi RAPB</h1>
                <p class="text-slate-500 mt-3 font-medium">Perbandingan rencana anggaran vs realisasi kas.</p>
            </div>

            <div class="flex items-center gap-3">
                <select wire:model.live="fiscalYearId" class="bg-white border-slate-200 rounded-2xl font-bold text-sm focus:ring-primary shadow-sm py-3 px-4 cursor-pointer">
                    @foreach($fiscalYears as $fy)
                        <option value="{{ $fy->id }}">{{ $fy->tahun }} {{ $fy->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
                <button onclick="window.print()" class="p-3 bg-slate-900 text-white rounded-2xl shadow-lg hover:bg-slate-800 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
        </div>

        @if(!$selectedYear)
            <div class="text-center py-20 bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Data Tahun Anggaran Tidak Ditemukan</p>
            </div>
        @else

        <!-- SUMMARY CARDS (FIX CALCULATION) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 print:hidden">
            <!-- Pendapatan -->
            @php 
                // FIX: Gunakan 'totalTarget' dan 'totalRealization' (akumulasi), bukan 'target' (diri sendiri)
                $sumIncTarget = $reportData->where('jenis', 'pemasukan')->sum('totalTarget');
                $sumIncReal = $reportData->where('jenis', 'pemasukan')->sum('totalRealization');
                $incPercent = $sumIncTarget > 0 ? ($sumIncReal / $sumIncTarget) * 100 : 0;
            @endphp
            <div class="bg-emerald-600 p-8 rounded-[40px] text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black uppercase opacity-60 tracking-[0.2em]">Total Pendapatan</p>
                        <span class="bg-emerald-500/50 px-2 py-1 rounded text-[10px] font-bold">{{ number_format($incPercent, 1) }}%</span>
                    </div>
                    <h3 class="text-3xl font-black tracking-tighter">Rp {{ number_format($sumIncReal, 0, ',', '.') }}</h3>
                    <p class="text-xs font-medium opacity-80 mt-1">Target: Rp {{ number_format($sumIncTarget, 0, ',', '.') }}</p>
                    <div class="w-full h-1.5 bg-emerald-800/50 rounded-full mt-6 overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ min($incPercent, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Belanja -->
            @php 
                $sumExpTarget = $reportData->where('jenis', 'pengeluaran')->sum('totalTarget');
                $sumExpReal = $reportData->where('jenis', 'pengeluaran')->sum('totalRealization');
                $expPercent = $sumExpTarget > 0 ? ($sumExpReal / $sumExpTarget) * 100 : 0;
            @endphp
            <div class="bg-rose-600 p-8 rounded-[40px] text-white shadow-xl shadow-rose-500/20 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black uppercase opacity-60 tracking-[0.2em]">Total Belanja</p>
                        <span class="bg-rose-500/50 px-2 py-1 rounded text-[10px] font-bold">{{ number_format($expPercent, 1) }}%</span>
                    </div>
                    <h3 class="text-3xl font-black tracking-tighter">Rp {{ number_format($sumExpReal, 0, ',', '.') }}</h3>
                    <p class="text-xs font-medium opacity-80 mt-1">Pagu: Rp {{ number_format($sumExpTarget, 0, ',', '.') }}</p>
                    <div class="w-full h-1.5 bg-rose-800/50 rounded-full mt-6 overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ min($expPercent, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden print:border-none print:shadow-none print:rounded-none">
            <!-- Kop Print -->
            <div class="hidden print:block text-center p-8 border-b-2 border-slate-900 mb-4">
                <h1 class="text-2xl font-black uppercase tracking-widest text-slate-900">Gereja Kristen Sumba</h1>
                <h2 class="text-xl font-bold uppercase text-slate-600">Laporan Realisasi Anggaran</h2>
                <p class="text-sm font-medium mt-2 italic">Tahun: {{ $selectedYear->tahun }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest print:bg-slate-200 print:text-slate-900">
                        <tr>
                            <th class="px-8 py-5 w-1/3">Kode & Uraian</th>
                            <th class="px-6 py-5 text-right">Target</th>
                            <th class="px-6 py-5 text-right">Realisasi</th>
                            <th class="px-8 py-5 text-center">Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($reportData as $parent)
                            <!-- Level 1 (Induk) -->
                            <tr class="bg-slate-50 font-black">
                                <td class="px-8 py-4 flex items-center gap-3">
                                    <span class="bg-primary text-white px-2 py-1 rounded text-[10px] print:text-slate-900 print:bg-transparent print:border print:border-slate-400">{{ $parent['kode'] }}</span>
                                    <span class="text-slate-900 uppercase tracking-wider">{{ $parent['nama'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($parent['totalTarget'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right {{ $parent['jenis'] == 'pemasukan' ? 'text-emerald-700' : 'text-rose-700' }}">Rp {{ number_format($parent['totalRealization'], 0, ',', '.') }}</td>
                                <td class="px-8 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700 text-[10px] font-black">{{ number_format($parent['percentage'], 1) }}%</span>
                                </td>
                            </tr>

                            @foreach($parent['children'] as $child)
                                <!-- Level 2 (Kategori) -->
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-12 py-3">
                                        <div class="flex items-center gap-3 border-l-4 border-slate-200 pl-4">
                                            <span class="font-mono text-[10px] font-bold text-slate-400">{{ $child['kode'] }}</span>
                                            <span class="font-bold text-slate-700">{{ $child['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right text-xs font-bold text-slate-400">Rp {{ number_format($child['totalTarget'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-right text-sm font-black text-slate-800">Rp {{ number_format($child['totalRealization'], 0, ',', '.') }}</td>
                                    <td class="px-8 py-3 text-center text-xs font-bold text-slate-500">{{ number_format($child['percentage'], 0) }}%</td>
                                </tr>

                                @foreach($child['children'] as $sub)
                                    <!-- Level 3 (Sub-Pos) -->
                                    <tr class="text-[11px] text-slate-500 hover:bg-yellow-50/50 transition-colors">
                                        <td class="px-20 py-2">
                                            <div class="flex items-center gap-2 border-l border-dashed border-slate-300 pl-4">
                                                <span class="font-mono text-slate-400">{{ $sub['kode'] }}</span>
                                                <span>{{ $sub['nama'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-2 text-right text-slate-400 italic">{{ number_format($sub['totalTarget'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-2 text-right font-bold text-slate-700">{{ number_format($sub['totalRealization'], 0, ',', '.') }}</td>
                                        <td class="px-8 py-2 text-center text-[10px] text-slate-400">{{ number_format($sub['percentage'], 0) }}%</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Total -->
            <div class="bg-slate-900 text-white p-6 mt-6 rounded-[32px] flex justify-between items-center print:bg-white print:text-slate-900 print:border-t-2 print:border-slate-900">
                <span class="text-xs font-black uppercase tracking-widest">Saldo Kas (Surplus/Defisit)</span>
                <span class="text-2xl font-black">Rp {{ number_format($sumIncReal - $sumExpReal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>