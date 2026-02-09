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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Total Piutang</p>
                <p class="text-xl font-black text-slate-900">Rp {{ number_format($event->auctions->sum('harga_jadi'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-emerald-500 uppercase mb-1">Total Masuk</p>
                <p class="text-xl font-black text-emerald-600">Rp {{ number_format($event->auctions->sum('total_terbayar_cache'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-[32px] text-white shadow-xl">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Sisa Piutang</p>
                <p class="text-xl font-black italic">Rp {{ number_format($event->auctions->sum('harga_jadi') - $event->auctions->sum('total_terbayar_cache'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden overflow-x-auto">
            <div class="p-6 border-b border-slate-50">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-primary/10" placeholder="Cari barang atau pemenang...">
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-6">Barang</th>
                        <th class="px-6 py-6 text-center">Pemenang</th>
                        <th class="px-6 py-6 text-right">Harga Jadi</th>
                        <th class="px-6 py-6 text-right">Terbayar</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="font-black text-slate-900">{{ $item->nama_barang }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-1">Donatur: {{ $item->donatur_nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="px-3 py-1 bg-slate-100 rounded-xl font-black text-[10px] uppercase text-slate-600">{{ $item->pemenang_nama ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-6 text-right font-black">Rp {{ number_format($item->harga_jadi, 0, ',', '.') }}</td>
                        <td class="px-6 py-6 text-right">
                            <button wire:click="openHistoryModal({{ $item->id }})" class="flex flex-col items-end ml-auto">
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase {{ $item->status_lunas ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    Rp {{ number_format($item->total_terbayar_cache, 0, ',', '.') }}
                                </span>
                            </button>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $item->id }})" class="p-2 text-slate-400 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5"/></svg></button>
                                @if(!$item->status_lunas)
                                <button wire:click="openPaymentModal({{ $item->id }})" class="px-4 py-2 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">Bayar</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-20 text-center text-slate-300 italic font-bold">Agenda masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: FORM BARANG -->
    <div x-show="showItem" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showItem = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl overflow-hidden"
                 x-data="{ 
                    localHarga: @entangle('harga_jadi'),
                    init() { this.$watch('localHarga', v => { if(this.$refs.hargaInput) this.$refs.hargaInput.value = this.formatRupiah(v); }); }
                 }">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
                <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">{{ $editId ? 'Ubah' : 'Input' }} Barang</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nama Barang</label>
                        <input wire:model="nama_barang" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Satu Ekor Kambing...">
                        @error('nama_barang') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <!-- DONATUR (SEARCH JEMAAT) -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Donatur</label>
                        <input wire:model.live="searchDonatur" @focus="open = true" @click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama jemaat...">
                        
                        @if(count($foundDonatur) > 0)
                        <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                            @foreach($foundDonatur as $m)
                            <button type="button" wire:mousedown.prevent="selectDonatur({{ $m['id'] }}, '{{ $m['nama'] }}')" @click="open = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors">
                                <p class="font-black text-slate-900 text-sm">{{ $m['nama'] }}</p>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- PEMENANG (SEARCH JEMAAT) -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Pemenang Lelang</label>
                        <input wire:model.live="searchPemenang" @focus="open = true" @click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama pemenang...">
                        
                        @if(count($foundPemenang) > 0)
                        <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                            @foreach($foundPemenang as $m)
                            <button type="button" wire:mousedown.prevent="selectPemenang({{ $m['id'] }}, '{{ $m['nama'] }}')" @click="open = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors">
                                <p class="font-black text-slate-900 text-sm">{{ $m['nama'] }}</p>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-primary uppercase mb-2">Harga Jadi (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl font-black text-primary/30">Rp</span>
                            <input x-ref="hargaInput" type="tel" x-on:input="localHarga = formatRupiah($el.value); $el.value = localHarga"
                                class="w-full pl-16 py-6 bg-blue-50 border-none rounded-[32px] font-black text-primary text-3xl focus:ring-4 focus:ring-primary/10">
                        </div>
                        @error('harga_jadi') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showItem = false" class="flex-1 py-5 bg-slate-100 rounded-[24px] font-black text-[10px] uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[24px] font-black text-[10px] uppercase shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TERIMA PEMBAYARAN -->
    <div x-show="showPayment" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPayment = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl"
                 x-data="{ 
                    localNominal: @entangle('nominal_bayar'),
                    init() { this.$watch('localNominal', v => { if(this.$refs.payInput) this.$refs.payInput.value = this.formatRupiah(v); }); }
                 }">
                <h3 class="text-2xl font-black text-slate-900 mb-1 leading-none italic uppercase">Terima Bayar</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-10">{{ $activeItemName }}</p>
                
                <form wire:submit="savePayment" class="space-y-8">
                    <div class="text-center">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-6">Nominal Tunai</label>
                        <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                            class="w-full bg-emerald-50 border-none rounded-[40px] p-10 text-center text-4xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200 shadow-inner">
                        @error('nominal_bayar') <p class="text-rose-600 text-[10px] font-bold mt-4 uppercase tracking-tighter">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-4 text-left">
                        <div>
                            <label class="text-[10px] font-black text-primary uppercase ml-1">Pos Anggaran</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-black text-primary focus:ring-2 focus:ring-primary/10">
                                @foreach($budgetPosts as $pos) <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option> @endforeach
                            </select>
                            @error('ref_budget_post_id') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Akun Kas</label>
                            <select wire:model="ref_account_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700">
                                @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                            </select>
                            @error('ref_account_id') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showPayment = false" class="flex-1 py-6 bg-slate-100 rounded-[32px] font-black text-[10px] uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-6 bg-emerald-500 text-white rounded-[32px] font-black text-[10px] uppercase shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 transition transform active:scale-95">Verifikasi Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: AUDIT PEMBAYARAN -->
    <div x-show="showHistory" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl" @click="showHistory = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-[50px] p-12 shadow-2xl text-left border border-white">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h3 class="text-3xl font-black text-gray-900 leading-none italic uppercase tracking-tighter">Audit Bayar</h3>
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mt-4">Item: {{ $activeItemName }}</p>
                    </div>
                    <button @click="showHistory = false" class="p-3 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors shadow-sm"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-hidden border border-slate-100 rounded-[32px] mb-10 shadow-inner bg-slate-50/50">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white border-b border-slate-100 font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Tgl</th>
                                <th class="px-6 py-5">Nominal</th>
                                <th class="px-8 py-5">Akun Kas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($paymentHistory as $pay)
                            <tr class="bg-white/50 hover:bg-white transition-colors">
                                <td class="px-8 py-5 font-bold text-slate-500">{{ $pay->tanggal_bayar->format('d/m/y') }}</td>
                                <td class="px-6 py-5 font-black text-emerald-600 text-sm">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</td>
                                <td class="px-8 py-5 font-bold text-slate-700 italic text-[10px] uppercase tracking-tighter">{{ $pay->transaction->account->nama ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-20 text-center text-slate-300 font-black uppercase tracking-widest text-[10px]">Kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-slate-900 rounded-[40px] p-10 text-white flex justify-between items-center shadow-2xl">
                    <div>
                        <span class="text-[10px] font-black uppercase text-slate-500 tracking-[0.3em] block mb-2">Total Terbayar</span>
                        <span class="text-4xl font-black tracking-tighter text-emerald-400">Rp {{ number_format(collect($paymentHistory)->sum('nominal'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>