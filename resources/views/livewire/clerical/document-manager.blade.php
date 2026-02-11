<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Pustaka Dokumen</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-blue-500 pl-4 uppercase text-[10px] tracking-widest">Manajemen File & Arsip Digital</p>
            </div>
            <button wire:click="create" class="px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all uppercase tracking-widest">
                + UPLOAD DOKUMEN
            </button>
        </div>

        <!-- LIST -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-4 font-bold text-sm shadow-inner" placeholder="Cari dokumen...">
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Judul Dokumen</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Ukuran</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-100 rounded-lg text-slate-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-black text-slate-900">{{ $doc->judul }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">{{ $doc->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">{{ $doc->kategori }}</span></td>
                        <td class="px-6 py-5 font-mono text-xs text-slate-500">{{ $doc->size }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-[9px] font-bold uppercase {{ $doc->is_public ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $doc->is_public ? 'Publik' : 'Internal' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button wire:click="edit({{ $doc->id }})" class="p-2 text-slate-400 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"/></svg></button>
                            <button wire:click="delete({{ $doc->id }})" wire:confirm="Hapus file ini?" class="p-2 text-slate-400 hover:text-rose-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-20 text-center text-slate-300 italic font-bold text-xs uppercase tracking-widest">Belum ada dokumen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95">
                <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>
                <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center">Upload Dokumen</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Judul File</label>
                        <input wire:model="judul" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner" placeholder="Misal: Warta Jemaat 12 Feb">
                        @error('judul') <span class="text-rose-500 text-[10px] font-bold block mt-1 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kategori</label>
                            <select wire:model="kategori" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                <option>Tata Ibadah</option>
                                <option>Warta Jemaat</option>
                                <option>Laporan Keuangan</option>
                                <option>Formulir</option>
                                <option>SK / Surat Keputusan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Visibilitas</label>
                            <select wire:model="is_public" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                <option value="1">Publik (Website)</option>
                                <option value="0">Internal (Admin)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">File (PDF/DOCX)</label>
                        <input type="file" wire:model="file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                        <div wire:loading wire:target="file" class="text-[10px] text-blue-500 font-bold mt-2 animate-pulse">Mengunggah...</div>
                        @error('file') <span class="text-rose-500 text-[10px] font-bold block mt-1 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-4 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-3xl font-black text-[10px] uppercase shadow-xl hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>