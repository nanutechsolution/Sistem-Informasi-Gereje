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
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Data Seluruh Jemaat</h1>
                <p class="text-gray-500 mt-2 text-lg">Pencarian data jiwa anggota jemaat.</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                    placeholder="Cari Nama atau NIK...">
            </div>

            <!-- Filter Wilayah (Dinamis dari Master) -->
            <div class="w-full sm:w-56">
                <select wire:model.live="wilayahFilter" class="block w-full py-3 px-3 border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Wilayah</option>
                    @foreach($refWilayahs as $rw) 
                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option> 
                    @endforeach
                </select>
            </div>
        </div>

        <!-- TABEL DESKTOP -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Nama Jemaat</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Keluarga (KK)</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Wilayah</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Pekerjaan</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('members.show', $member) }}" class="font-bold text-primary hover:underline">
                                {{ $member->nama }}
                            </a>
                            <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $member->nik ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $member->family->kepala_keluarga ?? 'Tanpa KK' }}</div>
                            <!-- Mengambil nama dari tabel master hubungan keluarga -->
                            <div class="text-xs text-gray-500 bg-gray-100 inline-block px-1.5 rounded mt-0.5">
                                {{ $member->refHubunganKeluarga->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $member->family->refWilayah->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $member->refPekerjaan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-1">
                                <span class="text-[10px] px-1.5 py-0.5 rounded border {{ $member->status_baptis == 'Sudah' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-200' }}">B</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded border {{ $member->status_sidi == 'Sudah' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-200' }}">S</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded border {{ $member->status_nikah != 'Belum' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-200' }}">N</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('members.edit', $member) }}" class="text-gray-400 hover:text-primary font-bold text-xs">Edit</a>
                                
                                @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                                <button wire:click="delete('{{ $member->id }}')" wire:confirm="Hapus data {{ $member->nama }}?" class="text-gray-400 hover:text-red-600 font-bold text-xs">Hapus</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Data jemaat tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TAMPILAN MOBILE -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($members as $member)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('members.show', $member) }}" class="font-bold text-gray-900 text-lg hover:text-primary">
                                {{ $member->nama }}
                            </a>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $member->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                {{ $member->jenis_kelamin }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $member->refHubunganKeluarga->nama ?? '-' }} di kel. {{ $member->family->kepala_keluarga ?? '-' }}
                        </p>
                    </div>
                    <span class="text-xs font-bold bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">
                        {{ $member->family->refWilayah->nama ?? '-' }}
                    </span>
                </div>
                
                <div class="flex items-center gap-2 pt-3 mt-2 border-t border-gray-50">
                    <a href="{{ route('members.edit', $member) }}" class="flex-1 text-center text-sm font-bold text-gray-600 bg-gray-50 py-2 rounded-lg border border-gray-200">Edit</a>
                    
                    @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                    <button wire:click="delete('{{ $member->id }}')" wire:confirm="Hapus?" class="flex-1 text-center text-sm font-bold text-red-600 bg-red-50 py-2 rounded-lg border border-red-100">Hapus</button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">Data tidak ditemukan.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>
</div>