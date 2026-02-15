<div class="py-10 bg-slate-100 min-h-screen font-serif text-slate-900">
    <div class="max-w-[210mm] mx-auto"> <!-- Kontainer Ukuran A4 -->

        <!-- CONTROL BAR (SCREEN ONLY) -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-wrap gap-4 items-end print:hidden">
            <div class="flex-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Warta</label>
                <div class="flex items-center gap-2 mt-1">
                    <input wire:model.live="startDate" type="date" class="border-slate-200 rounded-xl text-sm font-bold focus:ring-primary">
                    <span class="text-slate-400 font-bold">-</span>
                    <input wire:model.live="endDate" type="date" class="border-slate-200 rounded-xl text-sm font-bold focus:ring-primary">
                </div>
            </div>
            <button onclick="window.print()" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary transition shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Warta
            </button>
        </div>

        <!-- DOKUMEN WARTA JEMAAT -->
        <div class="bg-white p-[15mm] shadow-2xl min-h-[297mm] print:shadow-none print:p-0">

            <!-- HEADER / KOP -->
            <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-8">
                <h1 class="text-3xl font-black uppercase tracking-widest leading-none">Gereja Kristen Sumba</h1>
                <h2 class="text-xl font-bold text-slate-700 uppercase mt-1">Jemaat Reda Pada</h2>
                <div class="mt-4 inline-block bg-slate-900 text-white px-10 py-1.5 text-[10px] font-black tracking-[0.4em] uppercase rounded-sm">
                    Warta Jemaat
                </div>
                <p class="text-xs font-bold mt-4 text-slate-500 italic">
                    Edisi: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
                </p>
            </div>

            <div class="space-y-10">

                <!-- 1. AGENDA PELAYANAN SEPEKAN -->
                <section>
                    <h3 class="text-xs font-black uppercase tracking-widest border-b border-slate-300 pb-1.5 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-slate-900 rotate-45"></span>
                        Jadwal Pelayanan & Kegiatan Jemaat
                    </h3>
                    <div class="space-y-6">
                        @forelse($schedules as $sch)
                        <div class="flex items-start gap-4 border-b border-slate-100 pb-4 last:border-0">
                            <div class="w-16 pt-1 text-center shrink-0">
                                <span class="block text-[10px] font-black uppercase text-slate-400">{{ $sch->tanggal->isoFormat('dddd') }}</span>
                                <span class="block text-2xl font-black text-slate-900 leading-none">{{ $sch->tanggal->format('d') }}</span>
                                <span class="block text-[9px] font-bold text-slate-500 uppercase">{{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }} WITA</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-black text-sm text-slate-900 uppercase leading-tight">
                                        {{ $sch->tema ?: ($sch->family ? 'Ibadah Kel. '.$sch->family->members->first()?->churchPeople?->full_name : $sch->type->nama) }}
                                    </h4>
                                    <span class="text-[9px] font-black text-slate-400 border border-slate-200 px-1.5 rounded uppercase">{{ $sch->family->wilayah->nama ?? 'Umum' }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    <span class="font-bold">Lokasi:</span> {{ $sch->family->alamat ?? $sch->lokasi_manual ?? 'Gedung Gereja' }}
                                </p>
                                
                                {{-- Detail Tim Pelayanan / Anggota --}}
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-[11px] bg-slate-50 p-2 rounded-lg border-l-2 border-slate-200 print:bg-transparent print:border-l print:p-1">
                                    <div class="flex gap-2">
                                        <span class="font-black text-slate-400 uppercase tracking-tighter w-16 shrink-0">PF:</span>
                                        <span class="font-black text-slate-800">{{ $sch->servants->where('peran', 'Pembaca Firman')->first()?->member?->churchPeople?->full_name ?? '-' }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-black text-slate-400 uppercase tracking-tighter w-16 shrink-0">Anggota:</span>
                                        <div class="text-slate-600 italic leading-snug">
                                            @php
                                                $pendampingNames = $sch->servants->where('peran', 'Pendamping')->map(fn($s) => $s->member->churchPeople->full_name);
                                            @endphp
                                            {{ count($pendampingNames) ? $pendampingNames->join(', ') : 'Tidak ada anggota pendamping' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-xs text-slate-400 italic py-4">Tidak ada jadwal pelayanan dalam periode ini.</p>
                        @endforelse
                    </div>
                </section>

                <!-- 2. INFO ULANG TAHUN -->
                @if(count($birthdays))
                <section class="break-inside-avoid">
                    <h3 class="text-xs font-black uppercase tracking-widest border-b border-slate-300 pb-1.5 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-amber-500 rotate-45"></span>
                        Selamat Hari Ulang Tahun
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-2">
                        @foreach($birthdays as $bday)
                        <div class="flex items-center gap-3 text-xs py-1 border-b border-slate-50">
                            <span class="font-black text-slate-300">{{ \Carbon\Carbon::parse($bday->churchPeople->date_of_birth)->format('d/m') }}</span>
                            <span class="font-bold text-slate-800 uppercase tracking-tighter truncate">{{ $bday->churchPeople->full_name }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- 3. HIGHLIGHT KEUANGAN (MIMBAR) -->
                <section class="break-inside-avoid">
                    <h3 class="text-xs font-black uppercase tracking-widest border-b border-slate-300 pb-1.5 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rotate-45"></span>
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
                            @if(count($detailPKS))
                            <ul class="text-[11px] space-y-3">
                                @foreach($detailPKS as $pks)
                                <li class="border-b border-slate-50 pb-2 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <span class="text-slate-800 font-bold uppercase tracking-tighter w-2/3 leading-tight">{{ $pks->keterangan }}</span>
                                        <span class="font-black text-slate-900">Rp {{ number_format($pks->nominal, 0, ',', '.') }}</span>
                                    </div>
                                    @if($pks->activitySchedule)
                                    <div class="mt-1 text-[9px] text-slate-500 leading-relaxed italic">
                                        PF: {{ $pks->activitySchedule->servants->where('peran', 'Pembaca Firman')->first()?->member?->churchPeople?->full_name ?? '-' }}
                                        | Anggota: {{ $pks->activitySchedule->servants->where('peran', 'Pendamping')->map(fn($s) => $s->member->churchPeople->full_name)->join(', ') ?: '-' }}
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-[10px] text-slate-400 italic">- Nihil -</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase mb-2 border-b border-dotted border-slate-300 pb-1">Rincian Lelang & Pembangunan</h4>
                            @if(count($detailLelang))
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

                <!-- 4. REKAPITULASI KAS JEMAAT (UMUM) -->
                <section class="break-inside-avoid">
                    <h3 class="text-xs font-black uppercase tracking-widest border-b border-slate-300 pb-1.5 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-600 rotate-45"></span>
                        Laporan Keuangan Kas Jemaat
                    </h3>
                    <table class="w-full text-[11px] border-collapse">
                        <thead>
                            <tr class="border-y-2 border-slate-900 font-black uppercase bg-slate-50">
                                <th class="py-2 text-left px-2">Keterangan / Uraian Transaksi</th>
                                <th class="py-2 text-right px-2">Penerimaan (Rp)</th>
                                <th class="py-2 text-right px-2">Pengeluaran (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="font-black text-slate-500 italic border-b border-slate-100">
                                <td class="py-2 px-2">SALDO AWAL PERIODE</td>
                                <td class="py-2 text-right px-2">{{ number_format($saldoAwalUmum, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            
                            {{-- PEMASUKAN --}}
                            @foreach($pemasukanUmum as $groupId => $items)
                                @foreach($items as $t)
                                <tr class="border-b border-slate-50">
                                    <td class="py-1.5 px-4 text-slate-700 uppercase tracking-tighter">{{ $t->keterangan }}</td>
                                    <td class="py-1.5 text-right px-2">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            @endforeach

                            {{-- PENGELUARAN --}}
                            @foreach($pengeluaranUmum as $groupId => $items)
                                @foreach($items as $t)
                                <tr class="border-b border-slate-50 italic">
                                    <td class="py-1.5 px-4 text-slate-600 uppercase tracking-tighter">Potongan/Biaya: {{ $t->budgetPost->nama }}</td>
                                    <td></td>
                                    <td class="py-1.5 text-right px-2">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-900 bg-slate-900 text-white font-black">
                                <td class="py-2 px-2 uppercase text-xs">Saldo Akhir Kas Jemaat</td>
                                <td colspan="2" class="py-2 text-right px-2 text-sm font-mono">
                                    Rp {{ number_format($saldoAwalUmum + $totalMasukUmum - $totalKeluarUmum, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </section>

                <!-- 5. KAS PEMBANGUNAN (Compact) -->
                <section class="break-inside-avoid">
                    <h3 class="text-xs font-black uppercase tracking-widest border-b border-slate-300 pb-1.5 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-600 rotate-45"></span>
                        Dana Pembangunan & Investasi
                    </h3>
                    <div class="grid grid-cols-2 text-xs border-2 border-slate-900 p-4 gap-y-2">
                        <div class="text-slate-500 font-bold uppercase">Saldo Awal</div>
                        <div class="text-right font-black">Rp {{ number_format($saldoAwalBangun, 0, ',', '.') }}</div>

                        <div class="text-slate-500 font-bold uppercase">Penerimaan (Lelang/Aksi)</div>
                        <div class="text-right font-bold text-emerald-700">+ {{ number_format($totalMasukBangun, 0, ',', '.') }}</div>

                        <div class="text-slate-500 font-bold uppercase">Pengeluaran</div>
                        <div class="text-right font-bold text-rose-700">- {{ number_format($totalKeluarBangun, 0, ',', '.') }}</div>

                        <div class="border-t-2 border-slate-900 col-span-2 my-1"></div>

                        <div class="font-black text-slate-900 uppercase">Saldo Akhir Pembangunan</div>
                        <div class="text-right font-black text-sm font-mono">Rp {{ number_format($saldoAwalBangun + $totalMasukBangun - $totalKeluarBangun, 0, ',', '.') }}</div>
                    </div>
                </section>

            </div>

            <!-- PENUTUP & TANDA TANGAN -->
            <div class="mt-20 flex justify-between text-center text-xs break-inside-avoid px-10">
                <div class="w-64">
                    <p class="mb-20 font-black uppercase text-slate-400 tracking-widest">Ketua Majelis Jemaat</p>
                    <p class="font-black border-b border-slate-900 pb-1 uppercase">Pdt. .....................................</p>
                </div>
                <div class="w-64">
                    <p class="mb-20 font-black uppercase text-slate-400 tracking-widest">Bendahara Jemaat</p>
                    <p class="font-black border-b border-slate-900 pb-1 uppercase">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <!-- FOOTER DOKUMEN -->
            <div class="mt-20 pt-4 border-t border-slate-100 text-[8px] text-slate-400 text-center uppercase tracking-widest italic">
                Dokumen Digital SIG-GKS Jemaat Reda Pada | Dicetak {{ now()->format('d/m/Y H:i') }}
            </div>

        </div>
    </div>

<style>
    @media print {
        body { background: white !important; }
        .bg-gray-100 { background: white !important; }
        .shadow-2xl { shadow: none !important; }
        @page { margin: 10mm; }
    }
    /* Sembunyikan scrollbar pada navigasi warta */
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
</div>
