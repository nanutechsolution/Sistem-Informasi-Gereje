<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
        @if (session()->has('error'))
            $dispatch('notify', { message: '{{ session('error') }}', type: 'error' });
        @endif
    "
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Data Keluarga</h1>
                <p class="text-gray-500 mt-2 text-lg">Basis data Kepala Keluarga (KK) dan Domisili.</p>
            </div>
            
            <a href="{{ route('families.create') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah KK Baru
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-150 ease-in-out" 
                    placeholder="Cari Kepala Keluarga atau No. KK...">
            </div>
            
            <!-- Filter Status -->
            <div class="w-full sm:w-48">
                <div class="relative">
                    <select wire:model.live="statusFilter" class="block w-full py-3 pl-3 pr-10 border border-gray-200 bg-gray-50 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="pindah">Pindah</option>
                        <option value="keluar">Keluar</option>
                        <option value="disiplin">Disiplin</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE VIEW: Cards -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($families as $family)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden">
                <!-- Header Kartu -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-wide">
                                NO. KK: {{ $family->nomor_kk }}
                            </span>
                            @if($family->status !== 'aktif')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">
                                    {{ $family->status }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $family->kepala_keluarga }}</h3>
                    </div>
                    <!-- Badge Wilayah (Support Relasi Baru) -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        {{ $family->refWilayah->nama ?? 'Tanpa Wilayah' }}
                    </span>
                </div>
                
                <!-- Alamat -->
                <p class="text-sm text-gray-500 mb-4 flex items-start">
                    <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ Str::limit($family->alamat, 60) }}
                </p>
                
                <!-- Footer Kartu -->
                <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                    <a href="{{ route('families.edit', $family) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 border border-gray-200 transition-colors">
                        Edit & Anggota
                    </a>
                    
                    @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                    <button wire:click="delete('{{ $family->id }}')" wire:confirm="Hapus KK ini beserta seluruh anggotanya?" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-300">
                <p class="text-gray-500">Belum ada data keluarga.</p>
            </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Kepala Keluarga & No. KK</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Wilayah</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Alamat Domisili</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($families as $family)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-primary font-bold text-lg">
                                    {{ substr($family->kepala_keluarga, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-base">{{ $family->kepala_keluarga }}</div>
                                    <div class="text-xs font-mono text-gray-500 mt-0.5 bg-gray-100 inline-block px-1.5 rounded">{{ $family->nomor_kk }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <!-- Support relasi baru -->
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $family->refWilayah->nama ?? 'Tanpa Wilayah' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ Str::limit($family->alamat, 50) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($family->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 capitalize">
                                    {{ $family->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('families.edit', $family) }}" class="p-2 text-gray-400 hover:text-primary bg-transparent hover:bg-blue-50 rounded-lg transition-all" title="Edit & Detail Anggota">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                
                                @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                                <button wire:click="delete('{{ $family->id }}')" wire:confirm="Yakin menghapus KK ini?" class="p-2 text-gray-400 hover:text-red-600 bg-transparent hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p>Tidak ada data keluarga yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $families->links() }}
        </div>
    </div>
</div>