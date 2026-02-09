<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isOpen').live }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic">Master Jabatan</h1>
                <p class="text-slate-500 mt-3 font-medium">Atur struktur organisasi dan standar gaji dasar.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                TAMBAH JABATAN
            </button>
        </div>

        <!-- List Data -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-6 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-4 focus:ring-primary/5" placeholder="Cari nama jabatan...">
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Urutan</th>
                        <th class="px-6 py-5">Nama Jabatan</th>
                        <th class="px-6 py-5">Singkatan</th>
                        <th class="px-6 py-5 text-center">Status Gaji</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($positions as $pos)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5 font-black text-slate-300">{{ $pos->urutan }}</td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-slate-900 block">{{ $pos->nama }}</span>
                        </td>
                        <td class="px-6 py-5 font-mono text-xs text-slate-500">{{ $pos->singkatan ?? '-' }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $pos->is_paid ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">
                                {{ $pos->is_paid ? 'Berbayar' : 'Sukarela' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $pos->id }})" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-primary hover:border-primary transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg></button>
                                <button wire:click="delete({{ $pos->id }})" wire:confirm="Hapus jabatan ini?" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-rose-600 hover:border-rose-200 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center text-slate-400 italic font-medium">Belum ada data jabatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $positions->links() }}</div>

        <!-- MODAL FORM -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="openModal = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl transition-all">

                    <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">{{ $positionId ? 'Edit' : 'Tambah' }} Jabatan</h3>

                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Jabatan</label>
                                <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10" placeholder="Contoh: Koster">
                                @error('nama') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Singkatan</label>
                                <input wire:model="singkatan" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 text-center" placeholder="Kst">
                            </div>
                        </div>

                        <div
                            x-data="{ isPaid: @entangle('is_paid') }"
                            class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">
                                    Mendapat Gaji / Insentif?
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    Aktifkan jika jabatan ini digaji rutin.
                                </span>
                            </div>

                            <div
                                class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full cursor-pointer"
                                :class="isPaid ? 'bg-emerald-500' : 'bg-slate-200'"
                                @click="isPaid = !isPaid">
                                <span
                                    class="absolute block w-6 h-6 bg-white border-2 rounded-full shadow transition-transform transform"
                                    :class="isPaid
                ? 'translate-x-6 border-emerald-500'
                : 'border-slate-200'"></span>
                            </div>
                        </div>


                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Urutan Tampil</label>
                            <input wire:model="urutan" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900">
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="openModal = false" class="flex-1 py-5 bg-white border-2 border-slate-100 rounded-[28px] font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-blue-800 transition transform active:scale-95">Simpan Jabatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>