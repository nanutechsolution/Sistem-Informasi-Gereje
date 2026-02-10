<div class="py-6 sm:py-12 bg-slate-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
    "
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Jurnal Kas Jemaat</h1>
                <p class="text-slate-500 mt-3 font-medium border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Arus Kas Masuk & Keluar</p>
            </div>
            
            @can('manage_finance')
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-xs shadow-xl shadow-emerald-500/20 hover:scale-105 transition-all uppercase tracking-widest gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Pemasukan
                </a>
                <a href="{{ route('transactions.create', ['jenis' => 'keluar']) }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-2xl font-black text-xs shadow-xl shadow-rose-500/20 hover:scale-105 transition-all uppercase tracking-widest gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                    Pengeluaran
                </a>
            </div>
            @endcan
        </div>

        <!-- Summary Widgets (Mendukung Panitia Pembangunan) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Masuk (Periode)</p>
                <p class="text-xl font-black text-emerald-600 italic">Rp {{ number_format($summary['masuk'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Keluar (Periode)</p>
                <p class="text-xl font-black text-rose-600 italic">Rp {{ number_format($summary['keluar'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-[32px] shadow-xl text-white">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Saldo Bersih Periode</p>
                <p class="text-xl font-black italic">Rp {{ number_format($summary['saldo_periode'], 0, ',', '.') }}</p>
            </div>
            <!-- WIDGET KHUSUS PEMBANGUNAN -->
            <div class="bg-amber-500 p-6 rounded-[32px] shadow-xl text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[9px] font-black text-amber-100 uppercase tracking-widest mb-1">Kas Pembangunan</p>
                    <p class="text-xl font-black italic">Rp {{ number_format($summary['pembangunan'], 0, ',', '.') }}</p>
                </div>
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-amber-400/30 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89l-.13-1.326a1 1 0 01.128-.507 1.021 1.021 0 01.252-.225zm6.236 6.397l-3.326-1.426a9.045 9.045 0 002.592.676l.12 1.069a1 1 0 00.997.89h.756a1 1 0 00.997-.89l.12-1.069a9.042 9.042 0 002.592-.676l-3.326 1.426a1 1 0 01-.787 0z"/></svg>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Pencarian</label>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-4 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-primary/10" placeholder="Ketik keterangan...">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Dari Tanggal</label>
                <input wire:model.live="startDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-3 font-bold text-sm">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Sampai Tanggal</label>
                <input wire:model.live="endDate" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-3 font-bold text-sm">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Filter Akun</label>
                <select wire:model.live="filterAccount" class="w-full bg-slate-50 border-none rounded-2xl p-3 font-bold text-sm appearance-none cursor-pointer">
                    <option value="">Semua Dompet</option>
                    @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-6 w-32">Waktu</th>
                            <th class="px-6 py-6">Uraian Transaksi</th>
                            <th class="px-6 py-6">Pos / Akun</th>
                            <th class="px-6 py-6 text-right">Nominal</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="block font-black text-slate-900 leading-none">{{ $trx->tanggal->format('d M') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase mt-1 block">{{ $trx->tanggal->format('Y') }}</span>
                            </td>
                            <td class="px-6 py-6">
                                <p class="font-bold text-slate-800 leading-snug">{{ $trx->keterangan }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">Oleh: {{ $trx->user->name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                @if($trx->budgetPost)
                                    <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded uppercase tracking-tighter border border-blue-100 mb-1">
                                        {{ $trx->budgetPost->kode }} - {{ $trx->budgetPost->nama }}
                                    </span>
                                @endif
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    {{ $trx->account->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-right font-black text-base whitespace-nowrap">
                                <span class="{{ $trx->jenis == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('transactions.print', $trx) }}" target="_blank" class="p-2 bg-white border border-slate-200 text-slate-400 hover:text-slate-900 rounded-xl shadow-sm transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg></a>
                                    @can('manage_finance')
                                    <a href="{{ route('transactions.edit', $trx) }}" class="p-2 bg-white border border-slate-200 text-slate-400 hover:text-amber-500 rounded-xl shadow-sm transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                                    <button wire:click="delete({{ $trx->id }})" wire:confirm="Hapus transaksi ini? Saldo akan disesuaikan otomatis." class="p-2 bg-white border border-slate-200 text-slate-300 hover:text-rose-600 rounded-xl shadow-sm transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-24 text-center text-slate-300 italic font-black uppercase tracking-widest text-xs">Belum ada transaksi di periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">{{ $transactions->links() }}</div>
    </div>
</div>