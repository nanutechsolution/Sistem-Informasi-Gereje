<div class="py-6 sm:py-12 bg-gray-50 min-h-screen" 
     x-data="{ 
        showItem: @entangle('isModalOpen'), 
        showPayment: @entangle('isPaymentModalOpen'), 
        showHistory: @entangle('isHistoryModalOpen'),
        formatRupiah(value) {
            if(!value) return '';
            return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('auctions.index') }}" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Daftar Event
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">{{ $event->nama_event }}</h1>
                <div class="mt-3 flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-primary text-white tracking-widest shadow-sm">Target: Kas {{ $event->tujuan_kas }}</span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-200 text-slate-600 tracking-widest">{{ \Carbon\Carbon::parse($event->tanggal_event)->isoFormat('D MMMM Y') }}</span>
                </div>
            </div>
            <button wire:click="create" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang Baru
            </button>
        </div>

        <!-- Toolbar Pencarian -->
        <div class="mb-6 relative group max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-[24px] shadow-sm focus:ring-2 focus:ring-primary/20 font-bold text-slate-700" placeholder="Cari nama barang atau pemenang...">
        </div>

        <!-- Tabel Data Barang -->
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-6">Rincian Barang & Donatur</th>
                        <th class="px-6 py-6 text-center">Pemenang</th>
                        <th class="px-6 py-6 text-right">Harga Jadi</th>
                        <th class="px-6 py-6 text-right">Terbayar</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="font-black text-slate-900 text-base leading-tight">{{ $item->nama_barang }}</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">Donatur: {{ $item->donatur_nama ?? 'Hamba Tuhan' }}</div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($item->pemenang_nama)
                                <div class="font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-lg inline-block text-xs uppercase">{{ $item->pemenang_nama }}</div>
                            @else
                                <span class="text-slate-300 italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-900 text-base">Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}</td>
                        <td class="px-6 py-6 text-right">
                            <button wire:click="openHistoryModal({{ $item->id }})" class="flex flex-col items-end group/btn ml-auto">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm {{ $item->status_lunas ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}">
                                    Rp {{ number_format($item->total_terbayar_cache, 0, ',', '.') }}
                                </span>
                                @if($item->sisa_piutang > 0)
                                    <span class="text-[9px] font-bold text-rose-400 mt-1 uppercase tracking-tighter">Sisa: Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $item->id }})" class="p-2.5 text-slate-400 hover:text-primary hover:bg-blue-50 rounded-xl transition-all" title="Edit Data Barang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                @if(!$item->status_lunas)
                                <button wire:click="openPaymentModal({{ $item->id }})" class="p-2.5 text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-lg shadow-emerald-500/10" title="Terima Pembayaran">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-24 text-center text-slate-400 italic font-medium">Belum ada barang lelang yang dicatat untuk kegiatan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">{{ $items->links() }}</div>
    </div>

    <!-- MODAL 1: FORM INPUT/EDIT BARANG -->
    <div x-show="showItem" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showItem = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl transition-all"
                 x-data="{ 
                    localHarga: @entangle('harga_jadi'),
                    init() {
                        this.$watch('localHarga', v => { this.$refs.hargaInput.value = this.formatRupiah(v); });
                    }
                 }">
                <h3 class="text-2xl font-black text-slate-900 mb-6 italic">{{ $editId ? 'Ubah' : 'Tambah' }} Barang Lelang</h3>
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama Barang</label>
                        <input wire:model="nama_barang" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Misal: Kue Natal 1 Paket">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama Donatur</label>
                        <input wire:model="donatur_nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-primary/20" placeholder="Kel. Bpk. Albert">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pemenang Lelang</label>
                            <input wire:model="pemenang_nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black focus:ring-2 focus:ring-primary/20" placeholder="Bpk. Yohanes">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-3 ml-1">Harga Jadi (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/40 font-bold">Rp</span>
                                <input x-ref="hargaInput" type="tel" x-on:input="localHarga = formatRupiah($el.value); $el.value = localHarga"
                                    class="w-full pl-10 bg-blue-50 border-none rounded-2xl p-4 font-black text-primary focus:ring-2 focus:ring-primary/20" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showItem = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-xs uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase shadow-xl shadow-blue-500/30 hover:bg-blue-800 transition transform active:scale-95">Simpan Data Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: CATAT PEMBAYARAN (INTEGRASI JURNAL) -->
    <div x-show="showPayment" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPayment = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl"
                 x-data="{ 
                    localNominal: @entangle('nominal_bayar'),
                    init() {
                        this.$watch('localNominal', v => { this.$refs.payInput.value = this.formatRupiah(v); });
                    }
                 }">
                <h3 class="text-2xl font-black text-slate-900 mb-2 leading-none">Terima Pembayaran</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">{{ $activeItemName }}</p>
                
                <form wire:submit="savePayment" class="space-y-6">
                    <div class="text-center">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Nominal Setoran Tunai / Transfer</label>
                        <div class="relative inline-block w-full">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-3xl font-black text-emerald-300">Rp</span>
                            <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                                class="w-full pl-16 bg-emerald-50 border-none rounded-[32px] p-8 text-center text-4xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Masuk ke Dompet</label>
                            <select wire:model="ref_account_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 appearance-none">
                                @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Terima</label>
                            <input wire:model="tanggal_bayar" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showPayment = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-xs uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase shadow-xl shadow-emerald-500/30 hover:bg-emerald-600 transition transform active:scale-95">Konfirmasi & Simpan Jurnal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: RINCIAN RIWAYAT (AUDIT TRAIL) -->
    <div x-show="showHistory" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showHistory = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-[40px] p-10 shadow-2xl text-left border border-white">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 leading-none">Rincian Audit Pembayaran</h3>
                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mt-3">Barang: {{ $activeItemName }}</p>
                    </div>
                    <button @click="showHistory = false" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"><svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-hidden border border-gray-100 rounded-3xl mb-8">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 font-black text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Tgl Bayar</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4">Metode / Akun</th>
                                <th class="px-6 py-4 text-right">No. Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($paymentHistory as $pay)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-500">{{ $pay->tanggal_bayar->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-black text-emerald-600 text-sm">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700 italic">{{ $pay->transaction->account->nama ?? 'Kasir' }}</td>
                                <td class="px-6 py-4 text-right font-mono text-gray-400 text-[10px]">{{ substr($pay->uuid, 0, 8) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-12 text-center text-gray-400 italic font-medium uppercase tracking-widest text-[10px]">Belum ada data pembayaran masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center bg-slate-900 rounded-[32px] p-6 text-white shadow-2xl shadow-slate-900/20">
                    <div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] block mb-1">Status Saldo</span>
                        <span class="text-xs font-bold text-emerald-400 uppercase">Terverifikasi Sistem</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Terbayar</span>
                        <span class="text-3xl font-black tracking-tighter">Rp {{ number_format(collect($paymentHistory)->sum('nominal'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>