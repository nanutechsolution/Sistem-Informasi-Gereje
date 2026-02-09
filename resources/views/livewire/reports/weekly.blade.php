<div class="py-6 sm:py-10 bg-slate-100 min-h-screen">
    <div class="max-w-5xl mx-auto px-4">
        
        <!-- Toolbar Kontrol -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200 mb-8 flex flex-wrap gap-4 items-end print:hidden">
            <div class="flex-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Laporan Keuangan</label>
                <div class="flex items-center gap-2 mt-1">
                    <input wire:model.live="startDate" type="date" class="w-full border-slate-200 rounded-xl text-sm font-bold">
                    <span class="text-slate-300 font-bold">s/d</span>
                    <input wire:model.live="endDate" type="date" class="w-full border-slate-200 rounded-xl text-sm font-bold">
                </div>
            </div>
            <button onclick="window.print()" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                Cetak Warta Utuh
            </button>
        </div>

        <!-- HALAMAN KERTAS WARTA -->
        <div class="bg-white p-10 sm:p-20 shadow-2xl min-h-[297mm] text-slate-900 border border-slate-200 print:shadow-none print:p-0">
            
            <!-- Kop Warta -->
            <div class="text-center border-b-4 border-double border-slate-900 pb-8 mb-12">
                <h1 class="text-3xl font-black uppercase tracking-[0.2em] leading-tight">Gereja Kristen Sumba</h1>
                <h2 class="text-xl font-bold text-slate-600 mt-1 uppercase italic">Jemaat Reda Pada</h2>
                <div class="mt-6 inline-block border-2 border-slate-900 px-8 py-1.5 text-[12px] font-black tracking-[0.4em] uppercase">
                    Warta Jemaat
                </div>
                <p class="text-xs font-bold mt-6 text-slate-400 italic">Edisi Minggu, {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
            </div>

            <!-- BAGIAN I: AGENDA PELAYANAN PEKAN INI -->
            <div class="mb-16">
                <h3 class="text-xs font-black bg-slate-900 text-white px-5 py-2.5 rounded-lg inline-block uppercase tracking-[0.2em] mb-8 italic">I. Agenda & Jadwal Pelayanan</h3>
                
                <div class="space-y-10">
                    @forelse($schedules as $sch)
                    <div class="border-l-4 border-slate-200 pl-6 relative">
                        <div class="absolute -left-[9px] top-0 h-4 w-4 rounded-full bg-white border-4 border-slate-900"></div>
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-black uppercase tracking-tight text-primary">{{ $sch->type->nama }} - {{ $sch->tema ?? 'Rutin' }}</h4>
                            <span class="text-[10px] font-black text-slate-400 uppercase italic">{{ $sch->tanggal->isoFormat('dddd, D MMM Y') }} • {{ $sch->jam_mulai->format('H:i') }}</span>
                        </div>
                        <p class="text-xs font-bold text-slate-500 mb-4">Lokasi: {{ $sch->lokasi_display }}</p>
                        
                        <!-- List Pelayan -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach($sch->servants as $servant)
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">{{ $servant->peran }}</p>
                                <p class="text-[11px] font-bold text-slate-700 italic">{{ $servant->member->nama }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-300 italic text-center py-10 border-2 border-dashed border-slate-100 rounded-3xl">Belum ada agenda yang divalidasi.</p>
                    @endforelse
                </div>
            </div>

            <!-- BAGIAN II: LAPORAN KEUANGAN PEKAN LALU -->
            <div class="mb-16 break-inside-avoid">
                <h3 class="text-xs font-black bg-slate-900 text-white px-5 py-2.5 rounded-lg inline-block uppercase tracking-[0.2em] mb-8 italic">II. Realisasi Keuangan Kas Jemaat</h3>
                
                <table class="w-full text-[11px] border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-y-2 border-slate-900 font-black uppercase italic">
                            <td class="py-3 px-3">Uraian / Pos Anggaran</td>
                            <td class="py-3 px-3 text-right">Pemasukan</td>
                            <td class="py-3 px-3 text-right">Pengeluaran</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-bold text-slate-400 border-b border-slate-100">
                            <td class="py-3 px-3 italic">Saldo Awal per {{ \Carbon\Carbon::parse($startDate)->format('d/m') }}</td>
                            <td class="py-3 px-3 text-right font-mono">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right">-</td>
                        </tr>
                        
                        {{-- Pemasukan --}}
                        @foreach($pemasukan as $p)
                        <tr class="border-b border-slate-50">
                            <td class="py-2.5 px-3 text-slate-700 font-medium">{{ $p->budgetPost->nama ?? 'Lain-lain' }}</td>
                            <td class="py-2.5 px-3 text-right font-bold text-emerald-700">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 text-right">-</td>
                        </tr>
                        @endforeach

                        {{-- Pengeluaran --}}
                        @foreach($pengeluaran as $p)
                        <tr class="border-b border-slate-50">
                            <td class="py-2.5 px-3 text-slate-700 font-medium">{{ $p->budgetPost->nama ?? 'Umum' }}</td>
                            <td class="py-2.5 px-3 text-right">-</td>
                            <td class="py-2.5 px-3 text-right font-bold text-rose-700">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white font-black text-xs italic">
                            <td class="py-4 px-3 uppercase tracking-tighter">Saldo Akhir Kas (Tersedia)</td>
                            <td colspan="2" class="py-4 px-3 text-right text-sm font-mono tracking-tighter">
                                Rp {{ number_format($saldoAwal + $totalMasuk - $totalKeluar, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- BAGIAN III: INFO JEMAAT (ULANG TAHUN) -->
            <div class="mb-12 break-inside-avoid">
                <h3 class="text-xs font-black bg-slate-900 text-white px-5 py-2.5 rounded-lg inline-block uppercase tracking-[0.2em] mb-8 italic">III. Kabar Sukacita Jemaat</h3>
                <div class="p-6 bg-yellow-50 rounded-3xl border-2 border-yellow-100 flex flex-wrap gap-8 justify-center">
                    @forelse($birthdays as $bday)
                    <div class="text-center">
                        <p class="text-[11px] font-black text-slate-900 uppercase leading-none">{{ $bday->nama }}</p>
                        <p class="text-[9px] font-bold text-yellow-600 mt-1 italic">{{ $bday->tanggal_lahir->format('d F') }}</p>
                    </div>
                    @empty
                    <p class="text-[10px] text-slate-300 font-bold italic">Pekan ini tidak ada jemaat yang berulang tahun.</p>
                    @endforelse
                </div>
            </div>

            <!-- PENUTUP & TTD -->
            <div class="mt-24 grid grid-cols-2 text-center text-[11px] break-inside-avoid">
                <div class="px-10">
                    <p class="mb-24 font-black uppercase tracking-widest text-slate-400">Ketua Majelis Jemaat</p>
                    <p class="font-black border-b-2 border-slate-900 pb-1 italic uppercase">Pdt. Alponia Malo, S.Th</p>
                </div>
                <div class="px-10">
                    <p class="mb-24 font-black uppercase tracking-widest text-slate-400 text-right">Lolo Ole, {{ date('d F Y') }}<br><span class="text-slate-900">Bendahara Jemaat</span></p>
                    <p class="font-black border-b-2 border-slate-900 pb-1 uppercase">{{ auth()->user()->name }}</p>
                </div>
            </div>

        </div>
    </div>

<style>
@media print {
    body { background: white !important; }
    .bg-slate-100 { background-color: white !important; }
    nav, .print\:hidden { display: none !important; }
    .shadow-2xl { box-shadow: none !important; }
}
</style>

</div>
