<div class="py-4 sm:py-10 bg-slate-50 min-h-screen text-slate-900" 
     x-data="{ 
        formatRupiah(num) {
            if (!num) return '';
            let val = num.toString().replace(/[^0-9]/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="w-full md:w-auto">
                <div class="flex items-center gap-2 mb-2 overflow-x-auto no-scrollbar whitespace-nowrap">
                    <a href="{{ route('auctions.index') }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline shrink-0">Event Lelang</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ $event->nama_event }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none">Item Lelang</h1>
            </div>
            <button wire:click="create" class="w-full md:w-auto px-6 py-4 bg-slate-900 text-white rounded-2xl sm:rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Item
            </button>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-[24px] sm:rounded-[32px] p-4 sm:p-6 shadow-sm border border-slate-100 mb-6 sm:mb-8">
            <div class="relative w-full">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl py-3 sm:py-4 pl-10 sm:pl-12 pr-4 font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Cari barang atau nama pemenang...">
                <svg class="w-4 h-4 sm:w-5 h-5 text-slate-300 absolute left-3.5 sm:left-4 top-3 sm:top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Mobile List (Cards) -->
        <div class="grid grid-cols-1 gap-4 md:hidden mb-8">
            @forelse($items as $item)
                <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->is_lunas ? 'bg-emerald-400' : 'bg-amber-400' }}"></div>
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="min-w-0">
                            <span class="block font-black text-slate-800 uppercase leading-tight truncate text-sm">{{ $item->nama_barang }}</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Donatur: {{ $item->donatur_nama ?? 'Anonim' }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-900 leading-none text-nowrap">Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4 bg-slate-50 p-2 rounded-xl">
                        <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                            {{ substr($item->pemenang_nama, 0, 1) }}
                        </div>
                        <span class="text-[10px] font-bold text-slate-600 truncate">{{ $item->pemenang_nama }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                        <div>
                            @if($item->is_lunas)
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">Lunas</span>
                            @else
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Sisa Piutang:</span>
                                <span class="block text-xs font-black text-amber-600 leading-none mt-0.5">Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="openHistoryModal({{ $item->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            @if(!$item->is_lunas)
                                <button wire:click="openPaymentModal({{ $item->id }})" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100">
                                    Bayar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white py-12 text-center rounded-[24px] border-2 border-dashed border-slate-100">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Tidak ada item ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Table Desktop -->
        <div class="hidden md:block bg-white rounded-[40px] shadow-xl border border-slate-100 overflow-hidden mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Barang</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Harga Jadi</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pemenang</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $item)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-4 px-8">
                                <span class="block font-black text-slate-800 uppercase leading-tight">{{ $item->nama_barang }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Donatur: {{ $item->donatur_nama ?? 'Anonim' }}</span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-black text-slate-900">
                                Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="block font-bold text-slate-700 text-sm truncate max-w-[150px]">{{ $item->pemenang_nama }}</span>
                                @if(!$item->pemenang_member_id)
                                    <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest">Pihak Luar</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($item->is_lunas)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">Lunas</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest text-nowrap">Sisa: Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-8 text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="openHistoryModal({{ $item->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors" title="Riwayat Bayar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                    @if(!$item->is_lunas)
                                    <button wire:click="openPaymentModal({{ $item->id }})" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md">
                                        Bayar
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $items->links() }}</div>
    </div>

    <!-- Modal Form Tambah (Single/Batch) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6 bg-slate-900/60 backdrop-blur-sm animate-in fade-in transition-all">
        <div class="bg-white w-full max-w-4xl rounded-t-[32px] sm:rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[95vh] sm:max-h-[90vh]">
            <div class="p-6 sm:p-8 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight">Input Item Lelang</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mendukung pemenang dari luar jemaat</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="text-slate-300 hover:text-rose-500 transition-colors p-2"><svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-6 sm:space-y-8 custom-scrollbar">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                    <div class="space-y-5 sm:space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Barang Utama</label>
                            <input wire:model="nama_barang" type="text" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Kain Tenun">
                            @error('nama_barang') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="relative">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Donatur</label>
                            <input wire:model.live.debounce.300ms="searchDonatur" type="text" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-300" placeholder="Cari jemaat atau ketik nama pihak luar...">
                            @if(!empty($foundDonatur))
                                <div class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden divide-y">
                                    @foreach($foundDonatur as $d)
                                        <button wire:click="selectDonatur({{ $d->id }}, '{{ $d->churchPeople->full_name }}')" class="w-full text-left p-4 hover:bg-slate-50 transition-colors">
                                            <p class="font-black text-slate-800 text-sm uppercase">{{ $d->churchPeople->full_name }}</p>
                                            <p class="text-[9px] text-slate-400 font-bold">ANGGOTA JEMAAT</p>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jumlah Item (Batch)</label>
                            <input wire:model.live="jumlah" type="number" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                            <p class="text-[9px] text-slate-400 mt-1 ml-1">Masukkan jumlah jika ingin input banyak pemenang untuk item sejenis.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Daftar Pemenang & Harga Jadi</label>
                        <div class="space-y-3 max-h-[350px] lg:max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($items_list as $index => $item)
                                <div class="p-4 bg-slate-50 rounded-2xl sm:rounded-3xl border border-slate-100 relative overflow-visible" wire:key="item-input-{{ $index }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-5 h-5 bg-slate-200 rounded-full flex items-center justify-center text-[9px] font-black text-slate-500">#{{ $index + 1 }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Detail Item</span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="relative">
                                            <input wire:model.live="items_list.{{ $index }}.pemenang_nama" 
                                                   wire:keyup="searchMemberBatch({{ $index }}, $event.target.value)"
                                                   type="text" class="w-full bg-white border-none rounded-xl p-3 text-[11px] font-bold shadow-sm" placeholder="Nama Pemenang (Ketik Bebas)">
                                            
                                            @if($activeSearchIndex === $index && !empty($foundPemenangBatch))
                                                <div class="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden divide-y">
                                                    @foreach($foundPemenangBatch as $m)
                                                        <button wire:click="selectPemenangBatch({{ $index }}, {{ $m['id'] }}, '{{ $m['church_people']['full_name'] }}')" class="w-full text-left p-3 hover:bg-slate-50 transition-colors">
                                                            <p class="font-black text-slate-800 text-[10px] uppercase">{{ $m['church_people']['full_name'] }}</p>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="relative" x-data="{ localVal: @entangle('items_list.'.$index.'.harga_jadi') }">
                                            <span class="absolute left-3 top-3 text-[10px] font-black text-slate-300">Rp</span>
                                            <input x-model="localVal" 
                                                   x-on:input="localVal = formatRupiah($event.target.value)"
                                                   type="text" class="w-full bg-white border-none rounded-xl py-3 pl-8 pr-3 text-[11px] font-black shadow-sm text-right" placeholder="Harga">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 border-t border-slate-50 bg-slate-50/30 sticky bottom-0">
                <button wire:click="save" wire:loading.attr="disabled" class="w-full py-4 sm:py-5 bg-slate-900 text-white rounded-2xl sm:rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-3 group">
                    <span wire:loading.remove wire:target="save">Simpan Semua Item lelang</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Bayar -->
    @if($isPaymentModalOpen)
    <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-sm animate-in zoom-in-95 transition-all">
        <div class="bg-white w-full max-w-md rounded-t-[32px] sm:rounded-[40px] shadow-2xl p-8 sm:p-10 relative">
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Terima Bayar</h2>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 border-l-4 border-emerald-500 pl-3">{{ $activeItemName }}</p>

            <div class="space-y-6">
                <div x-data="{ localNominal: @entangle('nominal_bayar') }">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nominal Setoran (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-5 text-lg font-black text-slate-300">Rp</span>
                        <input x-model="localNominal" 
                               x-on:input="localNominal = formatRupiah($event.target.value)"
                               type="text" class="w-full bg-slate-50 border-none rounded-2xl py-5 pl-12 pr-6 font-black text-2xl text-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-inner" placeholder="0">
                    </div>
                    @error('nominal_bayar') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Simpan Ke Kas</label>
                    <div class="relative group">
                        <select wire:model="ref_account_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none">
                            @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('ref_account_id') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">Silakan pilih kas.</span> @enderror
                </div>
                
                <div class="pt-2">
                    <button wire:click="savePayment" wire:loading.attr="disabled" class="w-full py-5 bg-emerald-500 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-600 transition-all flex items-center justify-center gap-3">
                        <span wire:loading.remove wire:target="savePayment">Verifikasi Pembayaran</span>
                        <span wire:loading wire:target="savePayment" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                    <button wire:click="$set('isPaymentModalOpen', false)" class="w-full text-[10px] font-black text-slate-300 hover:text-slate-500 uppercase tracking-[0.2em] mt-4 transition-colors">Batal & Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>