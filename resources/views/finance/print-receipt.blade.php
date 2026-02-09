<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi #{{ $trx->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Premium: Cinzel untuk kesan resmi/klasik, Inter untuk keterbacaan -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page { size: A5 landscape; margin: 0; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white; }
            .no-print { display: none !important; }
            .print-container { 
                width: 100%; 
                height: 100%; 
                border: none; 
                box-shadow: none; 
                padding: 0;
            }
        }
        body { font-family: 'Inter', sans-serif; background: #eef2f6; }
        .font-serif-header { font-family: 'Cinzel', serif; }
        .watermark {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%231e3a8a" opacity="0.05"><path d="M12 2L1 21h22L12 2zm0 3.516L20.297 19H3.703L12 5.516z"/></svg>'); 
            background-repeat: no-repeat;
            background-position: center;
            background-size: 40%;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-0 sm:p-8">

    <!-- Tombol Print -->
    <div class="fixed top-6 right-6 no-print z-50">
        <button onclick="window.print()" class="bg-gray-900 text-white px-5 py-2.5 rounded-lg shadow-lg hover:bg-black font-medium text-sm flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <!-- Kertas Kwitansi (A5 Landscape Ratio: 210mm x 148mm) -->
    <div class="print-container bg-white w-[210mm] h-[148mm] relative shadow-2xl overflow-hidden text-gray-800 flex flex-col watermark border border-gray-300 sm:rounded-sm">
        
        <!-- Header Strip Dekoratif -->
        <div class="h-2 w-full bg-blue-900"></div>

        <div class="p-8 sm:p-10 flex-1 flex flex-col justify-between relative z-10">
            
            <!-- 1. Header Section -->
            <div class="flex justify-between items-start border-b-2 border-gray-100 pb-4">
                <div class="flex items-center gap-4">
                    <!-- Logo Circle Placeholder -->
                    <div class="w-12 h-12 bg-blue-900 text-white flex items-center justify-center rounded-full shadow-md">
                        <span class="text-xl font-serif font-bold">✝</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-serif-header font-bold text-blue-900 tracking-wide leading-none">GEREJA KRISTEN SUMBA</h1>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Jemaat Reda</p>
                        <p class="text-[10px] text-gray-400">Jl. Katedral No. 1, Sumba Barat Daya</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                        {{ $trx->jenis == 'masuk' ? 'KWITANSI' : ($trx->jenis == 'keluar' ? 'BUKTI KAS KELUAR' : 'BUKTI TRANSFER') }}
                    </h2>
                    <div class="mt-1 flex flex-col items-end">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Nomor Referensi</span>
                        <span class="text-sm font-mono font-bold text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                            TRX-{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Content Body -->
            <div class="flex-1 py-4 space-y-3">
                
                <!-- Tanggal -->
                <div class="flex justify-end mb-2">
                    <p class="text-xs text-gray-500 font-medium">
                        Tanggal: <span class="text-gray-900 font-bold">{{ $trx->tanggal->format('d F Y') }}</span>
                    </p>
                </div>

                <!-- Fields Grid -->
                <div class="grid grid-cols-[140px_1fr] gap-y-3 items-baseline text-sm">
                    
                    <!-- Pihak -->
                    <div class="font-bold text-gray-500 uppercase text-[10px] tracking-wider">
                        {{ $trx->jenis == 'masuk' ? 'Diterima Dari' : 'Diserahkan Kepada' }}
                    </div>
                    <div class="border-b border-gray-300 border-dashed pb-1 font-bold text-base text-gray-900">
                        {{ $trx->jenis == 'masuk' ? 'Jemaat / Donatur' : ($trx->user->name ?? 'Bendahara') }}
                    </div>

                    <!-- Nominal Terbilang -->
                    <div class="font-bold text-gray-500 uppercase text-[10px] tracking-wider">Uang Sejumlah</div>
                    <div class="bg-gray-100 rounded-lg p-2.5 text-gray-700 italic font-medium border-l-4 border-blue-600 text-xs sm:text-sm">
                        " {{ \App\Helpers\Terbilang::make($trx->nominal) }} Rupiah "
                    </div>

                    <!-- Keterangan -->
                    <div class="font-bold text-gray-500 uppercase text-[10px] tracking-wider self-start mt-1">Untuk Pembayaran</div>
                    <div class="border-b border-gray-300 border-dashed pb-1 text-sm text-gray-800 leading-relaxed">
                        {{ $trx->keterangan }}
                        @if($trx->budgetPost)
                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 uppercase border border-blue-100">
                                Pos: {{ $trx->budgetPost->kode }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. Footer / Signature -->
            <div class="flex justify-between items-end mt-2 pt-2">
                
                <!-- Nominal Box -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah Nominal</span>
                    <div class="inline-flex items-baseline bg-gray-900 text-white px-4 py-2 rounded-lg shadow-md border border-gray-700">
                        <span class="text-xs font-medium text-gray-400 mr-1.5">Rp</span>
                        <span class="text-xl font-bold tracking-tight">{{ number_format($trx->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tanda Tangan -->
                <div class="flex gap-10 text-center text-[10px]">
                    <div>
                        <p class="text-gray-400 mb-10 uppercase font-bold tracking-wider">Disetujui Oleh</p>
                        <p class="font-bold text-gray-900 border-b border-gray-300 pb-1 min-w-[100px]">Ketua / Bendahara</p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-10 uppercase font-bold tracking-wider">
                            {{ $trx->jenis == 'masuk' ? 'Penerima' : 'Penyetor' }}
                        </p>
                        <p class="font-bold text-gray-900 border-b border-gray-300 pb-1 min-w-[100px]">
                            {{ $trx->user->name ?? 'Admin' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Bottom Bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-900 via-blue-700 to-yellow-500"></div>
    </div>

</body>
</html>