<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-primary transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Batal
            </a>
            <h1 class="text-xl font-extrabold text-gray-900">Transaksi Baru</h1>
            <div class="w-10"></div> <!-- Spacer agar judul di tengah -->
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden"
            x-data="{ 
                nominal: @entangle('nominal'),
                formatRupiah(value) {
                    if(!value) return '';
                    return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },
                init() {
                    this.$watch('nominal', value => {
                        // Pastikan input selalu terformat saat model berubah
                        if(this.$refs.nominalInput.value !== value) {
                            this.$refs.nominalInput.value = value;
                        }
                    });
                }
             }">

            <!-- 1. Tabs Jenis Transaksi (Sticky Style) -->
            <div class="grid grid-cols-3 bg-gray-50/50 p-2 gap-2 border-b border-gray-100">
                <button wire:click="$set('jenis', 'masuk')" class="py-3 rounded-2xl text-sm font-extrabold transition-all transform active:scale-95 {{ $jenis === 'masuk' ? 'bg-green-100 text-green-700 shadow-sm ring-1 ring-green-200' : 'text-gray-400 hover:bg-gray-100' }}">
                    <span class="block">⬇ Masuk</span>
                </button>
                <button wire:click="$set('jenis', 'keluar')" class="py-3 rounded-2xl text-sm font-extrabold transition-all transform active:scale-95 {{ $jenis === 'keluar' ? 'bg-red-100 text-red-700 shadow-sm ring-1 ring-red-200' : 'text-gray-400 hover:bg-gray-100' }}">
                    <span class="block">⬆ Keluar</span>
                </button>
                <button wire:click="$set('jenis', 'pindah_buku')" class="py-3 rounded-2xl text-sm font-extrabold transition-all transform active:scale-95 {{ $jenis === 'pindah_buku' ? 'bg-blue-100 text-blue-700 shadow-sm ring-1 ring-blue-200' : 'text-gray-400 hover:bg-gray-100' }}">
                    <span class="block">⇄ Transfer</span>
                </button>
            </div>

            <form wire:submit="save" class="p-6 sm:p-8 space-y-8">

                <!-- INPUT NOMINAL BESAR -->
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nominal Uang</label>
                    <div class="relative">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 text-2xl font-bold text-gray-400 pl-4">Rp</span>
                        <input x-ref="nominalInput" type="tel"
                            x-on:input="nominal = formatRupiah($el.value); $el.value = nominal"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent rounded-2xl text-3xl font-extrabold text-gray-900 focus:bg-white focus:border-primary focus:ring-0 transition-all placeholder-gray-300"
                            placeholder="0">
                    </div>
                    @error('nominal') <p class="text-red-500 text-xs mt-2 font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>{{ $message }}</p> @enderror
                </div>

                <div class="space-y-5">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" wire:model="tanggal" class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl p-3.5 focus:ring-2 focus:ring-primary/20 focus:border-primary font-medium shadow-sm">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- SEARCHABLE SELECT: AKUN (Sumber) -->
                    <div x-data="{ open: false, search: '' }">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            {{ $jenis === 'masuk' ? 'Masuk ke Dompet' : ($jenis === 'keluar' ? 'Ambil dari Dompet' : 'Dari Dompet') }}
                        </label>

                        <!-- Trigger Button -->
                        <button type="button" @click="open = true" class="w-full bg-white border border-gray-200 rounded-xl p-3.5 flex items-center justify-between shadow-sm hover:border-primary transition-colors group text-left">
                            <span class="font-medium text-gray-900">
                                {{ $accounts->firstWhere('id', $ref_account_id)->nama ?? '-- Pilih Akun --' }}
                            </span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        @error('ref_account_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <!-- Modal Selection -->
                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>
                            <div class="relative min-h-screen flex items-end sm:items-center justify-center p-0 sm:p-4">
                                <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
                                    <!-- Header dengan Close -->
                                    <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input x-model="search" type="text" class="w-full bg-gray-100 border-none rounded-xl pl-10 p-3 focus:ring-0 font-medium" placeholder="Cari akun..." autofocus>
                                        </div>
                                        <button @click="open = false" class="p-2 bg-gray-100 rounded-xl text-gray-500 hover:bg-gray-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="overflow-y-auto p-2 space-y-1">
                                        @foreach($accounts as $acc)
                                        <button type="button"
                                            x-show="!search || '{{ strtolower($acc->nama) }}'.includes(search.toLowerCase())"
                                            wire:click="$set('ref_account_id', '{{ $acc->id }}')"
                                            @click="open = false"
                                            class="w-full text-left p-3 rounded-xl hover:bg-blue-50 flex items-center justify-between group transition-colors">
                                            <span class="font-bold text-gray-700 group-hover:text-primary">{{ $acc->nama }}</span>
                                            @if($ref_account_id == $acc->id) <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg> @endif
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TRANSFER: Akun Tujuan -->
                    @if($jenis === 'pindah_buku')
                    <div x-data="{ open: false, search: '' }" class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <label class="block text-sm font-bold text-blue-800 mb-2">Ke Akun/Dompet Tujuan</label>
                        <button type="button" @click="open = true" class="w-full bg-white border border-blue-200 rounded-xl p-3.5 flex items-center justify-between shadow-sm text-left">
                            <span class="font-bold text-gray-900">{{ $accounts->firstWhere('id', $target_account_id)->nama ?? '-- Pilih Tujuan --' }}</span>
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        @error('target_account_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <!-- Modal -->
                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>
                            <div class="relative min-h-screen flex items-end sm:items-center justify-center p-0 sm:p-4">
                                <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
                                    <!-- Header dengan Close -->
                                    <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <input x-model="search" type="text" class="w-full bg-gray-100 border-none rounded-xl p-3 text-sm focus:ring-0 font-medium" placeholder="Cari tujuan..." autofocus>
                                        </div>
                                        <button @click="open = false" class="p-2 bg-gray-100 rounded-xl text-gray-500 hover:bg-gray-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="overflow-y-auto p-2">
                                        @foreach($accounts as $acc)
                                        <button type="button"
                                            x-show="!search || '{{ strtolower($acc->nama) }}'.includes(search.toLowerCase())"
                                            wire:click="$set('target_account_id', '{{ $acc->id }}')"
                                            @click="open = false"
                                            class="w-full text-left p-3 rounded-xl hover:bg-blue-50 font-bold text-gray-700">
                                            {{ $acc->nama }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- SEARCHABLE SELECT: POS ANGGARAN -->
                    @if($jenis !== 'pindah_buku')
                    <div x-data="{ open: false, search: '' }">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pos Anggaran</label>
                        <button type="button" @click="open = true" class="w-full bg-white border border-gray-200 rounded-xl p-3.5 flex items-center justify-between shadow-sm hover:border-primary transition-colors group text-left">
                            @php
                            $selectedPost = $budgetPosts->firstWhere('id', $ref_budget_post_id);
                            @endphp
                            <span class="font-medium {{ $selectedPost ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $selectedPost ? $selectedPost->kode . ' - ' . $selectedPost->nama : '-- Pilih Pos Anggaran --' }}
                            </span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        @error('ref_budget_post_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <!-- Modal Selection Pos -->
                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>
                            <div class="relative min-h-screen flex items-end sm:items-center justify-center p-0 sm:p-4">
                                <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
                                    <!-- Header dengan Close -->
                                    <div class="p-4 border-b border-gray-100 sticky top-0 bg-white z-10 flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <input x-model="search" type="text" class="w-full bg-gray-100 border-none rounded-xl p-3 text-sm font-medium focus:ring-0" placeholder="Ketik kode atau nama pos..." autofocus>
                                        </div>
                                        <button type="button" @click="open = false" class="p-2 bg-gray-100 rounded-xl text-gray-500 hover:bg-gray-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="overflow-y-auto p-2 space-y-1">
                                        @foreach($budgetPosts as $post)
                                        <button type="button"
                                            x-show="!search || '{{ strtolower($post->nama . $post->kode) }}'.includes(search.toLowerCase())"
                                            wire:click="$set('ref_budget_post_id', '{{ $post->id }}')"
                                            @click="open = false"
                                            class="w-full text-left p-3 rounded-xl hover:bg-blue-50 flex flex-col group transition-colors border-b border-gray-50 last:border-0">
                                            <span class="text-xs font-bold text-gray-400 group-hover:text-blue-400">{{ $post->kode }}</span>
                                            <span class="font-bold text-gray-800 group-hover:text-primary">{{ $post->nama }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Transaksi</label>
                        <textarea wire:model="keterangan" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm" placeholder="Contoh: Kolekte Ibadah Minggu..."></textarea>
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        wire:loading.attr="disabled" wire:target='save'
                        class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-lg font-extrabold text-white transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:scale-100
                        {{ $jenis === 'masuk' ? 'bg-gradient-to-r from-green-600 to-green-500 hover:to-green-600 shadow-green-500/30' : ($jenis === 'keluar' ? 'bg-gradient-to-r from-red-600 to-red-500 hover:to-red-600 shadow-red-500/30' : 'bg-gradient-to-r from-blue-600 to-blue-500 hover:to-blue-600 shadow-blue-500/30') }}">
                        <span wire:loading.remove wire:target='save'>SIMPAN TRANSAKSI</span>
                        <span wire:loading wire:target='save' class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            MENYIMPAN...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>