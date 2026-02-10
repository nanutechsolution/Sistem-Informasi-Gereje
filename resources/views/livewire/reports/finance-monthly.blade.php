<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10 print:hidden">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Laporan Keuangan Terpadu</h1>
                <p class="text-slate-500 mt-2 font-medium border-l-4 border-emerald-500 pl-4">Ringkasan Kas Umum & Pembangunan bulanan.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-200">
                <select wire:model.live="bulan" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer text-slate-700">
                    @foreach(range(1,12) as $m) <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option> @endforeach
                </select>
                <select wire:model.live="tahun" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer text-slate-700">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <button onclick="window.print()" class="p-2.5 bg-slate-900 text-white rounded-xl shadow-lg hover:scale-105 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
        </div>

        <div class="space-y-10 print:space-y-6">
            
            <!-- SECTION 1: KAS JEMAAT (UMUM) -->
            <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden print:border-slate-800">
                <div class="px-8 py-6 bg-slate-900 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-black uppercase italic tracking-widest">I. Kas Jemaat (Umum)</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $startDate->isoFormat('MMMM Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-slate-500 uppercase block">Status Dompet</span>
                        <span class="text-xs font-bold text-emerald-400 italic">{{ $kasUmum->nama ?? 'Kas Umum' }}</span>
                    </div>
                </div>

                <div class="p-8">
                    <table class="w-full text-sm border-collapse">
                        <tr class="font-black text-slate-400 uppercase text-[10px] tracking-widest border-b border-slate-100">
                            <th class="py-4 text-left">Uraian Transaksi</th>
                            <th class="py-4 text-right w-32">Penerimaan</th>
                            <th class="py-4 text-right w-32">Pengeluaran</th>
                        </tr>
                        <tr class="font-bold text-slate-900 bg-slate-50/50 italic">
                            <td class="py-4 pl-4">SALDO AWAL (Carry Over)</td>
                            <td class="py-4 text-right">Rp {{ number_format($saldoAwalUmum, 0, ',', '.') }}</td>
                            <td class="py-4 text-right">-</td>
                        </tr>
                        {{-- Penerimaan --}}
                        @foreach($mutasiUmum->where('jenis', 'masuk') as $m)
                        <tr class="border-b border-slate-50">
                            <td class="py-3 pl-6 text-slate-600 font-medium">{{ $m->budgetPost->nama ?? 'Lain-lain' }}</td>
                            <td class="py-3 text-right font-bold text-emerald-600">Rp {{ number_format($m->total, 0, ',', '.') }}</td>
                            <td class="py-3 text-right text-slate-300">-</td>
                        </tr>
                        @endforeach
                        {{-- Pengeluaran --}}
                        @foreach($mutasiUmum->whereIn('jenis', ['keluar', 'pindah_buku']) as $m)
                        <tr class="border-b border-slate-50">
                            <td class="py-3 pl-6 text-slate-600 font-medium">{{ $m->budgetPost->nama ?? 'Transfer Keluar' }}</td>
                            <td class="py-3 text-right text-slate-300">-</td>
                            <td class="py-3 text-right font-bold text-rose-500">Rp {{ number_format($m->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-slate-900 text-white font-black">
                            <td class="py-5 px-6 uppercase tracking-widest italic text-xs">Saldo Akhir Kas Umum</td>
                            <td colspan="2" class="py-5 px-6 text-right text-xl italic tracking-tighter">
                                Rp {{ number_format($saldoAwalUmum + $mutasiUmum->where('jenis','masuk')->sum('total') - $mutasiUmum->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('total'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: KAS PEMBANGUNAN -->
            <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden print:border-slate-800">
                <div class="px-8 py-6 bg-amber-500 text-white flex justify-between items-center">
                    <h2 class="text-lg font-black uppercase italic tracking-widest">II. Kas Khusus Pembangunan</h2>
                    <div class="text-right"><span class="text-[9px] font-black text-amber-900 uppercase block">Posisi Kas</span><span class="text-xs font-bold text-white italic">Target Pembangunan Gedung</span></div>
                </div>
                <div class="p-8 text-sm">
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 flex justify-between items-center">
                            <div><p class="text-[9px] font-black text-slate-400 uppercase">Saldo Awal</p><p class="text-xl font-black text-slate-900 leading-tight">Rp {{ number_format($saldoAwalBangun, 0, ',', '.') }}</p></div>
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-slate-300 italic font-black">S</div>
                        </div>
                        <div class="p-6 rounded-3xl bg-amber-50 border border-amber-100 flex justify-between items-center">
                            <div><p class="text-[9px] font-black text-amber-600 uppercase">Masuk Bulan Ini</p><p class="text-xl font-black text-amber-700 leading-tight">Rp {{ number_format($mutasiBangun->where('jenis', 'masuk')->sum('total'), 0, ',', '.') }}</p></div>
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-amber-400 font-black">+</div>
                        </div>
                    </div>
                    {{-- Rekap Pengeluaran Material --}}
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pengeluaran Pembangunan:</h4>
                    <div class="space-y-2">
                        @foreach($mutasiBangun->whereIn('jenis', ['keluar', 'pindah_buku']) as $mb)
                        <div class="flex justify-between py-2 border-b border-dotted border-slate-200">
                            <span class="font-medium text-slate-600 uppercase text-xs">{{ $mb->budgetPost->nama ?? 'Belanja Material' }}</span>
                            <span class="font-bold text-rose-600 text-xs">- Rp {{ number_format($mb->total, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-8 pt-6 border-t-2 border-slate-900 flex justify-between items-center">
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-slate-900 italic">Total Dana Pembangunan Tersedia</span>
                        <span class="text-2xl font-black text-slate-900 italic tracking-tighter">Rp {{ number_format($saldoAwalBangun + $mutasiBangun->where('jenis','masuk')->sum('total') - $mutasiBangun->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('total'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- TTD -->
            <div class="mt-16 grid grid-cols-2 text-center text-xs print:mt-10">
                <div class="px-10">
                    <p class="mb-24 font-bold uppercase text-slate-400">Ketua Majelis Jemaat</p>
                    <p class="font-black text-slate-900 border-b-2 border-slate-900 inline-block px-8 pb-1">Pdt. .....................................</p>
                </div>
                <div class="px-10">
                    <p class="mb-24 font-bold uppercase text-slate-400">Bendahara Jemaat</p>
                    <p class="font-black text-slate-900 border-b-2 border-slate-900 inline-block px-8 pb-1 uppercase">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>