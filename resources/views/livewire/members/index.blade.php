<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
        @if (session()->has('error'))
            $dispatch('notify', { message: '{{ session('error') }}', type: 'error' });
        @endif
    ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Jemaat</h1>
                <p class="text-gray-500 mt-1 text-base">Manajemen data jiwa dan status keanggotaan.</p>
            </div>
            <div class="flex gap-2">
                </div>
        </div>

        <div class="flex flex-wrap border-b border-gray-200 mb-6 gap-4 sm:gap-8">
            @foreach(['aktif' => 'Jemaat Aktif', 'pindah' => 'Pindah', 'meninggal' => 'Meninggal'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" 
                    class="pb-4 text-sm font-bold transition-all relative {{ $statusTab == $key ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
                    {{ $label }}
                    @if($statusTab == $key)
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-primary rounded-t-full"></div>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Cari Nama atau NIK...">
                </div>

                <select wire:model.live="wilayahFilter" class="block w-full py-2.5 px-3 text-sm border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    <option value="">Semua Wilayah</option>
                    @foreach($refWilayahs as $rw)
                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                    @endforeach
                </select>

                <select wire:model.live="pekerjaanFilter" class="block w-full py-2.5 px-3 text-sm border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    <option value="">Semua Pekerjaan</option>
                    @foreach($refPekerjaans as $rp)
                        <option value="{{ $rp->id }}">{{ $rp->nama }}</option>
                    @endforeach
                </select>

                <select wire:model.live="genderFilter" class="block w-full py-2.5 px-3 text-sm border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>

        <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Nama & NIK</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Keluarga</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Wilayah</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Pekerjaan</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                        {{ substr($member->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('members.show', $member) }}" class="font-bold text-gray-900 hover:text-primary transition-colors">
                                            {{ $member->nama }}
                                        </a>
                                        <div class="text-xs text-gray-400 font-mono">{{ $member->nik ?? 'Tidak ada NIK' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-700">{{ $member->family->kepala_keluarga ?? '-' }}</div>
                                <div class="text-[10px] uppercase font-bold text-gray-400">{{ $member->refHubunganKeluarga->nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $member->family->refWilayah->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs">
                                {{ $member->refPekerjaan->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('members.edit', $member) }}" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                                        <button wire:click="delete('{{ $member->id }}')" wire:confirm="Hapus data {{ $member->nama }}?" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <p class="text-gray-400 font-medium">Data jemaat tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden">
            @forelse($members as $member)
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                    {{ substr($member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('members.show', $member) }}" class="font-bold text-gray-900 text-base block">{{ $member->nama }}</a>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $member->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                        {{ $member->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                {{ $member->family->refWilayah->nama ?? '-' }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Keluarga</span>
                                <span class="text-gray-700 font-medium">{{ $member->family->kepala_keluarga ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Pekerjaan</span>
                                <span class="text-gray-700">{{ $member->refPekerjaan->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-50">
                        <a href="{{ route('members.edit', $member) }}" class="flex-1 text-center text-xs font-bold text-gray-600 bg-gray-50 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-100 transition">Edit</a>
                        @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                            <button wire:click="delete('{{ $member->id }}')" wire:confirm="Hapus?" class="flex-1 text-center text-xs font-bold text-red-600 bg-red-50 py-2.5 rounded-xl border border-red-100 hover:bg-red-100 transition">Hapus</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl py-12 text-center border border-dashed border-gray-200 col-span-full">
                    <p class="text-gray-400 italic">Data tidak tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $members->links() }}
        </div>
    </div>
</div>