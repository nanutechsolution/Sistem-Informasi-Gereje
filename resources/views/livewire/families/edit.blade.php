<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <a href="{{ route('families.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Data Keluarga
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Edit Kartu Keluarga</h1>
                    <p class="text-gray-500 mt-1">Kepala Keluarga: <span class="font-bold text-gray-800">{{ $family->kepala_keluarga }}</span> (No. KK: {{ $family->nomor_kk }})</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN 1: FORM EDIT DATA KELUARGA -->
        <form wire:submit="update" class="mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Data Utama -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-primary p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </span>
                            Data Domisili
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kepala Keluarga</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold text-sm">Aa</span>
                                    </div>
                                    <input wire:model="kepala_keluarga" type="text" class="pl-10 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all">
                                </div>
                                @error('kepala_keluarga') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor KK</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                    </div>
                                    <input wire:model="nomor_kk" type="number" class="pl-10 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all">
                                </div>
                                @error('nomor_kk') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Wilayah</label>
                                <div class="relative">
                                    <!-- Menggunakan wilayah_id dan data dari Tabel Master -->
                                    <select wire:model="wilayah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm appearance-none">
                                        <option value="">-- Pilih Wilayah --</option>
                                        @foreach($refWilayahs as $rw)
                                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('wilayah_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <textarea wire:model="alamat" rows="2" class="pl-10 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"></textarea>
                                </div>
                                @error('alamat') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Status & Save -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <label class="block text-sm font-bold text-gray-900 mb-4">Status Keanggotaan</label>
                        <div class="relative mb-6">
                            <select wire:model="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm appearance-none">
                                <option value="aktif">Aktif</option>
                                <option value="pindah">Pindah</option>
                                <option value="keluar">Keluar</option>
                                <option value="disiplin">Disiplin</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-3 px-4 rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-primary hover:bg-blue-800 transition-all transform active:scale-[0.98] disabled:opacity-70">
                            <span wire:loading.remove>Simpan Perubahan KK</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- BAGIAN 2: DAFTAR ANGGOTA KELUARGA -->
        <div class="border-t-4 border-gray-200 pt-8 mt-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                        Daftar Anggota Keluarga
                        <span class="px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800 font-bold">{{ $family->members->count() }} Jiwa</span>
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola data individu dalam keluarga ini.</p>
                </div>
                <a href="{{ route('members.create', $family) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-green-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Anggota
                </a>
            </div>

            <!-- Tampilan Desktop (Tabel Modern) -->
            <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Nama & NIK</th>
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Hubungan</th>
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">L/P</th>
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Status Gerejawi</th>
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($family->members as $member)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-xs font-bold text-primary border border-blue-100">
                                        {{ substr($member->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $member->nama }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $member->nik ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $member->refHubunganKeluarga->nama ?? "-" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $member->jenis_kelamin == 'L' ? 'text-blue-700 bg-blue-50' : 'text-pink-700 bg-pink-50' }}">
                                    {{ $member->jenis_kelamin }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <span class="text-[10px] px-2 py-0.5 rounded border {{ $member->status_baptis == 'Sudah' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-200' }}">Baptis</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded border {{ $member->status_sidi == 'Sudah' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-200' }}">Sidi</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('members.edit', $member) }}" class="text-gray-400 hover:text-primary font-bold text-xs transition-colors flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                                    <button wire:click="deleteMember({{ $member->id }})" wire:confirm="Hapus {{ $member->nama }}?" class="text-gray-400 hover:text-red-600 font-bold text-xs transition-colors flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada anggota keluarga yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Mobile (Card Stack Modern) -->
            <div class="sm:hidden grid grid-cols-1 gap-4">
                @forelse($family->members as $member)
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-sm font-bold text-primary border border-blue-100">
                                {{ substr($member->nama, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $member->nama }}</h4>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide bg-gray-100 px-1.5 py-0.5 rounded">{{ $member->hubungan_keluarga }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $member->jenis_kelamin == 'L' ? 'text-blue-700 bg-blue-50' : 'text-pink-700 bg-pink-50' }}">
                            {{ $member->jenis_kelamin }}
                        </span>
                    </div>

                    <div class="flex gap-2 mb-4 border-t border-b border-gray-50 py-3">
                        <div class="flex-1 text-center border-r border-gray-50">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">Baptis</span>
                            <span class="text-xs font-bold {{ $member->status_baptis == 'Sudah' ? 'text-green-600' : 'text-gray-400' }}">{{ $member->status_baptis }}</span>
                        </div>
                        <div class="flex-1 text-center">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">Sidi</span>
                            <span class="text-xs font-bold {{ $member->status_sidi == 'Sudah' ? 'text-green-600' : 'text-gray-400' }}">{{ $member->status_sidi }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('members.edit', $member) }}" class="flex-1 py-2 text-center text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-100">Edit</a>

                        @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                        <button
                            wire:click="deleteMember({{ $member->id }})"
                            wire:confirm="Hapus anggota ini?"
                            class="flex-1 py-2 text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-50">
                            Hapus
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-sm">Belum ada anggota keluarga.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>