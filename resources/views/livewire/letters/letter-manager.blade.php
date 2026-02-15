<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">Arsip Persuratan</h1>
                <p class="text-slate-500 mt-2 font-medium">Kelola surat keterangan, pindah, dan administrasi jemaat.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari No Surat / Nama..." class="w-full pl-10 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button wire:click="create" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Surat
                </button>
            </div>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($letters as $letter)
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <!-- Status Border -->
                @php
                $borderColor = match($letter->jenis) {
                'pindah' => 'bg-amber-400',
                'keterangan' => 'bg-blue-400',
                default => 'bg-slate-300'
                };
                @endphp
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderColor }}"></div>

                <div class="flex justify-between items-start mb-2 pl-2">
                    <span class="text-[10px] font-mono font-bold text-slate-400">{{ $letter->nomor_surat }}</span>
                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-wider">
                        {{ $letter->jenis }}
                    </span>
                </div>

                <div class="pl-2">
                    <h3 class="font-black text-slate-800 text-base leading-tight">{{ $letter->member->churchPeople->full_name ?? 'Jemaat Dihapus' }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $letter->keperluan ?? 'Tidak ada catatan keperluan' }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between pl-2">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">
                        {{ $letter->tanggal_cetak->format('d M Y') }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('letters.print', $letter->uuid) }}" target="_blank" class="p-2 text-slate-400 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </a>
                        <button wire:click="edit('{{ $letter->uuid }}')" class="p-2 text-amber-400 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button wire:click="delete('{{ $letter->uuid }}')" wire:confirm="Hapus arsip surat ini?" class="p-2 text-rose-400 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white py-12 text-center rounded-[32px] text-slate-400 border border-dashed border-slate-200">
                <p class="font-bold text-sm">Belum ada arsip surat.</p>
            </div>
            @endforelse

            <div class="mt-4">{{ $letters->links() }}</div>
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block bg-white rounded-[32px] shadow-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor & Tanggal</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jemaat</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis & Keperluan</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penandatangan</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($letters as $letter)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="block font-bold text-slate-800 text-sm font-mono">{{ $letter->nomor_surat }}</span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $letter->tanggal_cetak->format('d M Y') }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="block font-black text-slate-800">{{ $letter->member->churchPeople->full_name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400">NIK: {{ $letter->member->churchPeople->nik ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-0.5 bg-slate-100 rounded text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">
                                {{ $letter->jenis }}
                            </span>
                            <p class="text-xs text-slate-600 truncate max-w-[200px]">{{ $letter->keperluan ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="block font-bold text-slate-700 text-xs">{{ $letter->signatory->member->churchPeople->full_name ?? '-' }}</span>
                            <span class="text-[10px] text-primary font-bold uppercase">{{ $letter->signatory->position->nama ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('letters.print', $letter->uuid) }}" target="_blank" class="p-2 text-slate-400 hover:bg-slate-50 rounded-lg transition-colors" title="Cetak">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                                <button wire:click="edit('{{ $letter->uuid }}')" class="p-2 text-amber-400 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button wire:click="delete('{{ $letter->uuid }}')" wire:confirm="Hapus arsip?" class="p-2 text-rose-400 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100">
                {{ $letters->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL FORM SURAT -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
        <div class="bg-white w-full max-w-2xl rounded-[40px] shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in-95 duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

            <div class="p-6 sm:p-8 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Formulir Surat</h2>
                    <p class="text-slate-500 mt-1 text-xs font-medium uppercase tracking-widest">Buat atau edit surat keluar</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-6 custom-scrollbar">

                <!-- Pilih Jemaat -->
                <div class="relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Penerima / Jemaat</label>
                    @if($selectedMemberName)
                    <div class="flex justify-between items-center bg-blue-50 p-4 rounded-2xl border border-blue-100">
                        <span class="font-black text-blue-900 text-sm">{{ $selectedMemberName }}</span>
                        <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">Ganti</button>
                    </div>
                    @else
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="searchMember" type="text" placeholder="Ketik nama jemaat..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-slate-300">
                        <div class="absolute right-4 top-4 text-slate-300" wire:loading wire:target="searchMember">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
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
                        </button>
                        @endforeach
                    </div>
                    @endif
                    @endif
                    @error('member_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- Info Surat -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Surat</label>
                        <select wire:model.live="jenis" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                            <option value="keterangan">Keterangan</option>
                            <option value="pindah">Pindah / Atestasi</option>
                            <option value="baptis">Baptis</option>
                            <option value="sidi">Sidi</option>
                            <option value="nikah">Nikah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Cetak</label>
                        <input wire:model.live="tanggal_cetak" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor Surat (Otomatis)</label>
                    <input wire:model="nomor_surat" type="text" class="w-full bg-slate-100 border-none rounded-2xl p-4 font-mono font-bold text-slate-600 focus:ring-0 cursor-not-allowed" readonly>
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">*Nomor digenerate berdasarkan jenis dan tanggal.</p>
                </div>

                <!-- Keperluan & TTD -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Keperluan / Keterangan</label>
                    <textarea wire:model="keperluan" rows="3" class="w-full bg-slate-50 border-none rounded-[24px] p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 resize-none" placeholder="Contoh: Untuk keperluan administrasi sekolah..."></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pejabat Penandatangan</label>
                    <div class="relative">
                        <select wire:model="signed_by_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                            <option value="">Pilih Pejabat...</option>
                            @foreach($officers as $off)
                            <option value="{{ $off->id }}">{{ $off->member->churchPeople->full_name }} ({{ $off->position->nama }})</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-4 pointer-events-none text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg></div>
                    </div>
                    @error('signed_by_id') <span class="text-rose-500 text-[10px] font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-6 sm:p-8 border-t border-slate-50 bg-slate-50/50">
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="save">Simpan Arsip Surat</span>
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
    </style>

</div>