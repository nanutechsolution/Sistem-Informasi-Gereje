<div class="py-4 sm:py-10 bg-slate-50 min-h-screen text-slate-900" x-data="{ 
    formatRupiah(val) {
        if(!val) return '';
        let num = val.toString().replace(/[^0-9]/g, '');
        return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none">Iuran Jemaat</h1>
                <p class="text-slate-500 mt-2 font-medium text-xs uppercase tracking-widest border-l-4 border-primary pl-3">Manajemen Tanggungan & Setoran</p>
            </div>
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <button wire:click="$set('isModalOpen', true)" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Generate Masal
                </button>
                <button wire:click="$set('isSingleModalOpen', true)" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Input Manual
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-[32px] p-4 sm:p-6 shadow-sm border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2 relative">
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cari Nama / KK</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama jemaat...">
                <div wire:loading wire:target="search" class="absolute right-4 top-10">
                    <svg class="animate-spin h-4 w-4 text-slate-300" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>
            <div>
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Iuran</label>
                <select wire:model.live="typeFilter" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm appearance-none">
                    <option value="">Semua Jenis</option>
                    @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tahun</label>
                <select wire:model.live="filterYear" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm appearance-none">
                    @foreach($years as $y) <option value="{{ $y->id }}">{{ $y->tahun }}</option> @endforeach
                </select>
            </div>
        </div>

        <!-- Table Desktop -->
        <div class="hidden md:block bg-white rounded-[40px] shadow-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="py-5 px-8">Subjek</th>
                        <th class="py-5 px-6">Jenis Iuran</th>
                        <th class="py-5 px-6 text-right">Tanggungan</th>
                        <th class="py-5 px-6 text-right">Terbayar</th>
                        <th class="py-5 px-8 text-center">Status</th>
                        <th class="py-5 px-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($dues as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-8">
                            <span class="block font-black text-slate-800 uppercase leading-tight">{{ $item->assignee->churchPeople->full_name ?? "KK: ".$item->assignee->nomor_kk }}</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">{{ $item->assignee_type == 'App\Models\Member' ? 'Jiwa' : 'Keluarga' }}</span>
                        </td>
                        <td class="py-4 px-6 font-bold text-xs text-slate-600 uppercase">{{ $item->dueType->nama }}</td>
                        <td class="py-4 px-6 text-right font-mono font-black text-slate-900">
                            @if($item->dueType->unit_type == 'money') Rp {{ number_format($item->target_nominal, 0, ',', '.') }}
                            @else {{ number_format($item->target_qty) }} {{ $item->dueType->satuan_barang }} @endif
                        </td>
                        <td class="py-4 px-6 text-right font-mono font-bold text-emerald-600">
                            @if($item->dueType->unit_type == 'money') Rp {{ number_format($item->current_paid_nominal, 0, ',', '.') }}
                            @else {{ number_format($item->current_paid_qty) }} @endif
                        </td>
                        <td class="py-4 px-8 text-center">
                            @php
                                $colors = ['lunas' => 'bg-emerald-100 text-emerald-600', 'cicil' => 'bg-amber-100 text-amber-600', 'belum' => 'bg-rose-100 text-rose-600'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $colors[$item->status] ?? 'bg-slate-100' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-4 px-8 text-right">
                            @if($item->status != 'lunas')
                                <button wire:click="openPayModal({{ $item->id }})" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-md hover:bg-primary transition-all">Bayar</button>
                            @else
                                <svg class="w-5 h-5 text-emerald-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach($dues as $item)
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative">
                <div class="flex justify-between items-start mb-3">
                    <div class="min-w-0">
                        <span class="block font-black text-slate-800 uppercase leading-tight truncate">{{ $item->assignee->churchPeople->full_name ?? "KK: ".$item->assignee->nomor_kk }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $item->dueType->nama }}</span>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-[8px] font-black uppercase {{ $colors[$item->status] ?? '' }}">{{ $item->status }}</span>
                </div>
                <div class="flex justify-between items-end border-t border-slate-50 pt-3">
                    <div>
                        <p class="text-[8px] font-black text-slate-300 uppercase">Sisa Tagihan</p>
                        <p class="text-xs font-black text-rose-500">
                            @if($item->dueType->unit_type == 'money') Rp {{ number_format($item->sisa_nominal, 0, ',', '.') }}
                            @else {{ number_format($item->sisa_qty) }} Item @endif
                        </p>
                    </div>
                    @if($item->status != 'lunas')
                        <button wire:click="openPayModal({{ $item->id }})" class="px-4 py-2 bg-primary text-white rounded-xl text-[9px] font-black uppercase">Setor</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $dues->links() }}</div>
    </div>

    <!-- Modal Generate Masal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
        <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-8">Generate Masal</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Iuran</label>
                    <select wire:model.live="due_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }} ({{ $dt->target_level }})</option> @endforeach
                    </select>
                    @error('due_type_id') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ localVal: @entangle('nominal_standar') }">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nominal Standar (Rp)</label>
                    <input x-model="localVal" x-on:input="localVal = formatRupiah($event.target.value)" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-xl text-slate-900">
                </div>

                <button wire:click="generateBulk" wire:loading.attr="disabled" wire:target="generateBulk" class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="generateBulk">Mulai Generate</span>
                    <span wire:loading wire:target="generateBulk" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Input Manual (Single) -->
    @if($isSingleModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
        <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-slate-900"></div>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black uppercase tracking-tight">Input Manual</h2>
                <button wire:click="$set('isSingleModalOpen', false)" class="text-slate-300 hover:text-rose-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <div class="space-y-6">
                <!-- Autocomplete Search Jemaat/KK -->
                <div class="relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cari Jemaat / KK</label>
                    @if($selectedAssigneeName)
                        <div class="flex justify-between items-center bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <span class="font-black text-blue-900 text-sm truncate mr-2">{{ $selectedAssigneeName }}</span>
                            <button wire:click="$set('selectedAssigneeName', '')" class="text-[10px] font-black text-rose-500 uppercase hover:underline">Ganti</button>
                        </div>
                    @else
                        <input wire:model.live.debounce.300ms="searchAssignee" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20" placeholder="Ketik nama atau nomor KK...">
                        @if(!empty($foundAssignees))
                            <div class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden divide-y">
                                @foreach($foundAssignees as $res)
                                    {{-- Menggunakan str_replace untuk meng-escape backslash agar tidak hilang di Javascript --}}
                                    <button wire:click="selectAssignee({{ $res['id'] }}, '{{ str_replace('\\', '\\\\', $res['type']) }}', '{{ $res['name'] }}')" class="w-full text-left p-4 hover:bg-slate-50 transition-colors">
                                        <p class="font-black text-slate-800 text-sm uppercase">{{ $res['label'] }}</p>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    @error('selectedAssigneeId') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Iuran</label>
                    <select wire:model.live="single_due_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 appearance-none">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }}</option> @endforeach
                    </select>
                    @error('single_due_type_id') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ localSingleVal: @entangle('single_target_nominal') }">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nominal Tagihan (Rp)</label>
                    <input x-model="localSingleVal" x-on:input="localSingleVal = formatRupiah($event.target.value)" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-xl text-slate-900">
                </div>

                <button wire:click="saveSingle" wire:loading.attr="disabled" wire:target="saveSingle" class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="saveSingle">Daftarkan Tanggungan</span>
                    <span wire:loading wire:target="saveSingle" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Setoran -->
    @if($isPayModalOpen)
    <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-sm animate-in zoom-in-95">
        <div class="bg-white w-full max-w-md rounded-t-[32px] sm:rounded-[40px] shadow-2xl p-8 sm:p-10">
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Terima Setoran</h2>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 border-l-4 border-emerald-500 pl-3">
                {{ $activeDue->dueType->nama }} • {{ $activeDue->assignee->churchPeople->full_name ?? "KK: ".$activeDue->assignee->nomor_kk }}
            </p>

            <div class="space-y-6">
                <div x-data="{ localPay: @entangle('pay_nominal') }">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nominal Uang (Rp)</label>
                    <input x-model="localPay" x-on:input="localPay = formatRupiah($event.target.value)" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-2xl text-emerald-600 focus:ring-0 shadow-inner">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kas Penyimpanan</label>
                    <select wire:model="ref_account_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 appearance-none">
                        @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                    </select>
                </div>

                <button wire:click="savePayment" wire:loading.attr="disabled" wire:target="savePayment" class="w-full py-5 bg-emerald-500 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-600 transition-all flex items-center justify-center gap-3 group">
                    <span wire:loading.remove wire:target="savePayment">Verifikasi & Simpan</span>
                    <span wire:loading wire:target="savePayment" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
                <button wire:click="$set('isPayModalOpen', false)" class="w-full text-[10px] font-black text-slate-300 hover:text-slate-500 uppercase tracking-widest mt-2 transition-colors">Batal & Tutup</button>
            </div>
        </div>
    </div>
    @endif
</div>