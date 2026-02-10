<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        showItem: @entangle('isModalOpen').live, 
        showPayment: @entangle('isPaymentModalOpen').live, 
        showHistory: @entangle('isHistoryModalOpen').live,
        formatRupiah(value) {
            if(!value) return '';
            let val = value.toString().replace(/\D/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('auctions.index') }}" class="text-[10px] font-black text-slate-400 hover:text-primary transition-all flex items-center gap-1 mb-3 uppercase tracking-widest group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
                <h1 class="text-3xl font-black text-slate-900 leading-none italic uppercase">{{ $event->nama_event }}</h1>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-primary text-white text-[10px] font-black uppercase rounded-full shadow-lg shadow-blue-500/20">Target: {{ $event->tujuan_kas }}</span>
                    <span class="px-3 py-1 bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase rounded-full italic">{{ \Carbon\Carbon::parse($event->tanggal_event)->isoFormat('D MMMM Y') }}</span>
                </div>
            </div>
            
            <button wire:click="create" class="inline-flex items-center px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3.5"/></svg>
                TAMBAH BARANG
            </button>
        </div>

        <!-- Progress Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-center sm:text-left">
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Total Piutang (Nota)</p>
                <p class="text-xl font-black text-slate-900">Rp {{ number_format($items->sum('harga_jadi'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-emerald-500 uppercase mb-1">Total Uang Masuk</p>
                <p class="text-xl font-black text-emerald-600">Rp {{ number_format($items->sum('total_terbayar_cache'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-[32px] text-white shadow-xl relative overflow-hidden">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-1 relative z-10">Sisa Piutang</p>
                <p class="text-xl font-black italic text-amber-400 relative z-10">Rp {{ number_format($items->sum('harga_jadi') - $items->sum('total_terbayar_cache'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Table Area -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                <div class="relative max-w-md">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari barang atau pemenang...">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <tr>
                            <th class="px-8 py-6">Barang & Donatur</th>
                            <th class="px-6 py-6 text-center">Pemenang</th>
                            <th class="px-6 py-6 text-right">Harga Jadi</th>
                            <th class="px-6 py-6 text-right">Terbayar</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($items as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-900 leading-none">{{ $item->nama_barang }}</div>
                                <div class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter italic">Donatur: {{ $item->donatur_nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-3 py-1 bg-slate-100 rounded-xl font-black text-[10px] uppercase text-slate-600">{{ $item->pemenang_nama ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-6 text-right font-black">Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}</td>
                            <td class="px-6 py-6 text-right">
                                <button wire:click="openHistoryModal({{ $item->id }})" class="ml-auto inline-block">
                                    <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase {{ $item->status_lunas ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        Rp {{ number_format($item->total_terbayar_cache, 0, ',', '.') }}
                                    </span>
                                </button>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$item->status_lunas)
                                    <button wire:click="openPaymentModal({{ $item->id }})" class="px-5 py-2 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all transform active:scale-95">Bayar</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-24 text-center text-slate-300 italic font-black uppercase tracking-widest text-xs">Belum ada barang terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-8">{{ $items->links() }}</div>
    </div>

    <!-- MODAL 1: INPUT BARANG (Dukungan Batch & Lookup) -->
    <div x-show="showItem" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="showItem = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-t-[40px] sm:rounded-[40px] p-8 sm:p-12 shadow-2xl text-left overflow-visible transition-all">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
                
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 leading-none italic uppercase tracking-tighter">{{ $editId ? 'Ubah' : 'Batch Input' }} Lelang</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Sinkronisasi Jemaat & Nota</p>
                    </div>
                    @if(!$editId)
                    <div class="text-right">
                        <label class="block text-[9px] font-black text-slate-400 uppercase mb-1">Jumlah Item</label>
                        <input wire:model.live="jumlah" type="number" min="1" max="50" class="w-20 bg-slate-50 border-none rounded-xl p-2 text-center font-black text-primary focus:ring-0 shadow-inner">
                    </div>
                    @endif
                </div>
                
                <form wire:submit="save" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Nama Barang Utama</label>
                            <input wire:model.live="nama_barang" type="text" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-slate-900 shadow-sm focus:ring-2 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Donatur</label>
                            <input wire:model.live="searchDonatur" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-slate-900 shadow-sm focus:ring-2 focus:ring-primary/10 transition-all placeholder:text-slate-200" placeholder="Cari nama...">
                            @if(count($foundDonatur) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundDonatur as $m)
                                <button type="button" wire:mousedown.prevent="selectDonatur({{ $m['id'] }}, '{{ $m['nama'] }}')" @click="open = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors">
                                    <p class="font-black text-slate-900 text-sm leading-none">{{ $m['nama'] }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[350px] overflow-y-auto pr-3 custom-scrollbar">
                        @foreach($items_list as $index => $item)
                        <div class="flex flex-col gap-2 p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center font-black text-xs text-slate-400">#{{ $index + 1 }}</div>
                                <div class="flex-1 relative">
                                    <input type="text" wire:model.live.debounce.300ms="items_list.{{ $index }}.pemenang_nama" x-on:input="$wire.searchMemberBatch({{ $index }}, $el.value)" class="w-full bg-transparent border-none p-2 font-black text-sm text-slate-800 focus:ring-0 placeholder:text-slate-300" placeholder="Pemenang...">
                                    @if($activeSearchIndex === $index && !empty($foundPemenangBatch))
                                    <div class="absolute z-[110] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50 animate-in fade-in">
                                        @foreach($foundPemenangBatch as $fp)
                                        <button type="button" wire:click="selectPemenangBatch({{ $index }}, {{ $fp['id'] }}, '{{ $fp['nama'] }}')" class="w-full text-left p-3 hover:bg-emerald-50 transition-colors">
                                            <p class="font-black text-slate-800 text-xs">{{ $fp['nama'] }}</p>
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="w-40 relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300">Rp</span>
                                    <input wire:model="items_list.{{ $index }}.harga_jadi" type="tel" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-slate-50 border-none rounded-xl p-3 pl-8 font-black text-right text-sm text-primary focus:ring-2 focus:ring-primary/10">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showItem = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-400 hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl hover:bg-blue-800 transition transform active:scale-95">SIMPAN NOTA LELANG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: VERIFIKASI PEMBAYARAN (FIXED ERRORS) -->
    <div x-show="showPayment" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPayment = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl overflow-hidden transition-all animate-in slide-in-from-bottom duration-300"
                 x-data="{ 
                    localNominal: @entangle('nominal_bayar'),
                    init() { this.$watch('localNominal', v => { if(this.$refs.payInput) this.$refs.payInput.value = this.formatRupiah(v); }); }
                 }">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Verifikasi Bayar</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-10 border-b border-slate-50 pb-4">{{ $activeItemName }}</p>
                
                <form wire:submit="savePayment" class="space-y-8 text-left">
                    <div class="text-center group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-4 tracking-[0.2em]">Jumlah Fisik Uang</label>
                        <div class="relative">
                            <span class="absolute left-8 top-1/2 -translate-y-1/2 text-3xl font-black text-emerald-200">Rp</span>
                            <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                                class="w-full bg-emerald-50 border-none rounded-[32px] p-8 text-center text-4xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200 shadow-inner transition-all">
                        </div>
                        @error('nominal_bayar') <span class="text-rose-500 text-[10px] font-bold mt-3 block uppercase animate-bounce italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-5 bg-slate-50 p-6 rounded-[32px] border border-slate-100">
                        <div>
                            <label class="block text-[10px] font-black text-primary uppercase ml-1 mb-2 tracking-widest">Target Pos Pelaporan (RAPB)</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-white border border-slate-200 rounded-2xl p-4 font-black text-sm text-primary focus:ring-4 focus:ring-primary/10 appearance-none cursor-pointer">
                                <option value="">-- Pilih Pos Anggaran --</option>
                                @foreach($budgetPosts as $pos) <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option> @endforeach
                            </select>
                            @error('ref_budget_post_id') <span class="text-rose-500 text-[9px] font-bold mt-1 block uppercase ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 tracking-widest">Dompet / Kas Utama</label>
                            <select wire:model="ref_account_id" class="w-full bg-white border border-slate-200 rounded-2xl p-4 font-bold text-sm text-slate-700 appearance-none cursor-pointer">
                                <option value="">-- Pilih Kas --</option>
                                @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                            </select>
                            @error('ref_account_id') <span class="text-rose-500 text-[9px] font-bold mt-1 block uppercase ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showPayment = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-500 hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-emerald-500 text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 transition transform active:scale-95">
                            <span wire:loading.remove>VERIFIKASI & SIMPAN</span>
                            <span wire:loading>Memproses Jurnal...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: AUDIT HISTORY (RIWAYAT CICILAN) -->
    <div x-show="showHistory" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showHistory = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-2xl bg-white rounded-[40px] p-10 shadow-2xl text-left overflow-hidden">
                <h3 class="text-2xl font-black text-slate-900 mb-6 italic uppercase tracking-tighter border-b border-slate-50 pb-4">Audit Cicilan: {{ $activeItemName }}</h3>
                
                <div class="overflow-hidden border border-slate-100 rounded-3xl bg-slate-50/50 mb-8">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white border-b border-slate-100 font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-right">Nominal</th>
                                <th class="px-6 py-4">Akun Kas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($paymentHistory as $pay)
                            <tr class="hover:bg-white transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-600">{{ $pay->tanggal_bayar->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-black text-emerald-600 text-right">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 font-bold text-slate-400 italic text-[10px] uppercase">{{ $pay->transaction->account->nama ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-16 text-center text-slate-300 font-black uppercase tracking-widest text-[10px]">Belum ada riwayat pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <button @click="showHistory = false" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl">Tutup Riwayat</button>
            </div>
        </div>
    </div>
</div>