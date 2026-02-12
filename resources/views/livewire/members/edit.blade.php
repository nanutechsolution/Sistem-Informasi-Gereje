<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <a href="{{ route('families.edit', $member->family) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke KK {{ Str::limit($member->family->kepala_keluarga, 15) }}
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Edit Anggota Jemaat</h1>
            <p class="text-gray-500 mt-1">Memperbarui data untuk <span class="font-bold text-gray-800">{{ $member->nama }}</span>.</p>
        </div>

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: Biodata -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Biodata Diri</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input wire:model="nama" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- NIK -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                                <input wire:model="nik" type="number" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                                @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                                <select wire:model="jenis_kelamin" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                                <input wire:model="tempat_lahir" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input wire:model="tanggal_lahir" type="date" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                            </div>

                             <!-- No HP -->
                             <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP</label>
                                <input wire:model="no_hp" type="number" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Data Gerejawi -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Data Gerejawi & Sipil</h3>
                        
                        <div class="space-y-5">
                            <!-- Hubungan Keluarga (Master) -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Hubungan Keluarga</label>
                                <select wire:model="hubungan_keluarga_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                                    <option value="">-- Pilih --</option>
                                    @foreach($refHubungans as $rh)
                                        <option value="{{ $rh->id }}">{{ $rh->nama }}</option>
                                    @endforeach
                                </select>
                                @error('hubungan_keluarga_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Pekerjaan (Master) -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan</label>
                                <select wire:model="pekerjaan_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-primary/20 focus:border-primary">
                                    <option value="">-- Pilih --</option>
                                    @foreach($refPekerjaans as $rp)
                                        <option value="{{ $rp->id }}">{{ $rp->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pekerjaan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div>
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-primary hover:bg-blue-800 focus:outline-none transition-all disabled:opacity-70">
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>