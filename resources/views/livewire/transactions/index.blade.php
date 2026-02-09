<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
    ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Jurnal Keuangan</h1>
                <p class="text-gray-500 mt-2 text-lg">Catatan arus kas masuk dan keluar.</p>
            </div>

            <!-- Tombol Aksi Cepat (Nanti kita buat rutenya) -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Pemasukan
                </a>
                <a href="{{ route('transactions.create', ['jenis' => 'keluar']) }}" class="inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                    Pengeluaran
                </a>
                <a href="{{ route('transactions.create', ['jenis' => 'pindah_buku']) }}" class="inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Transfer
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="Cari keterangan atau nominal...">
            </div>
            <div class="w-full sm:w-48">
                <select wire:model.live="filterJenis" class="block w-full py-3 px-3 border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Jenis</option>
                    <option value="masuk">Pemasukan</option>
                    <option value="keluar">Pengeluaran</option>
                    <option value="pindah_buku">Pindah Buku</option>
                </select>
            </div>
        </div>

        <!-- DESKTOP VIEW: Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Akun / Dompet</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Pos Anggaran</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Nominal (Rp)</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $trx->tanggal->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $trx->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $trx->keterangan }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Oleh: {{ $trx->user->name ?? 'Sistem' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700">
                                {{ $trx->account->nama }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($trx->budgetPost)
                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">
                                {{ $trx->budgetPost->kode }} - {{ $trx->budgetPost->nama }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-base {{ $trx->jenis == 'masuk' ? 'text-green-600' : ($trx->jenis == 'keluar' ? 'text-red-600' : 'text-gray-700') }}">
                                {{ $trx->jenis == 'masuk' ? '+' : ($trx->jenis == 'keluar' ? '-' : '') }}
                                {{ number_format($trx->nominal, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('transactions.print', $trx) }}" target="_blank" class="text-gray-400 hover:text-gray-800 mr-2" title="Cetak Kwitansi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                            <a href="{{ route('transactions.edit', $trx) }}" class="text-gray-400 hover:text-primary font-bold text-xs transition-colors mr-2">
                                Edit
                            </a>
                            <button wire:click="delete('{{ $trx->id }}')" wire:confirm="Hapus transaksi ini?" class="text-gray-400 hover:text-red-600 font-bold text-xs transition-colors">
                                Hapus
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada transaksi tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MOBILE VIEW: Cards -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($transactions as $trx)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ $trx->tanggal->format('d M Y') }}</span>
                        <h3 class="font-bold text-gray-900 mt-0.5">{{ $trx->keterangan }}</h3>
                    </div>
                    <span class="font-extrabold {{ $trx->jenis == 'masuk' ? 'text-green-600' : ($trx->jenis == 'keluar' ? 'text-red-600' : 'text-gray-700') }}">
                        {{ $trx->jenis == 'masuk' ? '+' : ($trx->jenis == 'keluar' ? '-' : '') }}
                        {{ number_format($trx->nominal, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded border border-gray-200">
                        {{ $trx->account->nama }}
                    </span>
                    @if($trx->budgetPost)
                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">
                        {{ $trx->budgetPost->nama }}
                    </span>
                    @endif
                </div>

                @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                <div class="pt-3 border-t border-gray-50 flex justify-end gap-2">
                    <a href="{{ route('transactions.print', $trx) }}" target="_blank" class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak
                    </a>
                    <a href="{{ route('transactions.edit', $trx) }}" class="text-xs font-bold text-gray-500 hover:text-primary bg-gray-50 px-3 py-1.5 rounded-lg">
                        Edit
                    </a>
                    <button wire:click="delete('{{ $trx->id }}')" wire:confirm="Hapus transaksi?" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-lg">
                        Hapus
                    </button>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">Belum ada transaksi.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>