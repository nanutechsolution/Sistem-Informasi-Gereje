<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter leading-none italic uppercase">Administrasi Sakramen</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-amber-500 pl-4 uppercase text-[10px] tracking-widest leading-relaxed">Arsip Baptis, Sidi, & Pernikahan</p>
            </div>
            <button wire:click="$set('isModalOpen', true)" class="px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all active:scale-95 uppercase tracking-widest">
                + ARSIPKAN DATA BARU
            </button>
        </div>

        <!-- TABEL ARSIP -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-4 font-bold text-sm shadow-inner focus:ring-2 focus:ring-amber-200" placeholder="Cari nama atau nomor surat...">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                        <tr>
                            <th class="px-8 py-5">Subjek / Pasangan</th>
                            <th class="px-6 py-5">Kategori</th>
                            <th class="px-6 py-5 text-right">Tanggal</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $rec)
                        <tr class="hover:bg-amber-50/20 transition-colors">
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-900 uppercase italic leading-none">{{ $rec->member->nama }}</p>
                                @if($rec->type->kode === 'NKH')
                                    <p class="text-[9px] font-bold text-primary mt-1 uppercase italic">Dengan: {{ $rec->partner->nama ?? $rec->partner_external_name }}</p>
                                @else
                                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">No Reg: {{ $rec->nomor_surat }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-6">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-xl font-black text-[10px] uppercase tracking-widest">{{ $rec->type->nama }}</span>
                            </td>
                            <td class="px-6 py-6 text-right font-bold text-slate-600">
                                {{ $rec->tanggal_pelaksanaan->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('clerical.sacraments.print', $rec) }}" target="_blank" class="p-2 bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 rounded-xl transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-24 text-center text-slate-300 font-black uppercase text-xs italic">Belum ada arsip sakramen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-8">{{ $records->links() }}</div>
    </div>

    <!-- MODAL INPUT DENGAN LOGIKA NIKAH -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95">
                <div class="absolute top-0 left-0 w-full h-2 bg-amber-500"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center">Registrasi Sakramen</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. PILIH KATEGORI TERLEBIH DAHULU -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Pelayanan Sakramen</label>
                            <select wire:model.live="ref_sacrament_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-slate-700 cursor-pointer">
                                <option value="">-- Pilih Jenis Sakramen --</option>
                                @foreach($types as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
                            </select>
                        </div>

                        <!-- 2. PILIH MEMPELAI 1 / JEMAAT UTAMA -->
                        <div class="relative md:col-span-1" x-data="{ open: false }">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                                {{ $is_marriage ? 'Mempelai 1 (Jemaat)' : 'Nama Jemaat' }}
                            </label>
                            @if($selectedMemberName)
                                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex justify-between items-center">
                                    <span class="font-bold text-amber-900">{{ $selectedMemberName }}</span>
                                    <button type="button" wire:click="$set('selectedMemberName', null)" class="text-[9px] font-black text-rose-500 underline uppercase">Ganti</button>
                                </div>
                            @else
                                <input wire:model.live.debounce.300ms="searchMember" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-100 border-none rounded-2xl p-4 font-bold" placeholder="Cari nama...">
                                @if(count($foundMembers) > 0)
                                <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border overflow-hidden">
                                    @foreach($foundMembers as $m)
                                    <button type="button" wire:click="selectMember({{ $m['id'] }}, '{{ $m['nama'] }}')" class="w-full text-left p-3 hover:bg-amber-50 font-bold text-sm border-b">{{ $m['nama'] }}</button>
                                    @endforeach
                                </div>
                                @endif
                            @endif
                        </div>

                        <!-- 3. PILIH MEMPELAI 2 (KHUSUS NIKAH) -->
                        @if($is_marriage)
                        <div class="relative md:col-span-1" x-data="{ open: false, isExternal: false }">
                            <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-2 ml-1">Mempelai 2 (Pasangan)</label>
                            
                            @if($selectedPartnerName)
                                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex justify-between items-center">
                                    <span class="font-bold text-blue-900">{{ $selectedPartnerName }}</span>
                                    <button type="button" wire:click="$set('selectedPartnerName', null)" class="text-[9px] font-black text-rose-500 underline uppercase">Ganti</button>
                                </div>
                            @elseif($partner_external_name)
                                <div class="p-4 bg-slate-100 border rounded-2xl flex justify-between items-center">
                                    <span class="font-bold text-slate-700">{{ $partner_external_name }} (Luar Jemaat)</span>
                                    <button type="button" wire:click="$set('partner_external_name', null)" class="text-[9px] font-black text-rose-500 underline uppercase">Ganti</button>
                                </div>
                            @else
                                <div x-show="!isExternal">
                                    <input wire:model.live.debounce.300ms="searchPartner" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-slate-100 border-none rounded-2xl p-4 font-bold" placeholder="Cari jemaat pasangan...">
                                    <button type="button" @click="isExternal = true" class="mt-2 text-[9px] font-black text-blue-500 uppercase italic underline">Pasangan dari Luar Jemaat?</button>
                                    @if(count($foundPartners) > 0)
                                    <div x-show="open" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border overflow-hidden">
                                        @foreach($foundPartners as $p)
                                        <button type="button" wire:click="selectPartner({{ $p['id'] }}, '{{ $p['nama'] }}')" class="w-full text-left p-3 hover:bg-blue-50 font-bold text-sm border-b">{{ $p['nama'] }}</button>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div x-show="isExternal" class="animate-in fade-in">
                                    <input wire:model="partner_external_name" type="text" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-bold" placeholder="Input Nama Lengkap Manual...">
                                    <button type="button" @click="isExternal = false" class="mt-2 text-[9px] font-black text-slate-400 uppercase underline">Cari di Database Jemaat?</button>
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- 4. DATA PELAKSANAAN -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor Register</label>
                            <input wire:model="nomor_surat" type="text" class="w-full bg-slate-100 border-none rounded-2xl p-4 font-black text-slate-500 text-xs" readonly>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Layanan</label>
                            <input wire:model="tanggal_pelaksanaan" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pendeta Pelayan</label>
                            <input wire:model="pelayan_firman" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner" placeholder="Pdt. ...">
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-6 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-400 tracking-widest">Batal</button>
                        <button type="submit" class="flex-[2] py-6 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl hover:bg-amber-600 transition tracking-widest">ARSIPKAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>