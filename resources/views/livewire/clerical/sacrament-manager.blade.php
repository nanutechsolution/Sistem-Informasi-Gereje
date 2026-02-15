<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Pencarian Utama -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">Arsip Sakramen</h1>
                <p class="text-slate-500 mt-2 font-medium">Kelola data Baptis, Sidi, dan Pernikahan Jemaat.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / No. Surat..." class="w-full pl-10 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button wire:click="$set('isModalOpen', true)" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Arsip
                </button>
            </div>
        </div>

        <!-- Daftar Arsip -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            <!-- Mobile View (Cards) -->
            @forelse($records as $record)
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
                <div class="flex justify-between items-start mb-3">
                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase tracking-wider">
                        {{ $record->type->nama }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $record->nomor_surat }}</span>
                </div>
                <h3 class="font-black text-slate-800 text-base leading-tight">{{ $record->member->churchPeople->full_name }}</h3>
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($record->tanggal_pelaksanaan)->format('d M Y') }}
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white py-12 text-center rounded-[32px] text-slate-400 border border-dashed border-slate-200">
                <p class="font-bold">Tidak ada data arsip.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block bg-white rounded-[32px] shadow-xl border border-slate-100 overflow-hidden transition-all">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jemaat & Nomor Akta</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jenis</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Tempat</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelayan</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($records as $record)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="block font-black text-slate-800">{{ $record->member->churchPeople->full_name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono tracking-wider">{{ $record->nomor_surat }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-block px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-wider">
                                {{ $record->type->nama }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="block text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($record->tanggal_pelaksanaan)->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase font-black">{{ $record->tempat_pelaksanaan }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-xs font-bold text-slate-600 italic">Pdt. {{ $record->pelayan_firman }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('clerical.sacraments.print', $record) }}" class="p-2 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-primary shadow-sm" title="Cetak Akta">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL INPUT ARSIP -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
        <div class="bg-white w-full max-w-2xl rounded-[40px] shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in-95 duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

            <!-- Modal Header -->
            <div class="p-6 sm:p-10 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight leading-none">Input Arsip Sakramen</h2>
                    <p class="text-slate-500 mt-2 text-xs font-medium uppercase tracking-widest">Lengkapi formulir di bawah ini</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 sm:p-10 space-y-8 custom-scrollbar">

                <!-- Pilih Jemaat Utama -->
                <div class="relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Subjek Jemaat</label>
                    @if($selectedMemberName)
                    <div class="flex justify-between items-center bg-blue-50 p-4 rounded-2xl border border-blue-100">
                        <span class="font-black text-blue-900 text-sm">{{ $selectedMemberName }}</span>
                        <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">Ganti</button>
                    </div>
                    @else
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="searchMember" type="text" placeholder="Ketik Nama atau NIK..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-slate-300">
                        <div class="absolute right-4 top-4" wire:loading wire:target="searchMember">
                            <svg class="animate-spin h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    @if(!empty($foundMembers))
                    <div class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                        @foreach($foundMembers as $m)
                        <button wire:click="selectMember({{ $m->id }}, '{{ $m->churchPeople->full_name }}')" class="w-full text-left p-4 hover:bg-slate-50 transition-colors group">
                            <span class="block font-black text-slate-700 group-hover:text-primary">{{ $m->churchPeople->full_name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $m->churchPeople->nik ?? 'TANPA NIK' }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                    @endif
                    @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- Jenis & Nomor Akta -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Sakramen</label>
                        <select wire:model.live="ref_sacrament_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                            <option value="">Pilih Jenis...</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->nama }}</option>
                            @endforeach
                        </select>
                        @error('ref_sacrament_type_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor Akta / Surat</label>
                        <input wire:model="nomor_surat" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                        @error('nomor_surat') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Bagian Khusus Pernikahan (Muncul jika NKH dipilih) -->
                @if($is_marriage)
                <div class="p-6 bg-emerald-50 rounded-[32px] border border-emerald-100 animate-in slide-in-from-top-4 duration-500">
                    <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-4 text-center">Data Pasangan</h4>

                    <div class="space-y-4">
                        <div class="relative">
                            <label class="block text-[9px] font-black text-emerald-800/40 uppercase tracking-widest mb-2 ml-1">Cari Pasangan (Jemaat)</label>
                            @if($selectedPartnerName)
                            <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm">
                                <span class="font-black text-emerald-900 text-sm">{{ $selectedPartnerName }}</span>
                                <button type="button" wire:click="$set('selectedPartnerName', '')" class="text-[9px] font-black text-rose-400 uppercase tracking-widest hover:underline">Lepas</button>
                            </div>
                            @else
                            <input wire:model.live.debounce="searchPartner" type="text" placeholder="Nama pasangan jemaat..." class="w-full bg-white border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-emerald-400/20 placeholder:text-slate-300">
                            @if(!empty($foundPartners))
                            <div class="absolute z-30 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-emerald-50 overflow-hidden divide-y divide-slate-50">
                                @foreach($foundPartners as $p)
                                <button wire:click="selectPartner({{ $p->id }}, '{{ $p->churchPeople->full_name }}')" class="w-full text-left p-4 hover:bg-emerald-50 transition-colors group">
                                    <span class="block font-black text-emerald-900 group-hover:text-emerald-600">{{ $p->churchPeople->full_name }}</span>
                                    <span class="text-[9px] text-emerald-400 font-mono">{{ $p->churchPeople->nik ?? 'TANPA NIK' }}</span>
                                </button>
                                @endforeach
                            </div>
                            @endif
                            @endif
                        </div>

                        <div class="relative">
                            <div class="flex items-center gap-4 mb-2 ml-1">
                                <div class="h-[1px] flex-1 bg-emerald-100"></div>
                                <span class="text-[9px] font-black text-emerald-300 uppercase tracking-[0.3em]">Atau</span>
                                <div class="h-[1px] flex-1 bg-emerald-100"></div>
                            </div>
                            <label class="block text-[9px] font-black text-emerald-800/40 uppercase tracking-widest mb-2 ml-1">Pasangan Luar Jemaat</label>
                            <input wire:model="partner_external_name" type="text" placeholder="Ketik nama lengkap pasangan luar..." class="w-full bg-white border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-emerald-400/20 placeholder:text-slate-300">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lokasi, Waktu, Pelayan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Pelaksanaan</label>
                        <input wire:model="tanggal_pelaksanaan" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tempat Pelaksanaan</label>
                        <input wire:model="tempat_pelaksanaan" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pelayan Firman (Pendeta)</label>
                    <input wire:model="pelayan_firman" type="text" placeholder="Nama lengkap Pendeta..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Catatan Tambahan (Opsional)</label>
                    <textarea wire:model="catatan" rows="3" class="w-full bg-slate-50 border-none rounded-[24px] p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 sm:p-10 border-t border-slate-50 bg-slate-50/50">
                <button wire:click="save" wire:loading.attr="disabled" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="save">Simpan Arsip Sakramen</span>
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
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

</div>