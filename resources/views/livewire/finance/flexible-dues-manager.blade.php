<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        showBulk: @entangle('isModalOpen').live, 
        showPay: @entangle('isPayModalOpen').live, 
        showSingle: @entangle('isSingleModalOpen').live,
        showLogs: false,
        formatRupiah(value) {
            if(!value) return '';
            let val = value.toString().replace(/\D/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }"
     @notify.window="showLogs = false"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Tanggungan Jemaat</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Manajemen Iuran Anggota (Uang) & Natura (Material/Barang).</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button @click="showSingle = true" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-900 rounded-[24px] font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">
                    Input Per Orang
                </button>
                <button @click="showBulk = true" class="flex-1 md:flex-none px-6 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                    Generate Masal
                </button>
                <select wire:model.live="filterYear" class="bg-white border-slate-200 rounded-2xl p-4 font-bold text-sm shadow-sm cursor-pointer focus:ring-2 focus:ring-primary/20">
                    @foreach($years as $y) <option value="{{ $y->id }}">{{ $y->tahun }}</option> @endforeach
                </select>
            </div>
        </div>

        <!-- 1. WIDGET STATISTIK (PROPOSAL BARU) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[32px] border border-slate-200 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Target (Uang)</p>
                <p class="text-xl font-black text-slate-900">Rp {{ number_format($dues->sum('target_nominal'), 0, ',', '.') }}</p>
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900"><svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg></div>
            </div>
            <div class="bg-emerald-500 p-6 rounded-[32px] text-white shadow-xl shadow-emerald-500/20">
                <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-1">Realisasi Masuk</p>
                <p class="text-xl font-black italic">Rp {{ number_format($dues->sum('current_paid_nominal'), 0, ',', '.') }}</p>
                @php $totalPercent = $dues->sum('target_nominal') > 0 ? ($dues->sum('current_paid_nominal') / $dues->sum('target_nominal')) * 100 : 0; @endphp
                <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-tight">Capaian: {{ number_format($totalPercent, 1) }}%</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-[32px] text-white shadow-xl">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Sisa Piutang Jemaat</p>
                <p class="text-xl font-black text-rose-400 italic">Rp {{ number_format($dues->sum('target_nominal') - $dues->sum('current_paid_nominal'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- SEARCH & FILTER -->
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5 transition-all" placeholder="Cari nama jemaat atau keluarga...">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/></svg>
            </div>
            <select wire:model.live="typeFilter" class="bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-sm cursor-pointer focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Jenis Tanggungan</option>
                @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }}</option> @endforeach
            </select>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Subjek (Jemaat/KK)</th>
                        <th class="px-6 py-5">Jenis Tanggungan</th>
                        <th class="px-6 py-5 text-right">Target</th>
                        <th class="px-6 py-5 text-right">Sudah Masuk</th>
                        <th class="px-6 py-5 text-center">Status & Progres</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($dues as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="font-black text-slate-900 uppercase italic leading-none">{{ $item->assignee->nama ?? $item->assignee->kepala_keluarga }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1">{{ $item->assignee_type == 'App\Models\Member' ? 'Individu (Jiwa)' : 'Keluarga (KK)' }}</p>
                        </td>
                        <td class="px-6 py-5 font-bold text-slate-600 uppercase text-xs">{{ $item->dueType->nama }}</td>
                        <td class="px-6 py-5 text-right font-black text-slate-900">
                            @if($item->dueType->unit_type == 'money') Rp {{ number_format($item->target_nominal, 0, ',', '.') }}
                            @else {{ $item->target_qty }} {{ $item->dueType->satuan_barang }} @endif
                        </td>
                        <td class="px-6 py-5 text-right font-bold text-emerald-600">
                            @if($item->dueType->unit_type == 'money') Rp {{ number_format($item->current_paid_nominal, 0, ',', '.') }}
                            @else {{ $item->current_paid_qty }} {{ $item->dueType->satuan_barang }} @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $item->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($item->status == 'cicil' ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $item->status }}
                                </span>
                                <!-- Progres Visual (Baru) -->
                                @php 
                                    $rowPercent = 0;
                                    if($item->dueType->unit_type == 'money') {
                                        $rowPercent = $item->target_nominal > 0 ? ($item->current_paid_nominal / $item->target_nominal) * 100 : 0;
                                    } else {
                                        $rowPercent = $item->target_qty > 0 ? ($item->current_paid_qty / $item->target_qty) * 100 : 0;
                                    }
                                @endphp
                                <div class="w-20 h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 transition-all duration-700" style="width: {{ min($rowPercent, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Tombol Riwayat (Baru) -->
                                <button @click="showLogs = true" wire:click="openPayModal({{ $item->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors" title="Riwayat Setoran">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <button wire:click="openPayModal({{ $item->id }})" @click="showLogs = false" class="px-5 py-2 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-primary transition-all">Update</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-24 text-center text-slate-300 font-black uppercase italic tracking-widest text-[10px]">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $dues->links() }}</div>
    </div>

    <!-- MODAL 1: TAMBAH MANUAL FLEXIBLE -->
    <div x-show="showSingle" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showSingle = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl animate-in zoom-in-95 duration-200 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
                <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center leading-none">Pendaftaran Natura / Wang</h3>
                
                <form wire:submit="saveSingle" class="space-y-6 text-left">
                    <!-- Cari Subjek -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Cari Jemaat atau KK (Wajib)</label>
                        @if($selectedAssigneeName)
                            <div class="p-5 bg-blue-50 border border-blue-100 rounded-3xl flex justify-between items-center animate-in fade-in">
                                <span class="font-black text-blue-900">{{ $selectedAssigneeName }}</span>
                                <button type="button" wire:click="$set('selectedAssigneeName', null)" class="text-[9px] font-black uppercase text-rose-500 underline">Ganti Nama</button>
                            </div>
                        @else
                            <input wire:model.live.debounce.300ms="searchAssignee" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300 shadow-inner" placeholder="Ketik nama jemaat...">
                            @if(count($foundAssignees) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundAssignees as $a)
                                <button type="button" wire:click="selectAssignee({{ $a['id'] }}, '{{ $a['type'] }}', '{{ $a['nama'] }}')" @click="open = false" class="w-full text-left p-4 hover:bg-blue-50 transition-colors group">
                                    <p class="font-black text-slate-800 text-sm group-hover:text-primary">{{ $a['label'] }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        @endif
                        @error('selectedAssigneeId') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">Nama Jemaat wajib dipilih.</span> @enderror
                    </div>

                    <!-- Jenis Iuran -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih Jenis Tanggungan (Wajib)</label>
                        <select wire:model.live="single_due_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-slate-700 shadow-inner focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="">-- Klik Untuk Memilih --</option>
                            @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }} ({{ $dt->unit_type == 'money' ? 'Uang/Kas' : 'Barang/Natura' }})</option> @endforeach
                        </select>
                        @error('single_due_type_id') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">Jenis tanggungan belum dipilih.</span> @enderror
                    </div>

                    <!-- Nominal / Qty -->
                    @php 
                        $selectedType = $dueTypes->firstWhere('id', $single_due_type_id);
                    @endphp

                    @if($selectedType)
                    <div class="p-6 bg-slate-900 rounded-3xl text-white animate-in slide-in-from-top-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Jumlah Komitmen / Target</label>
                        @if($selectedType->unit_type == 'money')
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-black text-slate-600 italic">Rp</span>
                            <input wire:model="single_target_nominal" type="text" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-slate-800 border-none rounded-2xl p-5 text-center font-black text-2xl text-white focus:ring-2 focus:ring-primary">
                        </div>
                        @else
                        <div class="flex items-center gap-4">
                            <input wire:model="single_target_qty" type="number" class="flex-1 bg-slate-800 border-none rounded-2xl p-5 text-center font-black text-2xl text-white" placeholder="0">
                            <span class="font-black text-xs uppercase tracking-widest text-slate-400">{{ $selectedType->satuan_barang }}</span>
                        </div>
                        <p class="text-[9px] text-center text-slate-400 mt-2 italic font-bold">Contoh: Batu/Pasir/Bambu/Hasil Bumi</p>
                        @endif
                    </div>
                    @endif

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showSingle = false" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-blue-800 transition transform active:scale-95 disabled:opacity-70">
                             <span wire:loading.remove>Daftarkan Sekarang</span>
                             <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: GENERATE MASAL -->
    <div x-show="showBulk" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showBulk = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-[40px] p-10 shadow-2xl">
                <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center leading-none">Generate Tagihan Rutin</h3>
                <form wire:submit="bulkAssign" class="space-y-6 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Jenis Iuran/Natura (Wajib)</label>
                        <select wire:model="due_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner cursor-pointer focus:ring-2 focus:ring-primary/20">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($dueTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->nama }} (Sasaran: {{ ucfirst($dt->target_level) }})</option> @endforeach
                        </select>
                        @error('due_type_id') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">Kategori wajib dipilih.</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Filter Pekerjaan (Opsional)</label>
                        <select wire:model="pekerjaan_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner cursor-pointer focus:ring-2 focus:ring-primary/20">
                            <option value="">Semua Pekerjaan</option>
                            @foreach($pekerjaans as $p) <option value="{{ $p->id }}">{{ $p->nama }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Sasaran Nominal Masal (Uang)</label>
                        <input wire:model="nominal_standar" type="number" class="w-full bg-blue-50 border-none rounded-2xl p-5 font-black text-2xl text-primary text-center focus:ring-4 focus:ring-primary/10 shadow-inner">
                        @error('nominal_standar') <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">Isi nominal target iuran.</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-5 bg-primary text-white rounded-[28px] font-black uppercase text-[10px] tracking-[0.2em] shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95">Mulai Pembebanan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: TERIMA SETORAN / RIWAYAT (DIPERBAIKI) -->
    @if($isPayModalOpen)
    <div x-show="showPay" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showPay = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-sm bg-white rounded-t-[40px] sm:rounded-[50px] p-8 sm:p-10 shadow-2xl overflow-hidden transition-all animate-in slide-in-from-bottom duration-300">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                
                <!-- Toggle Form vs History -->
                <div class="flex justify-center gap-2 mb-8 bg-slate-100 p-1 rounded-2xl">
                    <button @click="showLogs = false" :class="!showLogs ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400'" class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all italic">Setoran Baru</button>
                    <button @click="showLogs = true" :class="showLogs ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400'" class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all italic">Riwayat</button>
                </div>

                <div x-show="!showLogs">
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-slate-900 italic uppercase tracking-tighter leading-none">Verifikasi Setoran</h3>
                        <p class="text-[10px] font-bold text-slate-400 text-center uppercase mt-3 tracking-widest">
                            {{ $activeDue->assignee->nama ?? $activeDue->assignee->kepala_keluarga }}
                        </p>
                    </div>
                    
                    <form wire:submit="savePayment" class="space-y-6 text-left">
                        @if($activeDue->dueType->unit_type == 'money')
                            <div class="bg-emerald-50 rounded-[32px] p-6 border border-emerald-100">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3 text-center">Jumlah Uang Diterima (Rp)</label>
                                <input wire:model="pay_nominal" type="text" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-white border-none rounded-[24px] p-6 text-center font-black text-3xl text-emerald-700 shadow-inner focus:ring-4 focus:ring-emerald-200">
                            </div>

                            <div class="space-y-5 bg-slate-50 p-6 rounded-[32px] border border-slate-100">
                                <div>
                                    <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-2 ml-1">Pos Anggaran</label>
                                    <select wire:model="ref_budget_post_id" class="w-full bg-white border border-slate-200 rounded-2xl p-4 font-bold text-slate-700 appearance-none cursor-pointer focus:ring-2 focus:ring-primary/20">
                                        <option value="">-- Pilih Pos Laporan --</option>
                                        @foreach($budgetPosts as $bp) <option value="{{ $bp->id }}">{{ $bp->kode }} - {{ $bp->nama }}</option> @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Simpan Ke Kas</label>
                                    <select wire:model="ref_account_id" class="w-full bg-white border border-slate-200 rounded-2xl p-4 font-bold text-slate-700 appearance-none cursor-pointer">
                                        @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-50/50 p-8 rounded-[32px] border border-blue-100 text-center">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Jumlah Fisik ({{ $activeDue->dueType->satuan_barang }})</label>
                                <input wire:model="pay_qty" type="number" class="w-full bg-white border-none rounded-2xl p-6 text-center font-black text-4xl text-blue-700 shadow-sm focus:ring-4 focus:ring-primary/10" placeholder="0">
                            </div>
                        @endif

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showPay = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-500 hover:bg-slate-200">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-emerald-600 text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl hover:bg-emerald-700 transition transform active:scale-95">Simpan</button>
                        </div>
                    </form>
                </div>

                <!-- TAB RIWAYAT SETORAN (BARU) -->
                <div x-show="showLogs" class="animate-in fade-in zoom-in-95 duration-200 text-left">
                    <h3 class="text-lg font-black text-slate-900 mb-6 italic uppercase">Riwayat Setoran</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($activeDue->logs as $log)
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $log->tanggal_serah->format('d M Y') }}</p>
                                <p class="font-bold text-slate-700 text-sm">
                                    @if($activeDue->dueType->unit_type == 'money')
                                        Rp {{ number_format($log->nominal, 0, ',', '.') }}
                                    @else
                                        {{ $log->qty }} {{ $activeDue->dueType->satuan_barang }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-[8px] font-black text-slate-300 uppercase block">Petugas</span>
                                <span class="text-[9px] font-bold text-slate-500">{{ $log->user->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-10 text-slate-300 text-[10px] font-black uppercase tracking-widest italic">Belum ada riwayat setoran.</p>
                        @endforelse
                    </div>
                    <button type="button" @click="showPay = false" class="mt-8 w-full py-5 bg-slate-900 text-white rounded-[28px] font-black text-[10px] uppercase tracking-widest shadow-xl">Tutup</button>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>