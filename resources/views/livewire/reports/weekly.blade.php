<div class="py-10 bg-gray-100 min-h-screen font-sans text-slate-900">
    <div class="max-w-[210mm] mx-auto"> <!-- Ukuran A4 -->

        <!-- CONTROL BAR (SCREEN ONLY) -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-wrap gap-4 items-end print:hidden">
            <div class="flex-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Periode Laporan</label>
                <div class="flex items-center gap-2 mt-1">
                    <input wire:model.live="startDate" type="date" class="border-slate-200 rounded-lg text-sm font-semibold focus:ring-primary">
                    <span class="text-slate-400 font-bold">-</span>
                    <input wire:model.live="endDate" type="date" class="border-slate-200 rounded-lg text-sm font-semibold focus:ring-primary">
                </div>
            </div>
            <button onclick="window.print()" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Dokumen
            </button>
        </div>

        <!-- DOKUMEN CETAK -->
        <div class="bg-white p-12 shadow-2xl min-h-[297mm] print:shadow-none print:p-0">

            <!-- KOP -->
            <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-8">
                <h1 class="text-2xl font-black uppercase tracking-widest leading-none">Gereja Kristen Sumba</h1>
                <h2 class="text-xl font-bold text-slate-600 uppercase mt-1">Jemaat Reda Pada</h2>
                <div class="mt-4 inline-block bg-slate-900 text-white px-8 py-1.5 text-xs font-black tracking-[0.3em] uppercase">
                    Warta Jemaat
                </div>
                <p class="text-xs font-semibold mt-3 text-slate-500 italic">
                    Edisi: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-10">

                <!-- 1. AGENDA PELAYANAN -->
                <section>
                    <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-slate-900 rounded-full"></span>
                        Jadwal Pelayanan Sepekan
                    </h3>
                    @if($schedules->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($schedules as $sch)
                        <div class="flex items-start gap-4 p-3 border border-slate-100 rounded-xl bg-slate-50/50 print:border-none print:p-0 print:bg-transparent">
                            <div class="w-20 pt-1 text-center border-r border-slate-200 pr-4 print:border-r print:border-slate-400">
                                <span class="block text-xs font-black uppercase">{{ $sch->tanggal->isoFormat('dddd') }}</span>
                                <span class="block text-lg font-black text-slate-700 leading-none">{{ $sch->tanggal->format('d') }}</span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ $sch->tanggal->format('M') }} • {{ $sch->jam_mulai->format('H:i') }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-sm text-slate-900 uppercase tracking-tight">
                                        {{ $sch->tema ?? ($sch->family ? 'Ibadah Syukur Kel. '.$sch->family->kepala_keluarga : $sch->type->nama) }}
                                    </h4>
                                    <span class="text-[10px] font-bold bg-white border border-slate-200 px-2 py-0.5 rounded uppercase text-slate-500 print:hidden">{{ $sch->wilayah->nama ?? 'Umum' }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mb-2 italic">Lokasi: {{ $sch->lokasi_display }}</p>

                                <!-- Tim -->
                                <div class="text-xs grid grid-cols-2 gap-x-4 gap-y-1 mt-2 border-t border-slate-200 pt-2 border-dashed">
                                    <div class="flex gap-2">
                                        <span class="font-bold text-slate-400 w-16">Firman:</span>
                                        <span class="font-bold text-slate-800">{{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? '-' }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-bold text-slate-400 w-16">Tim:</span>
                                        <span class="text-slate-600 truncate">{{ $sch->servants->where('peran', 'Pendamping')->count() }} Orang Pendamping</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-center text-sm text-slate-400 italic py-4">Belum ada agenda pelayanan.</p>
                    @endif
                </section>

                <!-- 2. INFO JEMAAT (ULANG TAHUN) -->
                @if($birthdays->isNotEmpty())
                <section class="break-inside-avoid">
                    <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                        Jemaat Berulang Tahun
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                        @foreach($birthdays as $bday)
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-400 w-8">{{ $bday->tanggal_lahir->format('d/m') }}</span>
                            <span class="font-bold text-slate-700">{{ $bday->nama }}</span>
                            <span class="text-[9px] text-slate-400 italic">({{ $bday->tanggal_lahir->age + 1 }} Th)</span>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- 3. HIGHLIGHT KEUANGAN (MIMBAR) -->
                <section class="break-inside-avoid">
                    <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        Informasi Persembahan (Mimbar)
                    </h3>

                    <div class="grid grid-cols-2 gap-8 mb-6">
                        <div class="bg-slate-50 p-4 border-l-4 border-slate-300 print:bg-transparent print:border-l-2 print:border-slate-800 print:p-2">
                            <p class="text-[10px] font-bold uppercase text-slate-500 tracking-wider">Persembahan Minggu Lalu</p>
                            <p class="text-lg font-black text-slate-900">Rp {{ number_format($totalMingguLalu, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 border-l-4 border-emerald-300 print:bg-transparent print:border-l-2 print:border-slate-800 print:p-2">
                            <p class="text-[10px] font-bold uppercase text-emerald-600 print:text-slate-500 tracking-wider">Anak Sekolah Minggu</p>
                            <p class="text-lg font-black text-slate-900">Rp {{ number_format($totalASM, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Rincian PKS & Lelang -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase mb-2 border-b border-dotted border-slate-300 pb-1">Rincian PKS Rumah Tangga</h4>
                            @if($detailPKS->isNotEmpty())
                            <ul class="text-[11px] space-y-1">
                                @foreach($detailPKS as $pks)
                                <li class="flex justify-between">
                                    <span class="text-slate-600 truncate w-2/3">{{ $pks->keterangan }}</span>
                                    <span class="font-bold text-slate-900">Rp {{ number_format($pks->nominal, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-[10px] text-slate-400 italic">- Nihil -</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase mb-2 border-b border-dotted border-slate-300 pb-1">Rincian Lelang & Pembangunan</h4>
                            @if($detailLelang->isNotEmpty())
                            <ul class="text-[11px] space-y-1">
                                @foreach($detailLelang as $l)
                                <li class="flex justify-between">
                                    <span class="text-slate-600 truncate w-2/3">{{ $l->keterangan }}</span>
                                    <span class="font-bold text-slate-900">Rp {{ number_format($l->nominal, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-[10px] text-slate-400 italic">- Nihil -</p>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- 4. REKAPITULASI KAS (TABEL) -->
                <section class="break-inside-avoid">
                    <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        Rekapitulasi Kas Jemaat (Umum)
                    </h3>

                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="border-y-2 border-slate-800 font-bold uppercase text-slate-700">
                                <th class="py-2 text-left">Uraian</th>
                                <th class="py-2 text-right">Masuk</th>
                                <th class="py-2 text-right">Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="font-bold text-slate-500 border-b border-slate-100 italic">
                                <td class="py-2">Saldo Awal Periode</td>
                                <td class="py-2 text-right">{{ number_format($saldoAwalUmum, 0, ',', '.') }}</td>
                                <td class="py-2 text-right">-</td>
                            </tr>
                            @foreach($pemasukanUmum as $budgetId => $transactions)
                            @foreach($transactions as $t)
                            <tr class="border-b border-slate-50">
                                <td class="py-1.5 pl-8 text-slate-700">• {{ $t->keterangan ?? '-' }}</td>
                                <td class="py-1.5 text-right">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @endforeach

                            @foreach($pengeluaranUmum as $p)
                            <tr class="border-b border-slate-50">
                                <td class="py-1.5 text-slate-700 pl-4 indent-[-4px]">• {{ $p->budgetPost->nama ?? 'Lainnya' }}</td>
                                <td class="py-1.5 text-right">-</td>
                                <td class="py-1.5 text-right font-medium">{{ number_format($p->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-800 font-black bg-slate-50 print:bg-transparent">
                                <td class="py-2 uppercase">Saldo Akhir</td>
                                <td colspan="2" class="py-2 text-right text-base">Rp {{ number_format($saldoAwalUmum + $totalMasukUmum - $totalKeluarUmum, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </section>

                <!-- 5. KAS PEMBANGUNAN -->
                <section class="break-inside-avoid">
                    <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        Rekapitulasi Dana Pembangunan
                    </h3>
                    <div class="grid grid-cols-2 text-xs border border-slate-200 rounded-lg p-4 gap-y-2 print:border-slate-800">
                        <div class="text-slate-500">Saldo Awal Pembangunan</div>
                        <div class="text-right font-bold">{{ number_format($saldoAwalBangun, 0, ',', '.') }}</div>

                        <div class="text-slate-500">Penerimaan Minggu Ini</div>
                        <div class="text-right font-bold text-emerald-700 print:text-black">+ {{ number_format($totalMasukBangun, 0, ',', '.') }}</div>

                        <div class="text-slate-500">Pengeluaran Minggu Ini</div>
                        <div class="text-right font-bold text-rose-700 print:text-black">- {{ number_format($totalKeluarBangun, 0, ',', '.') }}</div>

                        <div class="border-t border-slate-300 col-span-2 my-1"></div>

                        <div class="font-black text-slate-900 uppercase tracking-wide">Saldo Akhir Pembangunan</div>
                        <div class="text-right font-black text-sm">Rp {{ number_format($saldoAwalBangun + $totalMasukBangun - $totalKeluarBangun, 0, ',', '.') }}</div>
                    </div>
                </section>

            </div>

            <!-- TTD -->
            <div class="mt-20 grid grid-cols-2 text-center text-xs break-inside-avoid">
                <div class="px-8">
                    <p class="mb-20 font-bold uppercase text-slate-500">Ketua Majelis Jemaat</p>
                    <p class="font-bold border-b border-slate-900 pb-1">Pdt. .....................................</p>
                </div>
                <div class="px-8">
                    <p class="mb-20 font-bold uppercase text-slate-500">Bendahara Jemaat</p>
                    <p class="font-bold border-b border-slate-900 pb-1 uppercase">{{ auth()->user()->name }}</p>
                </div>
            </div>

        </div>
    </div>
</div>