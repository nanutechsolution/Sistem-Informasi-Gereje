<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openModal: @entangle('isModalOpen').live }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Master Iuran & Natura</h1>
                <p class="text-slate-500 mt-2 font-medium">Definisikan jenis kewajiban jemaat (Uang atau Barang).</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                BUAT JENIS BARU
            </button>
        </div>

        <!-- Tabel List -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/10 transition-all" placeholder="Cari nama iuran...">
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama Iuran</th>
                        <th class="px-6 py-5">Target</th>
                        <th class="px-6 py-5">Tipe</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($types as $type)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="font-black text-slate-900 text-base italic uppercase">{{ $type->nama }}</span>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $type->target_level == 'family' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $type->target_level == 'family' ? 'Per KK' : 'Per Jiwa (Sidi)' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 font-bold text-slate-500">
                            @if($type->unit_type == 'money')
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg> Mata Uang</span>
                            @else
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2"/></svg> Natura ({{ $type->satuan_barang }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-block w-2 h-2 rounded-full {{ $type->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $type->id }})" class="p-2 bg-white border border-slate-200 text-slate-400 hover:text-primary rounded-xl shadow-sm transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5"/></svg></button>
                                <button wire:click="delete({{ $type->id }})" wire:confirm="Hapus jenis iuran ini?" class="p-2 bg-white border border-slate-200 text-slate-300 hover:text-rose-600 rounded-xl shadow-sm transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"/></svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase italic tracking-widest text-xs">Belum ada kategori iuran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MODAL FORM -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="openModal = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
                <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl text-left transition-all">
                    <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">{{ $editId ? 'Ubah' : 'Tambah' }} Jenis Iuran</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Iuran / Natura</label>
                            <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10" placeholder="Contoh: Iuran Tahunan Sidi">
                            @error('nama') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Level Target</label>
                                <select wire:model="target_level" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="member">Per Jiwa (Sidi)</option>
                                    <option value="family">Per Keluarga (KK)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Bentuk Setoran</label>
                                <select wire:model.live="unit_type" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="money">Uang Tunai</option>
                                    <option value="item">Barang (Natura)</option>
                                </select>
                            </div>
                        </div>

                        @if($unit_type == 'item')
                        <div class="animate-in slide-in-from-top-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Satuan Barang</label>
                            <input wire:model="satuan_barang" type="text" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-bold text-primary" placeholder="Contoh: Sack, Unit, Kg">
                            @error('satuan_barang') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-700">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="openModal = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-400">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl hover:bg-blue-800 transition transform active:scale-95">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>