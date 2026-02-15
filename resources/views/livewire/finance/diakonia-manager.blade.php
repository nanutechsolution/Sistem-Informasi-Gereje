<div class="py-4 sm:py-10 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none">Penyaluran Diakonia</h1>
                <p class="text-slate-500 mt-1 text-xs sm:text-sm font-medium uppercase tracking-widest border-l-4 border-primary pl-3">
                    Manajemen Bantuan Kasih Jemaat
                </p>
            </div>
            <button wire:click="$set('isModalOpen', true)" wire:loading.attr="disabled" wire:target="$set" class="w-full md:w-auto px-6 py-3 sm:py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Input Penyaluran
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-[24px] sm:rounded-[32px] p-4 sm:p-6 shadow-sm border border-slate-100 mb-6 flex flex-col sm:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl py-3 pl-10 pr-4 font-bold text-sm focus:ring-2 focus:ring-primary/20" placeholder="Cari nama penerima...">
                <svg class="w-5 h-5 text-slate-300 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest px-2">
                Total {{ $requests->total() }} Data
            </div>
        </div>

        <!-- Data Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($requests as $req)
            <div class="bg-white rounded-[32px] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary/20 group-hover:bg-primary transition-colors"></div>

                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-wider">
                        {{ $req->type->nama }}
                    </span>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase leading-none">{{ \Carbon\Carbon::parse($req->tanggal_pemberian)->format('d M Y') }}</p>
                    </div>
                </div>

                <h3 class="text-lg font-black text-slate-800 leading-tight mb-1 uppercase">
                    {{ $req->member?->churchPeople?->full_name ?? $req->nama_luar }}
                </h3>
                <p class="text-xs text-slate-500 font-medium line-clamp-1 mb-4 italic">"{{ $req->alasan_bantuan }}"</p>

                <div class="flex items-end justify-between border-t border-slate-50 pt-4 mt-auto">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Nominal</p>
                        <p class="text-lg font-black text-emerald-600 leading-none">Rp {{ number_format($req->nominal, 0, ',', '.') }}</p>
                    </div>
                    <button wire:click="exportPdf({{ $req->id }})" wire:loading.attr="disabled" wire:target="exportPdf" class="p-2 text-slate-400 hover:text-rose-500 bg-slate-50 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada riwayat penyaluran.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $requests->links() }}</div>
    </div>

    <!-- Modal Form (Mobile Friendly) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 animate-in fade-in duration-300">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('isModalOpen', false)"></div>
        <div class="relative bg-white w-full max-w-3xl rounded-t-[32px] sm:rounded-[40px] shadow-2xl overflow-hidden animate-in slide-in-from-bottom sm:slide-in-from-top-4 duration-300 max-h-[95vh] flex flex-col">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>

            <div class="p-6 sm:p-8 border-b border-slate-50 flex justify-between items-center bg-white sticky top-0 z-10">
                <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight">Form Penyaluran Diakonia</h2>
                <button wire:click="$set('isModalOpen', false)" class="p-2 text-slate-300 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-8 custom-scrollbar">

                <!-- Target Toggle -->
                <div class="flex bg-slate-100 p-1 rounded-2xl w-fit mx-auto sm:mx-0">
                    <button wire:click="$set('is_external', false)" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !$is_external ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400' }}">Jemaat</button>
                    <button wire:click="$set('is_external', true)" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $is_external ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400' }}">Pihak Luar</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recipient -->
                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Penerima Bantuan</label>
                        @if($is_external)
                        <input wire:model="nama_luar" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm" placeholder="Nama lengkap penerima luar...">
                        @else
                        @if($selectedMemberName)
                        <div class="flex justify-between items-center bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <span class="font-black text-blue-900 text-sm truncate mr-2">{{ $selectedMemberName }}</span>
                            <button wire:click="$set('selectedMemberName', '')" class="text-[10px] font-black text-rose-500 uppercase hover:underline">Ganti</button>
                        </div>
                        @else
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="searchMember" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm" placeholder="Ketik nama jemaat...">
                            @if(!empty($foundMembers))
                            <div class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundMembers as $m)
                                <button wire:click="selectMember({{ $m->id }}, '{{ $m->churchPeople?->full_name }}')" class="w-full text-left p-4 hover:bg-slate-50 transition-colors">
                                    <p class="font-black text-slate-800 text-sm">{{ $m->churchPeople?->full_name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">NIK: {{ $m->churchPeople?->nik ?? '-' }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                        @endif
                        @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Diakonia</label>
                        <select wire:model="ref_diakonia_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm appearance-none">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                        </select>
                        @error('ref_diakonia_type_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Items Repeater -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Rincian Bantuan</label>
                        <button wire:click="addItem" wire:loading.attr="disabled" wire:target="addItem" class="text-[10px] font-black text-primary uppercase bg-primary/10 px-3 py-1.5 rounded-xl hover:bg-primary hover:text-white transition-all">+ Item</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($items as $index => $item)
                        <div class="grid grid-cols-12 gap-2 bg-slate-50 p-4 rounded-2xl relative group border border-slate-100" wire:key="item-{{ $index }}">
                            <div class="col-span-12 sm:col-span-4">
                                <input wire:model.live="items.{{ $index }}.nama_item" type="text" class="w-full bg-white border-none rounded-xl p-3 text-xs font-bold shadow-sm" placeholder="Nama Bantuan/Barang">
                            </div>
                            <div class="col-span-4 sm:col-span-2">
                                <input wire:model.live="items.{{ $index }}.qty" type="number" class="w-full bg-white border-none rounded-xl p-3 text-xs font-bold shadow-sm text-center">
                            </div>
                            <div class="col-span-8 sm:col-span-2">
                                <select wire:model.live="items.{{ $index }}.ref_unit_id" class="w-full bg-white border-none rounded-xl p-3 text-[10px] font-bold shadow-sm">
                                    <option value="">Satuan</option>
                                    @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->nama }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-4 relative">
                                <div class="absolute left-3 top-3 text-[10px] font-black text-slate-300">Rp</div>
                                <input wire:model.live="items.{{ $index }}.nominal" type="number" class="w-full bg-white border-none rounded-xl py-3 pl-8 pr-3 text-xs font-black shadow-sm text-right">
                            </div>
                            <button wire:click="removeItem({{ $index }})" class="absolute -right-2 -top-2 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Financial Context -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-900 rounded-[32px] text-white shadow-lg">
                    <div>
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Kas Sumber</label>
                        <select wire:model="ref_account_id" class="w-full bg-slate-800 border-none rounded-xl p-3 text-xs font-bold text-white">
                            @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Pos Anggaran</label>
                        <select wire:model="ref_budget_post_id" class="w-full bg-slate-800 border-none rounded-xl p-3 text-xs font-bold text-white">
                            @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan / Alasan</label>
                    <textarea wire:model="alasan_bantuan" rows="3" class="w-full bg-slate-50 border-none rounded-[24px] p-4 font-bold text-sm resize-none focus:ring-2 focus:ring-primary/20"></textarea>
                    @error('alasan_bantuan') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 sm:p-8 border-t border-slate-50 bg-white sticky bottom-0 z-10">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Dana</p>
                        <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($total_nominal, 0, ',', '.') }}</p>
                    </div>
                    <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-3">
                        <span wire:loading.remove wire:target="save">Simpan Penyaluran</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

</div>