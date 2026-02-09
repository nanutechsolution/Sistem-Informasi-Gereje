<div class="py-6 sm:py-10 bg-slate-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Toolbar Kontrol -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200 mb-8 flex flex-wrap gap-4 items-end print:hidden">
            <div class="flex-1 min-w-[240px]">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Warta (Senin - Minggu)</label>
                <div class="flex items-center gap-2 mt-1">
                    <input wire:model.live="startDate" type="date" class="w-full border-slate-200 rounded-xl text-sm focus:ring-primary font-bold">
                    <span class="text-slate-300 font-bold">s/d</span>
                    <input wire:model.live="endDate" type="date" class="w-full border-slate-200 rounded-xl text-sm focus:ring-primary font-bold">
                </div>
            </div>
            <button onclick="window.print()" class="px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-blue-800 transition shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Warta
            </button>
        </div>

        <!-- HALAMAN KERTAS LAPORAN -->
        <div class="bg-white p-10 sm:p-16 shadow-2xl min-h-[297mm] print:shadow-none print:p-0 text-slate-900 border border-slate-200">
            
            <!-- Kop Gereja -->
            <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-10">
                <h1 class="text-2xl font-black uppercase tracking-[0.2em] leading-tight">Gereja Kristen Sumba</h1>
                <h2 class="text-xl font-bold text-slate-600 mt-1 uppercase italic">Jemaat Reda Pada</h2>
                <div class="mt-4 inline-block bg-slate-900 text-white px-5 py-1 text-[10px] font-black tracking-[0.3em] uppercase rounded-full">
                    Warta Jemaat & Laporan Keuangan
                </div>
                <p class="text-xs font-semibold mt-4 text-slate-500 italic">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
                </p>
            </div>

            <!-- BAGIAN I: CATATAN PELAYANAN -->
            <div class="mb-12">
                <h3 class="text-xs font-black bg-slate-100 px-4 py-2 rounded-lg inline-block uppercase tracking-widest border-l-4 border-slate-900 mb-6 italic text-primary">I. Catatan Persembahan Khusus</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- PKS Summary -->
                    <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100">
                        <span class="block text-[9px] font-black text-blue-400 uppercase tracking-widest mb-3">PKS Rumah Tangga (Pos 1.2)</span>
                        <div class="space-y-2">
                            @forelse($detailPKS as $pks)
                                <div class="flex justify-between text-[11px] border-b border-blue-100 pb-1 italic">
                                    <span class="text-blue-800">{{ $pks->keterangan }}</span>
                                    <span class="font-bold">Rp {{ number_format($pks->nominal, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] text-blue-300 italic text-center">Tidak ada setoran PKS.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Lelang Summary -->
                    <div class="p-6 bg-amber-50 rounded-3xl border border-amber-200">
                        <span class="block text-[9px] font-black text-amber-500 uppercase tracking-widest mb-3">Hasil Lelang</span>
                        <div class="space-y-2">
                            @forelse($detailLelang as $l)
                                <div class="flex justify-between text-[11px] border-b border-amber-100 pb-1 italic">
                                    <span class="text-amber-800 truncate mr-2">{{ $l->keterangan }}</span>
                                    <span class="font-bold">Rp {{ number_format($l->nominal, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] text-amber-300 italic text-center">Tidak ada hasil lelang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN II: REKAP KAS UMUM -->
            <div class="mb-12">
                <h3 class="text-xs font-black bg-blue-900 text-white px-4 py-2 rounded-lg inline-block uppercase tracking-widest mb-6 italic">II. Rekapitulasi Kas Jemaat (Umum)</h3>
                <table class="w-full text-[11px] border-collapse">
                    <tr class="bg-slate-50 border-y-2 border-slate-900 font-black uppercase">
                        <td class="py-3 px-2">Uraian / Pos Anggaran</td>
                        <td class="py-3 px-2 text-right">Pemasukan</td>
                        <td class="py-3 px-2 text-right">Pengeluaran</td>
                    </tr>
                    <tr class="font-bold border-b border-slate-200 italic text-slate-400">
                        <td class="py-3 px-2">Saldo Awal Periode</td>
                        <td class="py-3 px-2 text-right font-mono">Rp {{ number_format($saldoAwalUmum, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right">-</td>
                    </tr>
                    
                    {{-- Loop Pemasukan --}}
                    @foreach($pemasukanUmum as $p)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-2 text-slate-600 font-medium">{{ $p->budgetPost->nama ?? 'Lain-lain' }}</td>
                            <td class="py-2 px-2 text-right font-bold text-emerald-700">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-right">-</td>
                        </tr>
                    @endforeach

                    {{-- Loop Pengeluaran (Termasuk GAJI yang bapak input) --}}
                    @foreach($pengeluaranUmum as $p)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-2 text-slate-600 font-medium">{{ $p->budgetPost->nama ?? 'Pengeluaran Tanpa Pos' }}</td>
                            <td class="py-2 px-2 text-right">-</td>
                            <td class="py-2 px-2 text-right font-bold text-rose-700">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                    @if($pemasukanUmum->isEmpty() && $pengeluaranUmum->isEmpty())
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 italic font-medium">-- Tidak ada aktivitas transaksi pekan ini --</td>
                    </tr>
                    @endif

                    <tr class="bg-slate-900 text-white font-black text-xs">
                        <td class="py-4 px-2 uppercase tracking-tighter italic">Saldo Akhir Kas Umum</td>
                        <td colspan="2" class="py-4 px-2 text-right text-sm font-mono tracking-tighter">
                            Rp {{ number_format($saldoAwalUmum + $totalMasukUmum - $totalKeluarUmum, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- BAGIAN III: REKAP KAS PEMBANGUNAN -->
            <div class="mb-12 break-inside-avoid">
                <h3 class="text-xs font-black bg-amber-600 text-white px-4 py-2 rounded-lg inline-block uppercase tracking-widest mb-6 italic">III. Kas Khusus Pembangunan</h3>
                <table class="w-full text-[11px] border-collapse border border-amber-200">
                    <tr class="bg-amber-50 border-y-2 border-amber-600 font-black text-amber-900">
                        <td class="py-3 px-2 uppercase tracking-tight italic">Status Dana Pembangunan</td>
                        <td class="py-3 px-2 text-right">TOTAL</td>
                    </tr>
                    <tr class="border-b border-amber-100">
                        <td class="py-2 px-2 text-slate-600 italic">Saldo Awal Periode</td>
                        <td class="py-2 px-2 text-right font-bold text-slate-800">Rp {{ number_format($saldoAwalBangun, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b border-amber-100">
                        <td class="py-2 px-2 text-slate-600">Total Pemasukan (Persembahan & Lelang)</td>
                        <td class="py-2 px-2 text-right font-black text-emerald-700">Rp {{ number_format($totalMasukBangun, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b border-amber-100">
                        <td class="py-2 px-2 text-slate-600">Total Pengeluaran Pekan ini</td>
                        <td class="py-2 px-2 text-right font-black text-rose-700">Rp {{ number_format($totalKeluarBangun, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-amber-600 text-white font-black text-xs">
                        <td class="py-4 px-2 uppercase tracking-tighter italic">Saldo Akhir Dana Pembangunan</td>
                        <td colspan="1" class="py-4 px-2 text-right text-sm font-mono tracking-tighter">
                            Rp {{ number_format($saldoAwalBangun + $totalMasukBangun - $totalKeluarBangun, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- TANDA TANGAN -->
            <div class="mt-20 grid grid-cols-2 text-center text-[11px] break-inside-avoid">
                <div class="px-10">
                    <p class="mb-24 font-bold uppercase tracking-widest text-slate-400 leading-relaxed">Mengetahui,<br><span class="text-slate-900">Ketua Majelis Jemaat</span></p>
                    <p class="font-black border-b-2 border-slate-900 pb-1 italic">Pdt. .....................................</p>
                </div>
                <div class="px-10">
                    <p class="mb-24 font-bold uppercase tracking-widest text-slate-400 leading-relaxed">Lolo Ole, {{ date('d F Y') }}<br><span class="text-slate-900">Bendahara Jemaat</span></p>
                    <p class="font-black border-b-2 border-slate-900 pb-1 uppercase">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="mt-16 text-center text-[8px] text-slate-300 uppercase tracking-[0.5em] print:block hidden">
                Dicetak otomatis melalui SIG-GKS Jemaat Reda Pada
            </div>

        </div>
    </div>
</div>