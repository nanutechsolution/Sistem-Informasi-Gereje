<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight leading-none">Sekretariat Digital</h1>
                <p class="text-slate-500 mt-2 font-medium">Arsip surat keluar & pencetakan dokumen jemaat.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all uppercase tracking-widest gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                BUAT SURAT BARU
            </button>
        </div>

        <!-- Tabel Arsip -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <!-- Search -->
            <div class="p-6 border-b border-slate-100">
                <div class="relative max-w-md">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/10" placeholder="Cari nomor surat atau nama jemaat...">
                </div>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nomor & Tanggal</th>
                        <th class="px-6 py-5">Jenis & Tujuan</th>
                        <th class="px-6 py-5">Penandatangan</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($letters as $letter)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="block font-mono font-black text-primary text-xs bg-blue-50 px-2 py-1 rounded w-fit mb-1">{{ $letter->nomor_surat }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ $letter->tanggal_cetak->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-black text-slate-900 uppercase italic">{{ $letter->jenis_label }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Untuk: {{ $letter->member->nama ?? 'Hapus' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-black">{{ substr($letter->signatory->member->nama ?? '?', 0, 1) }}</div>
                                <span class="text-xs font-bold text-slate-600">{{ $letter->signatory->member->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-slate-400 hover:text-primary bg-white border border-slate-200 rounded-xl transition-all shadow-sm" title="Cetak PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </button>
                                <!-- Tombol Edit -->
                                <button wire:click="edit({{ $letter->id }})" class="p-2 text-slate-400 hover:text-amber-500 bg-white border border-slate-200 rounded-xl transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button wire:click="delete({{ $letter->id }})" wire:confirm="Hapus arsip ini?" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest text-xs">Belum ada surat keluar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">{{ $letters->links() }}</div>

        <!-- MODAL FORM -->
        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="$set('isModalOpen', false)"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl">
                    <h3 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">{{ $letterId ? 'Edit Surat' : 'Buat Surat Baru' }}</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <!-- Jenis & Tanggal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Jenis Surat</label>
                                <select wire:model.live="jenis" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                    <option value="keterangan">Keterangan Anggota</option>
                                    <option value="baptis">Surat Baptis</option>
                                    <option value="sidi">Surat Sidi</option>
                                    <option value="nikah">Akta Nikah</option>
                                    <option value="atestasi_keluar">Atestasi Keluar</option>
                                    <option value="tugas">Surat Tugas</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Tanggal Cetak</label>
                                <input wire:model.live="tanggal_cetak" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                            </div>
                        </div>

                        <!-- Generator Nomor -->
                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex justify-between items-center">
                            <div>
                                <label class="block text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Nomor Surat (Otomatis)</label>
                                <span class="font-mono font-black text-lg text-blue-900">{{ $nomor_surat }}</span>
                            </div>
                            <button type="button" wire:click="generateNumber" class="p-2 bg-white rounded-xl shadow-sm hover:bg-blue-100 text-blue-600" title="Regenerate"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                        </div>

                        <!-- Search Jemaat -->
                        <div class="relative">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Untuk Anggota Jemaat</label>
                            @if($selectedMemberName)
                                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex justify-between items-center">
                                    <span class="font-bold text-emerald-800">{{ $selectedMemberName }}</span>
                                    <button type="button" wire:click="$set('selectedMemberName', '')" class="text-[10px] font-black uppercase text-emerald-500 underline">Ganti</button>
                                </div>
                            @else
                                <input wire:model.live.debounce.300ms="searchMember" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/10" placeholder="Ketik nama...">
                                @if(count($foundMembers) > 0)
                                    <div class="absolute z-10 w-full mt-2 bg-white rounded-2xl shadow-xl overflow-hidden divide-y divide-slate-100 border border-slate-100">
                                        @foreach($foundMembers as $m)
                                            <button type="button" wire:click="selectMember({{ $m->id }}, '{{ $m->nama }}')" class="w-full text-left p-4 hover:bg-slate-50 text-sm font-bold text-slate-700">{{ $m->nama }}</button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            @error('member_id') <span class="text-rose-500 text-[10px] font-bold block mt-1 uppercase">{{ $message }}</span> @enderror
                        </div>

                        <!-- Keperluan (Kondisional) -->
                        @if($jenis == 'keterangan' || $jenis == 'tugas')
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Keperluan / Keterangan</label>
                            <textarea wire:model="keperluan" rows="2" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm" placeholder="Contoh: Persyaratan nikah, Tugas Pelayanan, dll..."></textarea>
                        </div>
                        @endif

                        <!-- Penandatangan -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Penandatangan (Pejabat)</label>
                            <select wire:model="signed_by_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm appearance-none cursor-pointer">
                                @foreach($officers as $off)
                                    <option value="{{ $off->id }}">{{ $off->member->nama }} ({{ $off->position->nama }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" wire:click="closeModal" class="flex-1 py-4 bg-slate-100 rounded-[24px] font-black text-xs uppercase text-slate-500">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-primary text-white rounded-[24px] font-black text-xs uppercase shadow-xl hover:scale-105 transition-transform">Simpan Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>