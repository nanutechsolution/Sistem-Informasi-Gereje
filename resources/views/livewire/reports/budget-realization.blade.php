<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic">Audit Realisasi RAPB</h1>
                <p class="text-slate-500 mt-3 font-medium">Pantau perbandingan rencana dan penggunaan anggaran secara mendetail.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-2">
                    <span class="pl-3 text-[10px] font-black text-slate-400 uppercase">Tahun Anggaran:</span>
                    <select wire:model.live="fiscalYearId" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer">
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->id }}">{{ $fy->tahun }} {{ $fy->is_active ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="window.print()" class="p-4 bg-slate-900 text-white rounded-2xl shadow-lg hover:bg-slate-800 transition-all print:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-8 py-6">Kode & Uraian Anggaran</th>
                        <th class="px-6 py-6 text-right">Target (RAPB)</th>
                        <th class="px-6 py-6 text-right">Realisasi</th>
                        <th class="px-8 py-6 text-center">Capaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reportData as $data)
                        {{-- LEVEL 1: Kategori Utama (PENDAPATAN / BELANJA) --}}
                        <tr class="bg-slate-50 font-black">
                            <td class="px-8 py-5 flex items-center gap-3">
                                <span class="bg-primary text-white px-2 py-1 rounded text-[10px]">{{ $data['kode'] }}</span>
                                <span class="text-slate-900 uppercase tracking-wider">{{ $data['nama'] }}</span>
                            </td>
                            <td class="px-6 py-5 text-right font-black">Rp {{ number_format($data['totalTarget'], 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-right font-black text-primary">Rp {{ number_format($data['totalRealization'], 0, ',', '.') }}</td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-primary text-[10px] font-black">
                                    {{ number_format($data['percentage'], 1) }}%
                                </span>
                            </td>
                        </tr>

                        {{-- LEVEL 2 & 3: Sub-Pos (Contoh: Pemeliharaan Pengerja -> Pdt. Alponia) --}}
                        @foreach($data['children'] as $child)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-12 py-4">
                                    <div class="flex items-center gap-3 border-l-4 border-slate-200 pl-4">
                                        <span class="font-mono text-[10px] font-bold text-slate-400 group-hover:text-primary transition-colors">{{ $child['kode'] }}</span>
                                        <span class="font-bold text-slate-700">{{ $child['nama'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-400 italic">Rp {{ number_format($child['totalTarget'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-black text-slate-800">Rp {{ number_format($child['totalRealization'], 0, ',', '.') }}</td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $child['percentage'] > 100 ? 'bg-rose-500' : 'bg-emerald-500' }} transition-all duration-500" style="width: {{ min($child['percentage'], 100) }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 w-10 text-right">{{ number_format($child['percentage'], 0) }}%</span>
                                    </div>
                                </td>
                            </tr>

                            {{-- LEVEL 3: Rincian Paling Detail (jika ada) --}}
                            @if($child['has_children'])
                                @foreach($child['children'] as $subChild)
                                    <tr class="bg-gray-50/30 group">
                                        <td class="px-20 py-3">
                                            <div class="flex items-center gap-3 border-l-2 border-dashed border-slate-200 pl-4 italic">
                                                <span class="text-[10px] font-medium text-slate-400">{{ $subChild['kode'] }}</span>
                                                <span class="text-xs text-slate-500">{{ $subChild['nama'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-right text-xs font-medium text-slate-400">Rp {{ number_format($subChild['totalTarget'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-right text-xs font-bold text-emerald-600">Rp {{ number_format($subChild['totalRealization'], 0, ',', '.') }}</td>
                                        <td class="px-8 py-3 text-right text-[10px] font-bold text-slate-400">
                                            {{ number_format($subChild['percentage'], 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Footer -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6 print:mt-20">
            <div class="p-8 bg-slate-900 rounded-[40px] text-white">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2">Evaluasi Akhir</span>
                <p class="text-sm font-medium leading-relaxed opacity-80 italic">"Laporan ini menyajikan data real-time. Selisih target dan realisasi dapat menjadi acuan evaluasi program pelayanan pada sidang majelis berikutnya."</p>
            </div>
            <div class="flex flex-col justify-center items-end px-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Laporan</p>
                <p class="text-xs font-bold text-emerald-600 flex items-center gap-2 uppercase tracking-tighter">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sinkron dengan Jurnal Kas
                </p>
            </div>
        </div>

    </div>
</div>