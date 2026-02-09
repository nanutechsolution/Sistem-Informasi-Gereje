<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Controls (Tidak ikut di-print) -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 print:hidden">
            <div class="flex-1 grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Dari Tanggal</label>
                    <input wire:model.live="startDate" type="date" class="w-full border-gray-200 rounded-lg text-sm focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Sampai Tanggal</label>
                    <input wire:model.live="endDate" type="date" class="w-full border-gray-200 rounded-lg text-sm focus:ring-primary">
                </div>
            </div>
            <div class="w-full md:w-64">
                <label class="text-xs font-bold text-gray-500 uppercase">Filter Akun</label>
                <select wire:model.live="accountId" class="w-full border-gray-200 rounded-lg text-sm focus:ring-primary">
                    <option value="all">Semua Akun (Gabungan)</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="window.print()" class="w-full md:w-auto px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-bold hover:bg-gray-700">
                    Cetak PDF
                </button>
            </div>
        </div>

        <!-- KERTAS LAPORAN (A4 Style) -->
        <div class="bg-white p-8 sm:p-12 shadow-lg sm:rounded-xl min-h-[297mm] print:shadow-none print:p-0">
            
            <!-- Kop Laporan -->
            <div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
                <h1 class="text-2xl font-extrabold text-gray-900 uppercase">GEREJA KRISTEN SUMBA</h1>
                <h2 class="text-lg font-bold text-gray-600">JEMAAT ... (Isi Nama Jemaat)</h2>
                <p class="text-sm text-gray-500 mt-2">LAPORAN BUKU KAS UMUM (BKU)</p>
                <p class="text-sm font-bold">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>

            <!-- Tabel Data -->
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-y-2 border-gray-800 text-gray-900">
                        <th class="py-2 px-2 w-24">Tanggal</th>
                        <th class="py-2 px-2 w-32">No. Bukti</th>
                        <th class="py-2 px-2">Uraian Transaksi</th>
                        <th class="py-2 px-2 text-right w-32">Masuk (Rp)</th>
                        <th class="py-2 px-2 text-right w-32">Keluar (Rp)</th>
                        <th class="py-2 px-2 text-right w-32">Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Baris Saldo Awal -->
                    <tr class="bg-gray-50 font-bold">
                        <td class="py-2 px-2">-</td>
                        <td class="py-2 px-2">-</td>
                        <td class="py-2 px-2">SALDO AWAL</td>
                        <td class="py-2 px-2 text-right">-</td>
                        <td class="py-2 px-2 text-right">-</td>
                        <td class="py-2 px-2 text-right">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                    </tr>

                    @php $saldoBerjalan = $saldoAwal; @endphp

                    @foreach($transactions as $trx)
                        @php
                            $masuk = $trx->jenis == 'masuk' ? $trx->nominal : 0;
                            $keluar = in_array($trx->jenis, ['keluar', 'pindah_buku']) ? $trx->nominal : 0;
                            $saldoBerjalan = $saldoBerjalan + $masuk - $keluar;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 align-top">{{ $trx->tanggal->format('d/m/Y') }}</td>
                            <td class="py-2 px-2 align-top text-xs text-gray-500">{{ $trx->nomor_bukti ?? '-' }}</td>
                            <td class="py-2 px-2 align-top">
                                <span class="font-medium text-gray-900 block">{{ $trx->keterangan }}</span>
                                <span class="text-xs text-gray-500">
                                    {{ $trx->account->nama }} 
                                    @if($trx->budgetPost) | Pos: {{ $trx->budgetPost->kode }} @endif
                                    @if($trx->jenis == 'pindah_buku') <span class="text-blue-600 font-bold">(Transfer)</span> @endif
                                </span>
                            </td>
                            <td class="py-2 px-2 align-top text-right font-medium text-gray-600">
                                {{ $masuk > 0 ? number_format($masuk, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-2 px-2 align-top text-right font-medium text-gray-600">
                                {{ $keluar > 0 ? number_format($keluar, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-2 px-2 align-top text-right font-bold text-gray-900">
                                {{ number_format($saldoBerjalan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-800 font-bold bg-gray-50">
                    <tr>
                        <td colspan="3" class="py-3 px-2 text-right uppercase">Total Mutasi & Saldo Akhir</td>
                        <td class="py-3 px-2 text-right text-green-700">{{ number_format($totalMasuk, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right text-red-700">{{ number_format($totalKeluar, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right bg-gray-200">{{ number_format($saldoBerjalan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Kolom Tanda Tangan -->
            <div class="mt-16 grid grid-cols-2 gap-8 text-center break-inside-avoid">
                <div>
                    <p class="mb-20">Mengetahui,<br>Ketua Majelis</p>
                    <p class="font-bold underline">Pdt. ...........................</p>
                </div>
                <div>
                    <p class="mb-20">Dibuat Oleh,<br>Bendahara Jemaat</p>
                    <p class="font-bold underline">{{ auth()->user()->name }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
