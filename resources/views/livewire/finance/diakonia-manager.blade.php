<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        showForm: @entangle('isModalOpen').live,
        formatRupiah(value) {
            if(!value) return '';
            let val = value.toString().replace(/\D/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Pelayanan Diakonia</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-rose-500 pl-4 uppercase text-[10px] tracking-widest">Penyaluran Bantuan Sosial & Kasih Jemaat</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-10 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all active:scale-95 cursor-pointer">
                + CATAT BANTUAN BARU
            </button>
        </div>

        <!-- TABLE ARSIP -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/5" placeholder="Cari nama penerima...">
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Penerima Bantuan</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5 text-right">Nominal</th>
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-rose-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-900 uppercase italic leading-none">{{ $req->member->nama }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Wilayah: {{ $req->member->family->refWilayah->nama ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 bg-slate-100 rounded-xl font-black text-[10px] uppercase text-slate-600 italic tracking-widest">{{ $req->type->nama }}</span>
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-900">Rp {{ number_format($req->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-6 font-bold text-slate-400 text-xs">{{ $req->tanggal_pemberian->format('d M Y') }}</td>
                        <td class="px-8 py-6 text-right text-[10px] font-black uppercase text-emerald-500 italic">Terverifikasi Jurnal</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase text-[10px]">Belum ada data diakonia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $requests->links() }}</div>
    </div>

    <!-- MODAL CATAT DIAKONIA -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 text-left shadow-2xl transition-all"
                 x-data="{ 
                    localNominal: @entangle('nominal'),
                    init() { this.$watch('localNominal', v => { if(this.$refs.payInput) this.$refs.payInput.value = this.formatRupiah(v); }); }
                 }">
                <div class="absolute top-0 left-0 w-full h-2 bg-rose-500"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-2 italic tracking-tighter leading-none uppercase">Catat Diakonia</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-10 border-b border-slate-50 pb-4">Dokumentasi bantuan kasih jemaat</p>
                
                <form wire:submit="save" class="space-y-8">
                    <!-- SEARCH PENERIMA -->
                    <div class="relative" x-data="{ open: false }">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 leading-none">Pilih Jemaat Penerima</label>
                        @if($selectedMemberName)
                            <div class="p-5 bg-rose-50 border border-rose-100 rounded-2xl flex justify-between items-center animate-in zoom-in-95">
                                <span class="font-black text-rose-900 text-sm italic">{{ $selectedMemberName }}</span>
                                <button type="button" wire:click="$set('selectedMemberName', null)" class="text-[9px] font-black uppercase text-rose-500 underline">Ganti</button>
                            </div>
                        @else
                            <input wire:model.live.debounce.300ms="searchMember" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-900 focus:ring-4 focus:ring-rose-100 transition-all placeholder:text-slate-300" placeholder="Ketik nama jemaat...">
                            @if(count($foundMembers) > 0)
                            <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundMembers as $m)
                                <button type="button" wire:click="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" @click="open = false" class="w-full text-left p-5 hover:bg-rose-50 transition-colors group">
                                    <p class="font-black text-slate-900 group-hover:text-rose-600 transition-colors">{{ $m['nama'] }}</p>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Kategori Bantuan</label>
                            <select wire:model="ref_diakonia_type_id" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black text-slate-700">
                                <option value="">-- Pilih --</option>
                                @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal</label>
                            <input wire:model="tanggal_pemberian" type="date" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-black">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest mb-4 text-center tracking-[0.3em]">Besar Dana Kasih (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-rose-200 italic">Rp</span>
                            <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                                class="w-full bg-rose-50 border-none rounded-[32px] p-8 text-center text-4xl font-black text-rose-700 focus:ring-4 focus:ring-rose-200 shadow-inner transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 leading-none">Catatan / Alasan Bantuan</label>
                        <textarea wire:model="alasan_bantuan" rows="2" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-900" placeholder="Misal: Biaya pendidikan anak sekolah..."></textarea>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-6 bg-slate-100 rounded-[32px] font-black text-[10px] uppercase tracking-widest text-slate-500">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-6 bg-rose-600 text-white rounded-[32px] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-rose-500/30 hover:bg-rose-700 transition active:scale-95">
                            <span wire:loading.remove italic>Verifikasi Saldo & Serahkan</span>
                            <span wire:loading>Memproses Jurnal...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>