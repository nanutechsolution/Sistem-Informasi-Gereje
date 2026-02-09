<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Manajemen Personil</h1>
                <p class="text-gray-500 mt-1">Daftar Pendeta, Vicaris, Majelis, dan Karyawan Jemaat.</p>
            </div>
            <a href="{{ route('officers.create') }}" class="inline-flex justify-center items-center px-6 py-3 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:scale-105 transition-transform active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Personil
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="Cari nama atau NIP...">
                <div class="absolute left-3 top-3.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <div class="flex gap-2">
                <select wire:model.live="filterPosition" class="bg-gray-50 border-gray-200 rounded-xl text-sm font-bold text-gray-600 focus:ring-primary">
                    <option value="">Semua Jabatan</option>
                    @foreach($positions as $p) <option value="{{ $p->id }}">{{ $p->nama }}</option> @endforeach
                </select>
                <select wire:model.live="filterStatus" class="bg-gray-50 border-gray-200 rounded-xl text-sm font-bold text-gray-600 focus:ring-primary">
                    <option value="aktif">Sedang Bertugas</option>
                    <option value="non-aktif">Sudah Selesai</option>
                    <option value="semua">Semua Riwayat</option>
                </select>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama & Jabatan</th>
                        <th class="px-6 py-5">Status / Lokasi</th>
                        <th class="px-6 py-5">Masa Bakti</th>
                        <th class="px-6 py-5 text-right">Gaji Bersih</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($officers as $off)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                    {{ substr($off->member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $off->member->nama }}</div>
                                    <div class="text-xs text-primary font-bold">{{ $off->position->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter {{ $off->status_kepegawaian == 'organik' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $off->status_kepegawaian }}
                            </span>
                            <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">{{ $off->lokasi_tugas }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-bold text-gray-700">{{ $off->tanggal_mulai?->format('d/m/Y') ?? '?' }}</div>
                            <div class="text-[10px] text-gray-400 italic">s/d {{ $off->tanggal_selesai?->format('d/m/Y') ?? 'Seterusnya' }}</div>
                        </td>
                        <td class="px-6 py-5 text-right font-black text-gray-900">
                            Rp {{ number_format($off->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('officers.edit', $off) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-blue-50 rounded-lg transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                                <button wire:click="delete({{ $off->id }})" wire:confirm="Hapus personil ini? Data historis akan ikut terhapus." class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-10 text-center text-gray-400 italic font-medium">Belum ada personil terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach($officers as $off)
            <div class="bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-primary text-white flex items-center justify-center font-bold text-xl">{{ substr($off->member->nama, 0, 1) }}</div>
                        <div>
                            <h3 class="font-black text-gray-900 leading-tight">{{ $off->member->nama }}</h3>
                            <span class="text-xs font-bold text-primary">{{ $off->position->nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-50">
                    <div>
                        <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Gaji Bersih</span>
                        <span class="text-sm font-black text-gray-900">Rp {{ number_format($off->net_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('officers.edit', $off) }}" class="inline-block py-2 px-4 bg-gray-50 rounded-xl text-xs font-bold text-gray-600">Detail & Edit</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $officers->links() }}</div>
    </div>
</div>