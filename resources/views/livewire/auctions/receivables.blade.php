<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase leading-none">Piutang Lelang</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-rose-500 pl-4">Daftar jemaat yang belum melunasi kewajiban lelang.</p>
            </div>

            <div class="flex gap-3">
                <select wire:model.live="filterEvent" class="bg-white border-slate-200 rounded-2xl p-4 font-bold text-sm shadow-sm">
                    <option value="">Semua Kegiatan</option>
                    @foreach($events as $e) <option value="{{ $e->id }}">{{ $e->nama_event }}</option> @endforeach
                </select>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" class="bg-white border-slate-200 rounded-2xl p-4 font-bold text-sm pl-12 shadow-sm" placeholder="Cari nama pemenang...">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Pemenang</th>
                        <th class="px-6 py-5">Barang & Acara</th>
                        <th class="px-6 py-5 text-right">Nilai</th>
                        <th class="px-6 py-5 text-right">Sisa Hutang</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($receivables as $item)
                    <tr class="hover:bg-rose-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <p class="font-black text-slate-900 uppercase italic">{{ $item->pemenang_nama }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->pemenang->family->refWilayah->nama ?? 'Wilayah -' }}</p>
                        </td>
                        <td class="px-6 py-5 text-sm">
                            <p class="font-bold text-slate-700">{{ $item->nama_barang }}</p>
                            <p class="text-[10px] font-medium text-primary uppercase">{{ $item->event->nama_event }}</p>
                        </td>
                        <td class="px-6 py-5 text-right font-bold text-slate-400 italic">Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right">
                            <span class="text-lg font-black text-rose-600 tracking-tighter">Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('auctions.items', $item->event->uuid) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-primary transition-all">Bayar &rarr;</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center font-black text-slate-300 uppercase tracking-widest italic">Tidak ada hutang lelang yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $receivables->links() }}</div>
    </div>
</div>