<div class="py-10 bg-gray-100 min-h-screen print:bg-white print:py-0">
    <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-[40px] overflow-hidden print:shadow-none print:rounded-none">

        <!-- Action Bar (Hide on Print) -->
        <div class="px-10 py-6 bg-slate-900 flex justify-between items-center print:hidden">
            <a href="{{ route('finance.payroll') }}" class="text-white/60 hover:text-white flex items-center gap-2 font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20">
                Cetak Slip Gaji
            </button>
        </div>

        <!-- Slip Content -->
        <div class="p-10 sm:p-16">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start gap-8 border-b-2 border-slate-100 pb-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white font-black text-3xl">G</div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-none">GKS JEMAAT REDA PADA</h2>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Sumba Barat Daya, NTT</p>
                    </div>
                </div>
                <div class="text-right">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">Slip Gaji</h1>
                    <p class="text-xs font-bold text-slate-400 mt-1">No. Ref: {{ $payroll->transaction->uuid ?? 'DRAFT-'.$payroll->id }}</p>
                </div>
            </div>

            <!-- Employee Info -->
            <div class="grid grid-cols-2 gap-8 py-10 border-b border-slate-50">
                <div class="space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Penerima</p>
                        <p class="text-lg font-black text-slate-900">{{ $payroll->officer->member->nama }}</p>
                        <p class="text-xs font-bold text-primary">{{ $payroll->officer->position->nama }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status / Lokasi</p>
                        <p class="text-xs font-bold text-slate-700 capitalize">{{ $payroll->officer->status_kepegawaian }} / {{ $payroll->officer->lokasi_tugas }}</p>
                    </div>
                </div>
                <div class="text-right space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Periode Gaji</p>
                        <p class="text-lg font-black text-slate-900">{{ $payroll->nama_bulan }} {{ $payroll->tahun }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal Dibayar</p>
                        <p class="text-xs font-bold text-slate-700">{{ $payroll->tanggal_bayar?->format('d/m/Y') ?? 'Belum Dibayar' }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Earnings & Deductions -->
            <div class="py-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Penghasilan -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Penghasilan (Earnings)
                        </h4>
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-600">Gaji Pokok / Pemeliharaan</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-600">Tunjangan Perumahan</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($payroll->tunjangan_perumahan, 0, ',', '.') }}</span>
                            </div>
                            @if($payroll->tunjangan_lain > 0)
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-600">Tunjangan Lainnya</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($payroll->tunjangan_lain, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Potongan -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Potongan (Deductions)
                        </h4>
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-600">Iuran Dana Pensiun</span>
                                <span class="font-bold text-rose-600">- Rp {{ number_format($payroll->iuran_pensiun, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Total -->
            <div class="mt-4 p-8 bg-slate-50 rounded-[32px] border border-slate-100 flex justify-between items-center">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Diterima (Netto)</span>
                <span class="text-3xl font-black text-slate-900 tracking-tighter">Rp {{ number_format($payroll->netto, 0, ',', '.') }}</span>
            </div>

            <!-- Footer Signatures -->
            <div class="mt-20 flex justify-between items-end">
                <div class="text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-16">Penerima,</p>
                    <p class="font-bold text-slate-900 border-b border-slate-900 inline-block px-4">{{ $payroll->officer->member->nama }}</p>
                </div>

                <!-- Digital Validation -->
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 p-2 rounded-xl mb-2 flex items-center justify-center opacity-60">
                        <svg class="w-full h-full text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3v6h6V3H3zm4 4H5V5h2v2zm-4 5v6h6v-6H3zm4 4H5v-2h2v2zM12 3v6h6V3h-6zm4 4h-2V5h2v2zM3 15v6h6v-6H3zm4 4H5v-2h2v2zM15 15v6h6v-6h-6zm4 4h-2v-2h2v2zM12 12v2h2v-2h-2zm4 4v2h2v-2h-2zM12 18v2h2v-2h-2zm4-6v2h2v-2h-2z" />
                        </svg>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase">Validasi Digital SIG-GKS</p>
                </div>

                <div class="text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lolo Ole, {{ $payroll->tanggal_bayar?->format('d F Y') ?? date('d F Y') }}</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-12">Bendahara Jemaat,</p>
                    <p class="font-bold text-slate-900 border-b border-slate-900 inline-block px-4">Bendahara Jemaat Reda Pada</p>
                </div>
            </div>

            <p class="mt-16 text-center text-[9px] font-bold text-slate-300 uppercase tracking-widest">Dokumen ini diterbitkan secara digital oleh sistem informasi SIG-GKS Jemaat Reda Pada.</p>
        </div>
    </div>

<style>
    @media print {

        nav,
        .action-bar {
            display: none !important;
        }

        body {
            background-color: white !important;
        }

        .shadow-2xl {
            box-shadow: none !important;
        }
    }
</style>

</div>
