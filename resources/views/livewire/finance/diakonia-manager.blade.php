<div class="min-h-screen bg-[#f8fafc] pb-20 antialiased"
    x-data="{ 
        showForm: @entangle('isModalOpen').live,
        isExternal: @entangle('is_external').live,
        formatRupiah(v) { 
            if(!v) return '0';
            return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
        }
    }">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        <!-- HEADER UTAMA -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-6 border-b border-slate-200">
            <div class="space-y-1 text-center md:text-left">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-none uppercase">
                    Bantuan <span class="text-[var(--primary)]">Diakonia</span>
                </h1>
                <p class="text-slate-500 font-medium text-lg">
                    Pencatatan pemberian dana kasih dan sembako jemaat.
                </p>
            </div>

            <button wire:click="create"
                class="inline-flex items-center justify-center px-8 py-4 bg-[var(--primary)] text-white font-bold text-base shadow-xl hover:brightness-110 active:scale-95 transition-all uppercase"
                style="border-radius: var(--radius-ui)">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Catat Bantuan
            </button>


        </div>

        <!-- SEARCH BOX -->
        <div class="relative group mb-10">
            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[var(--primary)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search"
                type="text"
                class="w-full pl-16 pr-6 py-5 bg-white border-2 border-slate-200 text-lg font-bold focus:border-[var(--primary)] focus:ring-0 shadow-sm transition-all placeholder:text-slate-300"
                placeholder="Cari nama penerima bantuan...">
            <div class="mt-2" style="border-radius: var(--radius-ui)"></div>
        </div>

        <!-- TABEL DATA -->
        <div class="bg-white shadow-xl border border-slate-200 overflow-hidden" style="border-radius: var(--radius-ui)">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b-2 border-slate-100">
                            <th class="px-6 sm:px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Penerima</th>
                            <th class="hidden lg:table-cell px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Rincian</th>
                            <th class="px-6 sm:px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                            <th class="px-6 sm:px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $req)
                        <tr class="hover:bg-blue-50/30 transition-colors group" wire:key="row-{{ $req->id }}">
                            <td class="px-6 sm:px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-[var(--primary)] font-black text-xl group-hover:bg-[var(--primary)] group-hover:text-white transition-all">
                                        {{ substr($req->member->nama ?? $req->nama_luar, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-extrabold text-slate-900 text-base sm:text-lg leading-tight uppercase group-hover:text-[var(--primary)]">
                                            {{ $req->member->nama ?? $req->nama_luar }}
                                        </span>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                            {{ $req->member_id ? 'Jemaat Internal' : 'Umum/Luar' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[11px] font-black text-slate-600 uppercase">{{ $req->type->nama }}</span>
                                    <span class="text-sm text-slate-400 font-semibold truncate max-w-xs">
                                        {{ $req->items->pluck('nama_item')->implode(', ') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 sm:px-8 py-6 text-right font-black text-slate-900 text-lg sm:text-xl tracking-tighter">
                                <span class="text-xs sm:text-sm text-slate-300 mr-1 font-medium">Rp</span>{{ number_format($req->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 sm:px-8 py-6 text-center text-[10px] sm:text-xs font-black uppercase text-emerald-500">Terposting</td>

                            <!-- Cari bagian <tbody> di dalam file Anda dan ganti kolom Aksi menjadi seperti ini -->
                            <td class="px-6 sm:px-8 py-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Cetak PDF -->
                                    <button wire:click="exportPdf({{ $req->id }})"
                                        wire:loading.attr="disabled"
                                        class="p-3 bg-slate-100 text-slate-600 hover:bg-[var(--primary)] hover:text-white rounded-xl transition-all shadow-sm group/print">
                                        <svg wire:loading.remove wire:target="exportPdf({{ $req->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <svg wire:loading wire:target="exportPdf({{ $req->id }})" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center opacity-30 font-black text-sm uppercase tracking-widest">Belum ada data bantuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>

    <!-- MODAL RESPONSIF -->
    <div x-show="showForm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>

        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>

        <div x-show="showForm"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="translate-y-full sm:translate-y-10"
            x-transition:enter-end="translate-y-0"
            class="relative w-full sm:max-w-2xl lg:max-w-4xl bg-white shadow-2xl overflow-hidden flex flex-col h-[90vh] sm:h-auto sm:max-h-[85vh] border-t-[8px] border-[var(--primary)]"
            style="border-radius: 1.5rem 1.5rem 0 0; @media (min-width: 640px) { border-radius: var(--radius-ui); }">

            <!-- HEADER MODAL -->
            <div class="bg-white px-6 sm:px-8 py-5 flex items-center justify-between border-b border-slate-100">
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter leading-none">Catat <span class="text-[var(--primary)]">Bantuan</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 leading-none">Lengkapi rincian di bawah ini</p>
                </div>
                <button @click="showForm = false" class="p-2 bg-slate-50 text-slate-400 hover:text-red-500 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto px-6 sm:px-8 py-6 space-y-8 custom-scrollbar">

                <!-- PESAN KESALAHAN -->
                @if ($errors->any())
                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 animate-in slide-in-from-top-2 rounded-lg">
                    <p class="font-black text-xs uppercase tracking-widest mb-1">Mohon periksa data berikut:</p>
                    <ul class="list-disc list-inside text-[11px] sm:text-xs font-bold">
                        @foreach ($errors->all() as $error)
                        <li>
                            @if(str_contains($error, 'member id')) Pilih nama jemaat terlebih dahulu.
                            @elseif(str_contains($error, 'ref diakonia type id')) Pilih Kategori/Jenis Bantuan.
                            @elseif(str_contains($error, 'ref account id')) Pilih Akun Kas Sumber Dana.
                            @elseif(str_contains($error, 'alasan bantuan')) Tuliskan alasan atau keterangan bantuan.
                            @elseif(str_contains($error, 'items.0.nama_item')) Tuliskan nama barang atau uang di baris rincian.
                            @else {{ $error }} @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- PENERIMA -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Pilih Penerima Bantuan:</label>
                    <div class="flex p-1 bg-slate-100 w-full md:w-fit rounded-xl mb-3">
                        <button type="button" wire:click="$set('is_external', false)"
                            :class="!isExternal ? 'bg-white shadow-md text-[var(--primary)]' : 'text-slate-500'"
                            class="flex-1 md:flex-none px-6 py-3 font-black text-[10px] uppercase transition-all rounded-lg text-center">Jemaat</button>
                        <button type="button" wire:click="$set('is_external', true)"
                            :class="isExternal ? 'bg-[var(--accent)] text-white shadow-md' : 'text-slate-500'"
                            class="flex-1 md:flex-none px-6 py-3 font-black text-[10px] uppercase transition-all rounded-lg text-center">Orang Luar</button>
                    </div>

                    <template x-if="!isExternal">
                        <div>
                            @if($selectedMemberName)
                            <div class="p-4 bg-slate-900 flex items-center justify-between shadow-xl" style="border-radius: 1rem">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white text-lg font-black border border-white/20">
                                        {{ substr($selectedMemberName, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-white text-sm uppercase tracking-tight leading-none">{{ $selectedMemberName }}</span>
                                        <span class="text-[9px] font-bold text-white/40 uppercase tracking-widest mt-1">Siap Dibantu</span>
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('selectedMemberName', null)" class="px-4 py-2 bg-white/10 text-[9px] font-black uppercase text-white hover:bg-red-500 transition-all rounded-lg">Ganti</button>
                            </div>
                            @else
                            <div class="relative group">
                                <input wire:model.live.debounce.300ms="searchMember" type="text"
                                    class="w-full bg-slate-50 border-2 border-slate-100 px-5 py-4 font-bold text-base focus:border-[var(--primary)] focus:ring-0 shadow-inner"
                                    placeholder="Ketik Nama Jemaat di sini..."
                                    style="border-radius: 1rem">
                                @if(count($foundMembers) > 0)
                                <div class="absolute z-30 w-full mt-2 bg-white shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50" style="border-radius: 1rem">
                                    @foreach($foundMembers as $m)
                                    <button type="button" wire:click="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" class="w-full text-left px-6 py-4 hover:bg-blue-50 flex justify-between items-center transition-all group/btn">
                                        <span class="font-black text-slate-800 group-hover/btn:text-[var(--primary)] text-sm uppercase">{{ $m['nama'] }}</span>
                                        <span class="text-[9px] font-black text-slate-300 uppercase">Klik untuk Pilih</span>
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </template>
                    <template x-if="isExternal">
                        <input wire:model="nama_luar" type="text"
                            class="w-full bg-slate-50 border-2 border-slate-100 px-5 py-4 font-bold text-base focus:border-[var(--accent)] focus:ring-0 shadow-inner uppercase"
                            placeholder="Nama Lengkap Penerima Luar..."
                            style="border-radius: 1rem">
                    </template>
                </div>

                <!-- INFO FINANSIAL -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase ml-1">Jenis Bantuan:</label>
                        <select wire:model="ref_diakonia_type_id" class="w-full bg-slate-50 border-2 border-slate-100 px-4 py-4 font-bold text-sm focus:border-[var(--primary)] appearance-none uppercase shadow-inner" style="border-radius: 1rem">
                            <option value="">-- Pilih --</option>
                            @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase ml-1">Tanggal Pemberian:</label>
                        <input wire:model="tanggal_pemberian" type="date" class="w-full bg-slate-50 border-2 border-slate-100 px-4 py-4 font-bold text-sm focus:border-[var(--primary)] shadow-inner uppercase" style="border-radius: 1rem">
                    </div>

                    <div class="md:col-span-2 bg-slate-900 p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 shadow-2xl relative overflow-hidden" style="border-radius: 1.25rem">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                        <div class="space-y-2 relative">
                            <label class="text-[9px] font-black text-white/40 uppercase block px-1 tracking-widest mb-1 leading-none">Sumber Kas Keluar:</label>
                            <select wire:model="ref_account_id" class="w-full bg-white/10 border-none px-4 py-4 text-white font-black text-sm focus:ring-4 focus:ring-[var(--accent)]/30 uppercase" style="border-radius: 0.75rem">
                                @foreach($accounts as $acc) <option value="{{ $acc->id }}" class="text-slate-900">{{ $acc->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div class="space-y-2 relative">
                            <label class="text-[9px] font-black text-white/40 uppercase block px-1 tracking-widest mb-1 leading-none">Pos Anggaran:</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-white/10 border-none px-4 py-4 text-white font-black text-sm focus:ring-4 focus:ring-[var(--accent)]/30 uppercase" style="border-radius: 0.75rem">
                                @foreach($budgetPosts as $post) <option value="{{ $post->id }}" class="text-slate-900">{{ $post->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- RINCIAN ITEM -->
                <div class="space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 px-1">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">Rincian Barang / Uang</h4>
                        <button type="button" wire:click="addItem" class="px-4 py-2 bg-slate-900 text-white font-black text-[9px] uppercase tracking-widest rounded-lg hover:opacity-90 transition-all">+ Tambah</button>
                    </div>

                    <div class="space-y-4">
                        @foreach($items as $idx => $item)
                        <div class="relative bg-slate-50 border border-slate-200 p-5 group transition-all hover:border-[var(--primary)] shadow-sm"
                            wire:key="item-row-{{ $idx }}"
                            style="border-radius: 1rem">

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-end">
                                <div class="col-span-1 lg:col-span-5 space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block px-1">Nama Barang / Dana:</label>
                                    <input type="text" wire:model.live="items.{{$idx}}.nama_item" class="w-full bg-white border-none px-4 py-3 text-xs font-black shadow-inner uppercase" placeholder="Mis: Uang Tunai">
                                </div>
                                <div class="flex gap-3 col-span-1 lg:col-span-4">
                                    <div class="w-1/2 space-y-1.5">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block text-center">Qty:</label>
                                        <input type="number" wire:model.live="items.{{$idx}}.qty" class="w-full bg-white border-none px-2 py-3 text-xs font-black text-center shadow-inner">
                                    </div>
                                    <div class="w-1/2 space-y-1.5 text-center">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block">Unit:</label>
                                        <select wire:model.live="items.{{$idx}}.satuan" class="w-full bg-white border-none px-2 py-3 text-xs font-black appearance-none text-center shadow-inner">
                                            @foreach($units as $u) <option value="{{ $u->nama }}">{{ strtoupper($u->nama) }}</option> @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-1 lg:col-span-3 space-y-1.5 relative">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block px-1">Harga Satuan (Rp):</label>
                                    <input type="number" wire:model.live="items.{{$idx}}.nominal" class="w-full bg-white border-none pl-9 pr-4 py-3 font-black text-xs shadow-inner">
                                    <span class="absolute left-3 bottom-3 text-xs font-black text-slate-300">Rp</span>
                                </div>
                            </div>

                            @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{$idx}})" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center shadow-lg hover:bg-red-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TOTAL AKHIR -->
                <div class="relative p-8 border-2 border-dashed border-slate-200 text-center bg-slate-50 group hover:bg-white transition-all overflow-hidden" style="border-radius: 1.25rem">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[var(--primary)] to-[var(--accent)]"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2 leading-none">Total Dana Dikeluarkan</p>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tighter flex items-center justify-center gap-3 leading-none">
                        <span class="text-lg sm:text-xl not-italic opacity-20 font-medium">IDR</span>
                        <span x-text="formatRupiah({{ $total_nominal }})"></span>
                    </h2>
                </div>

                <!-- ALASAN -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase ml-1 tracking-widest">Keterangan Tambahan / Alasan:</label>
                    <textarea wire:model="alasan_bantuan" rows="3" class="w-full bg-slate-50 border-2 border-slate-100 px-5 py-4 font-bold text-sm focus:border-[var(--primary)] focus:ring-0 shadow-inner uppercase" placeholder="Tulis alasan pemberian bantuan..."></textarea>
                </div>

            </form>

            <!-- FOOTER MODAL -->
            <div class="bg-slate-50 border-t border-slate-100 p-6 sm:p-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <button type="button" @click="showForm = false" class="col-span-1 py-4 bg-white border border-slate-200 font-black text-[11px] uppercase tracking-widest text-slate-400 hover:bg-slate-100 transition-all rounded-xl">Batal</button>

                <button type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="col-span-1 sm:col-span-3 py-4 bg-slate-900 text-white font-black text-sm sm:text-base uppercase tracking-[0.1em] shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3 rounded-xl"
                    style="background: var(--primary)">

                    <span wire:loading.remove wire:target="save" class="flex items-center gap-3 uppercase">
                        Posting Pelayanan
                        <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </span>

                    <span wire:loading wire:target="save" class="flex items-center gap-3 uppercase tracking-widest text-xs">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- STYLE KHUSUS -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        @media (max-width: 640px) {

            input,
            select,
            textarea {
                font-size: 16px !important;
            }
        }
    </style>
</div>