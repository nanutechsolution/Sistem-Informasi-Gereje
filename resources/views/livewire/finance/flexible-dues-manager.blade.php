<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        showBulk: @entangle('isModalOpen').live, 
        showPay: @entangle('isPayModalOpen').live, 
        showSingle: @entangle('isSingleModalOpen').live,
        formatRupiah(value) {
            if(!value) return '';
            let val = value.toString().replace(/\D/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER UTAMA -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Tanggungan Jemaat</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest leading-relaxed">
                    Manajemen Iuran Wang & Natura (Barang)
                </p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <button @click="showSingle = true" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-[24px] font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all active:scale-95">
                    INPUT MANUAL
                </button>
                <button @click="showBulk = true" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-widest shadow-xl hover:scale-105 transition-all active:scale-95">
                    GENERATE MASAL
                </button>
                <div class="bg-white border border-slate-200 rounded-[24px] p-1 flex items-center shadow-sm">
                    <span class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-tighter">Buku:</span>
                    <select wire:model.live="filterYear" class="border-none bg-transparent font-black text-sm text-slate-800 focus:ring-0 cursor-pointer pr-8">
                        @foreach($years as $y) <option value="{{ $y->id }}">{{ $y->tahun }}</option> @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- AREA SEARCH & FILTER PINTAR -->
        <div class="bg-white rounded-[32px] p-4 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari nama jemaat atau keluarga...">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/></svg>
            </div>
            <select wire:model.live="typeFilter" class="bg-slate-50 border-none rounded-2xl px-6 py-4 font-black text-sm text-slate-600 focus:ring-4 focus:ring-primary/5 appearance-none cursor-pointer">
                <option value="">Semua Jenis Tanggungan</option>
                @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }}</option> @endforeach
            </select>
        </div>

        <!-- TABEL DATA PRODUKSI -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-6">Subjek (Jiwa/KK)</th>
                        <th class="px-6 py-6">Jenis Tanggungan</th>
                        <th class="px-6 py-6 text-right">Target / Plafon</th>
                        <th class="px-6 py-6 text-right">Terbayar</th>
                        <th class="px-6 py-6 text-center">Status</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($dues as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-900 uppercase italic leading-none">{{ $item->assignee->nama ?? $item->assignee->kepala_keluarga ?? 'Subjek Terhapus' }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-2 tracking-tighter">
                                {{ str_contains($item->assignee_type, 'Member') ? '👤 Jiwa / Individu' : '🏠 Kepala Keluarga' }}
                            </p>
                        </td>
                        <td class="px-6 py-6">
                            <span class="font-bold text-slate-600 uppercase text-xs">{{ $item->dueType->nama }}</span>
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-900 uppercase">
                            {{ $item->dueType->unit_type == 'money' ? 'Rp '.number_format($item->target_nominal, 0, ',', '.') : $item->target_qty.' '.$item->dueType->satuan_barang }}
                        </td>
                        <td class="px-6 py-6 text-right font-bold text-emerald-600 italic uppercase">
                            {{ $item->dueType->unit_type == 'money' ? 'Rp '.number_format($item->current_paid_nominal, 0, ',', '.') : $item->current_paid_qty.' '.$item->dueType->satuan_barang }}
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $item->status == 'lunas' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($item->status == 'cicil' ? 'bg-blue-100 text-blue-700' : 'bg-rose-50 text-rose-600') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <button wire:click="openPayModal({{ $item->id }})" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-primary transition-all transform active:scale-95">Setoran</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-32 text-center text-slate-300 font-black uppercase tracking-widest text-xs italic animate-pulse">Data tanggungan tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $dues->links() }}</div>
    </div>

    <!-- MODAL 1: GENERATE MASAL (OTOMATIS) -->
    <div x-show="showBulk" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showBulk = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95 duration-200">
                <div class="absolute top-0 left-0 w-full h-2 bg-amber-500"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter text-center">Generate Masal</h3>
                <p class="text-[10px] text-slate-400 font-bold text-center uppercase tracking-widest mb-8">Membuat tagihan otomatis untuk seluruh jemaat</p>
                
                <form wire:submit="generateBulk" class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 italic">Jenis Tagihan</label>
                        <select wire:model.live="due_type_id" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black text-slate-700 shadow-inner focus:ring-4 focus:ring-primary/5">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }} ({{ ucfirst($dt->target_level) }})</option> @endforeach
                        </select>
                        @error('due_type_id') <span class="text-rose-600 text-[10px] font-bold mt-2 block ml-2 italic">{{ $message }}</span> @enderror
                    </div>

                    @php $gt = $dueTypes->firstWhere('id', $due_type_id); @endphp
                    @if($gt)
                    <div class="p-8 bg-slate-900 rounded-[32px] text-white">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 text-center">Nominal Standar per {{ $gt->target_level == 'member' ? 'Jiwa' : 'KK' }}</label>
                        @if($gt->unit_type == 'money')
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-slate-600">Rp</span>
                                <input wire:model="nominal_standar" type="text" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white/5 border-none rounded-2xl p-5 text-center font-black text-3xl text-white outline-none">
                            </div>
                        @else
                            <div class="flex items-center gap-4">
                                <input wire:model="qty_standar" type="number" class="flex-1 bg-white/5 border-none rounded-2xl p-5 text-center font-black text-3xl text-white outline-none" placeholder="0">
                                <span class="font-black text-xs uppercase text-slate-500 italic">{{ $gt->satuan_barang }}</span>
                            </div>
                        @endif
                    </div>
                    @endif

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showBulk = false" class="flex-1 py-6 bg-slate-100 rounded-3xl font-black text-[10px] uppercase tracking-widest text-slate-400">BATAL</button>
                        <button type="submit" class="flex-[2] py-6 bg-amber-500 text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl hover:bg-amber-600 transition active:scale-95">MULAI GENERATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: INPUT MANUAL (SINGLE) -->
    <div x-show="showSingle" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showSingle = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95 duration-200">
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center leading-none">Pendaftaran Iuran</h3>
                
                <form wire:submit="saveSingle" class="space-y-8">
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Cari Jemaat atau KK (Wajib)</label>
                        @if($selectedAssigneeName)
                            <div class="p-5 bg-blue-50 border border-blue-100 rounded-[24px] flex justify-between items-center animate-in fade-in">
                                <span class="font-black text-blue-900 text-sm italic">{{ $selectedAssigneeName }}</span>
                                <button type="button" wire:click="$set('selectedAssigneeName', null)" class="text-[9px] font-black uppercase text-rose-500 underline">Ganti Subjek</button>
                            </div>
                        @else
                            <input wire:model.live.debounce.300ms="searchAssignee" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 shadow-inner" placeholder="Ketik minimal 3 huruf...">
                            @if(count($foundAssignees) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundAssignees as $a)
                                <button type="button" wire:click="selectAssignee({{ $a['id'] }}, '{{ str_replace('\\', '\\\\', $a['type']) }}', '{{ $a['nama'] }}')" @click="open = false" class="w-full text-left p-5 hover:bg-blue-50 transition-colors">
                                    <p class="font-black text-slate-800 text-sm italic leading-none">{{ $a['label'] }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        @endif
                        @error('selectedAssigneeId') <span class="text-rose-600 text-[10px] font-bold mt-2 block uppercase italic ml-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih Jenis Tagihan</label>
                        <select wire:model.live="single_due_type_id" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black text-slate-700 shadow-inner appearance-none cursor-pointer">
                            <option value="">-- Pilih Jenis Tanggungan --</option>
                            @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }} ({{ ucfirst($dt->unit_type) }})</option> @endforeach
                        </select>
                        @error('single_due_type_id') <span class="text-rose-600 text-[10px] font-bold mt-2 block uppercase italic ml-2">{{ $message }}</span> @enderror
                    </div>

                    @php $st = $dueTypes->firstWhere('id', $single_due_type_id); @endphp
                    @if($st)
                    <div class="p-8 bg-slate-900 rounded-[32px] text-white animate-in slide-in-from-top-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 text-center">Jumlah Target Tagihan</label>
                        @if($st->unit_type == 'money')
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-slate-600">Rp</span>
                            <input wire:model="single_target_nominal" type="text" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white/5 border-none rounded-2xl p-5 text-center font-black text-3xl text-white outline-none">
                        </div>
                        @else
                        <div class="flex items-center gap-4">
                            <input wire:model="single_target_qty" type="number" class="flex-1 bg-white/5 border-none rounded-2xl p-5 text-center font-black text-3xl text-white outline-none" placeholder="0">
                            <span class="font-black text-xs uppercase text-slate-500 italic">{{ $st->satuan_barang }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showSingle = false" class="flex-1 py-6 bg-slate-100 rounded-3xl font-black text-[10px] uppercase tracking-widest text-slate-400">BATAL</button>
                        <button type="submit" class="flex-[2] py-6 bg-primary text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl hover:bg-blue-800 transition transform active:scale-95 shadow-blue-500/30">DAFTARKAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: UPDATE / TERIMA SETORAN -->
    @if($isPayModalOpen)
    <div x-show="showPay" x-cloak class="fixed inset-0 z-[110] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showPay = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-md bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl transition-all animate-in slide-in-from-bottom duration-300">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                <h3 class="text-2xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-none">Terima Setoran</h3>
                <p class="text-[10px] font-black text-slate-400 text-center uppercase mb-10 border-b border-slate-50 pb-4">
                    {{ $activeDue->assignee->nama ?? $activeDue->assignee->kepala_keluarga }}
                </p>
                
                <form wire:submit="savePayment" class="space-y-8 text-left">
                    @if($activeDue->dueType->unit_type == 'money')
                        <div class="bg-emerald-50 rounded-[40px] p-8 border border-emerald-100">
                            <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-4 text-center italic tracking-[0.2em]">Uang Diterima (Rp)</label>
                            <input wire:model="pay_nominal" type="text" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white border-none rounded-3xl p-6 text-center font-black text-4xl text-emerald-700 shadow-sm focus:ring-4 focus:ring-emerald-200">
                            @error('pay_nominal') <span class="text-rose-500 text-[10px] font-bold mt-3 block text-center uppercase italic">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-4 bg-slate-50 p-6 rounded-3xl border border-slate-100">
                            <div>
                                <label class="block text-[10px] font-black text-primary uppercase ml-1 mb-2 tracking-widest italic">Simpan Ke Kas</label>
                                <select wire:model="ref_account_id" class="w-full bg-white border border-slate-200 rounded-xl p-4 font-bold text-sm text-slate-700">
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 tracking-widest italic">Pos Pelaporan</label>
                                <select wire:model="ref_budget_post_id" class="w-full bg-white border border-slate-200 rounded-xl p-4 font-bold text-sm text-slate-700">
                                    <option value="">-- Pilih Pos Anggaran --</option>
                                    @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50/50 p-10 rounded-[40px] border border-blue-100 text-center">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-4 tracking-widest">Jumlah Barang ({{ $activeDue->dueType->satuan_barang }})</label>
                            <input wire:model="pay_qty" type="number" class="w-full bg-white border-none rounded-3xl p-8 text-center font-black text-5xl text-blue-700" placeholder="0">
                            <p class="text-[9px] font-bold text-blue-400 mt-4 uppercase italic">* Inventaris akan bertambah otomatis</p>
                        </div>
                    @endif

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showPay = false" class="flex-1 py-6 bg-slate-100 rounded-[32px] font-black text-[10px] uppercase text-slate-500">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-6 bg-emerald-500 text-white rounded-[32px] font-black text-[10px] uppercase shadow-2xl hover:bg-emerald-600 transition transform active:scale-95 shadow-emerald-500/30">
                            <span wire:loading.remove italic>VERIFIKASI SETORAN</span>
                            <span wire:loading>Memproses Data...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>