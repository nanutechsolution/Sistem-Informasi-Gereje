<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ openForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Inventaris Aset</h1>
                <p class="text-slate-500 mt-2 font-medium">Pengelolaan sarana, prasarana, dan material pembangunan.</p>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-[24px] font-black text-xs shadow-xl shadow-blue-500/30 hover:scale-105 transition-all">
                TAMBAH ASET BARU
            </button>
        </div>

        <!-- Filter & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm md:col-span-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Item</p>
                <p class="text-2xl font-black text-slate-900">{{ \App\Models\Asset::sum('jumlah') }}</p>
            </div>
            <div class="md:col-span-3 bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
                <div class="relative flex-1 w-full">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-primary/10" placeholder="Cari nama barang...">
                </div>
                <select wire:model.live="filterKategori" class="w-full md:w-48 bg-slate-50 border-none rounded-2xl p-3 font-bold text-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Mebeul">Mebeul (Kursi/Meja)</option>
                    <option value="Bangunan">Bangunan / Material</option>
                    <option value="Kendaraan">Kendaraan</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Nama Barang</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5 text-center">Jumlah</th>
                        <th class="px-6 py-5">Kondisi</th>
                        <th class="px-6 py-5">Lokasi</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($assets as $asset)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-black text-slate-900 uppercase italic leading-none">{{ $asset->nama_aset }}</div>
                            <div class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter italic">Asal: {{ $asset->donatur->nama ?? $asset->asal_perolehan }}</div>
                        </td>
                        <td class="px-6 py-5 font-bold text-slate-500 uppercase text-xs">{{ $asset->kategori }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg font-black text-slate-700">{{ $asset->jumlah }} {{ $asset->satuan }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $asset->kondisi == 'baik' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ str_replace('_', ' ', $asset->kondisi) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 font-bold text-slate-400 text-xs">{{ $asset->lokasi_fisik ?? '-' }}</td>
                        <td class="px-8 py-5 text-right">
                            <button wire:click="edit({{ $asset->id }})" class="p-2 text-slate-300 hover:text-primary transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5"/></svg></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-20 text-center text-slate-300 font-black uppercase text-xs">Belum ada aset terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $assets->links() }}</div>

        <!-- MODAL FORM -->
        <div x-show="openForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openForm = false"></div>
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
                <div class="relative w-full max-w-2xl transform overflow-hidden bg-white rounded-t-[40px] sm:rounded-[40px] p-8 sm:p-12 text-left shadow-2xl transition-all">
                    
                    <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">Form Inventaris</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Nama Barang / Aset</label>
                                <input wire:model="nama_aset" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900" placeholder="Misal: Kursi Plastik Napolly">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Kategori</label>
                                <select wire:model="kategori" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="">-- Pilih --</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Mebeul">Mebeul (Kursi/Meja)</option>
                                    <option value="Bangunan">Bangunan / Material</option>
                                    <option value="Kendaraan">Kendaraan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Jumlah</label>
                                <input wire:model="jumlah" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Satuan</label>
                                <input wire:model="satuan" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" placeholder="Unit/Sack">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Kondisi</label>
                                <select wire:model="kondisi" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Asal Perolehan</label>
                                <select wire:model.live="asal_perolehan" class="w-full bg-white border-none rounded-xl p-3 font-bold">
                                    <option value="pembelian">Pembelian</option>
                                    <option value="hibah_jemaat">Hibah Jemaat (Sumbangan)</option>
                                    <option value="sinode">Bantuan Sinode</option>
                                </select>
                            </div>
                            <div class="relative" x-data="{ open: false }">
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Donatur (Jika Hibah)</label>
                                <input wire:model.live="searchMember" @focus="open = true" x-on:click.away="open = false" type="text" class="w-full bg-white border-none rounded-xl p-3 font-bold" placeholder="Cari nama jemaat..." {{ $asal_perolehan != 'hibah_jemaat' ? 'disabled' : '' }}>
                                @if($selectedMemberName) <div class="mt-2 text-[10px] font-bold text-primary italic uppercase tracking-widest">Terpilih: {{ $selectedMemberName }}</div> @endif
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="openForm = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase text-slate-400">Batal</button>
                            <button type="submit" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-[10px] uppercase shadow-2xl">Simpan Aset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>