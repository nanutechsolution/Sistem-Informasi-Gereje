<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Jurnal Kas</h1>
                <p class="text-slate-500 mt-2 font-medium border-l-4 border-primary pl-4">Pencatatan arus masuk & keluar dana jemaat.</p>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="flex-shrink-0 px-6 py-3 bg-emerald-600 text-white rounded-[24px] font-black text-xs shadow-lg hover:bg-emerald-700 transition transform hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    PEMASUKAN
                </a>
                <a href="{{ route('transactions.create', ['jenis' => 'keluar']) }}" class="flex-shrink-0 px-6 py-3 bg-rose-600 text-white rounded-[24px] font-black text-xs shadow-lg hover:bg-rose-700 transition transform hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                    PENGELUARAN
                </a>
                <a href="{{ route('transactions.create', ['jenis' => 'pindah_buku']) }}" class="flex-shrink-0 px-6 py-3 bg-slate-800 text-white rounded-[24px] font-black text-xs shadow-lg hover:bg-slate-700 transition transform hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    TRANSFER
                </a>
            </div>
        </div>

        <!-- SUMMARY CARDS (MINI DASHBOARD) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Masuk -->
            <div class="bg-emerald-50 rounded-[32px] p-6 border border-emerald-100 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Total Masuk (Periode Ini)</p>
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($summary['masuk'], 0, ',', '.') }}</p>
                </div>
                <div class="h-10 w-10 bg-emerald-200 rounded-full flex items-center justify-center text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
            <!-- Keluar -->
            <div class="bg-rose-50 rounded-[32px] p-6 border border-rose-100 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-1">Total Keluar (Periode Ini)</p>
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($summary['keluar'], 0, ',', '.') }}</p>
                </div>
                <div class="h-10 w-10 bg-rose-200 rounded-full flex items-center justify-center text-rose-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <!-- Surplus -->
            <div class="bg-slate-900 rounded-[32px] p-6 text-white flex justify-between items-center shadow-xl">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Surplus / Defisit</p>
                    <p class="text-2xl font-black {{ $summary['saldo_periode'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $summary['saldo_periode'] >= 0 ? '+' : '' }} Rp {{ number_format($summary['saldo_periode'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-10 w-10 bg-white/10 rounded-full flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
            </div>
        </div>

        <!-- FILTER TOOLS -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4">
            <!-- Date Range -->
            <div class="flex items-center gap-2 bg-slate-50 rounded-2xl p-2 md:w-auto w-full">
                <input wire:model.live="startDate" type="date" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0">
                <span class="text-slate-300 font-bold">-</span>
                <input wire:model.live="endDate" type="date" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0">
            </div>

            <!-- Search -->
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-xs focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari keterangan transaksi...">
            </div>

            <!-- Filter Akun -->
            <select wire:model.live="filterAccount" class="bg-slate-50 border-none rounded-2xl py-3 px-4 font-bold text-xs text-slate-600 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                <option value="">Semua Dompet</option>
                @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
            </select>
        </div>

        <!-- TABLE DATA -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden hidden md:block">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Tanggal & Bukti</th>
                        <th class="px-6 py-5">Uraian Transaksi</th>
                        <th class="px-6 py-5">Pos Anggaran / Akun</th>
                        <th class="px-6 py-5 text-right">Nominal</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5 align-top">
                            <div class="font-bold text-slate-900">{{ $trx->tanggal->format('d/m/Y') }}</div>
                            <div class="text-[10px] font-mono text-slate-400 mt-1 uppercase">{{ $trx->uuid ? substr($trx->uuid, 0, 8) : '-' }}</div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            <div class="font-bold text-slate-800 leading-snug">{{ $trx->keterangan }}</div>
                            <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Input: {{ $trx->user->name ?? 'System' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            @if($trx->budgetPost)
                                <span class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100 mb-1">
                                    {{ $trx->budgetPost->kode }}
                                </span>
                            @endif
                            <div class="text-xs font-bold text-slate-500">{{ $trx->account->nama }}</div>
                        </td>
                        <td class="px-6 py-5 text-right align-top">
                            <span class="font-black text-base {{ $trx->jenis == 'masuk' ? 'text-emerald-600' : ($trx->jenis == 'keluar' ? 'text-rose-600' : 'text-blue-600') }}">
                                {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </span>
                            <div class="text-[9px] font-black uppercase text-slate-300 mt-1 tracking-widest">{{ $trx->jenis == 'pindah_buku' ? 'TRANSFER' : $trx->jenis }}</div>
                        </td>
                        <td class="px-8 py-5 text-right align-top">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('transactions.edit', $trx) }}" class="p-2 text-slate-400 hover:text-primary bg-slate-50 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <a href="{{ route('transactions.print', $trx) }}" target="_blank" class="p-2 text-slate-400 hover:text-emerald-600 bg-slate-50 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                                <button wire:click="delete({{ $trx }})" wire:confirm="Hapus transaksi ini?" class="p-2 text-slate-300 hover:text-rose-500 bg-slate-50 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-24 text-center text-slate-400 italic font-medium">Belum ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MOBILE CARDS -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach($transactions as $trx)
            <div class="bg-white p-5 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $trx->tanggal->format('d M Y') }}</p>
                        <h3 class="font-bold text-slate-900 leading-tight mt-1">{{ $trx->keterangan }}</h3>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $trx->jenis == 'masuk' ? 'bg-emerald-100 text-emerald-700' : ($trx->jenis == 'keluar' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $trx->jenis }}
                    </span>
                </div>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded">{{ $trx->account->nama }}</span>
                    @if($trx->budgetPost)
                    <span class="text-[10px] font-bold bg-blue-50 text-primary px-2 py-0.5 rounded">{{ $trx->budgetPost->kode }}</span>
                    @endif
                </div>

                <div class="flex justify-between items-end pt-4 border-t border-slate-50">
                    <span class="text-xl font-black {{ $trx->jenis == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                        Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('transactions.print', $trx) }}" target="_blank" class="p-2 bg-slate-50 rounded-xl text-slate-400 hover:text-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-width="2"/></svg></a>
                        <a href="{{ route('transactions.edit', $trx) }}" class="p-2 bg-slate-50 rounded-xl text-slate-400 hover:text-amber-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"/></svg></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $transactions->links() }}</div>

    </div>
</div>