<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <a href="{{ route('families.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Data Keluarga
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Registrasi KK Baru</h1>
            <p class="text-gray-500 mt-1">Tambahkan data Kepala Keluarga dan alamat domisili.</p>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: Data Utama -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-primary p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </span>
                            Informasi Kartu Keluarga
                        </h3>

                        <div class="space-y-5">
                            <!-- Nomor KK -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor KK Gereja / Pemerintah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    </div>
                                    <input wire:model="nomor_kk" type="number" 
                                        class="pl-10 w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 transition-all placeholder-gray-400 shadow-sm" 
                                        placeholder="Masukkan nomor identitas KK...">
                                </div>
                                @error('nomor_kk') <p class="mt-2 text-sm text-red-600 flex items-center">{{ $message }}</p> @enderror
                            </div>

                            <!-- Kepala Keluarga -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kepala Keluarga</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold text-sm">Aa</span>
                                    </div>
                                    <input wire:model="kepala_keluarga" type="text" 
                                        class="pl-10 w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 transition-all placeholder-gray-400 shadow-sm" 
                                        placeholder="Nama lengkap sesuai KTP...">
                                </div>
                                @error('kepala_keluarga') <p class="mt-2 text-sm text-red-600 flex items-center">{{ $message }}</p> @enderror
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Domisili</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <textarea wire:model="alamat" rows="3" 
                                        class="pl-10 w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 transition-all placeholder-gray-400 shadow-sm"
                                        placeholder="Nama jalan, RT/RW, kelurahan..."></textarea>
                                </div>
                                @error('alamat') <p class="mt-2 text-sm text-red-600 flex items-center">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Data Pelayanan -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-purple-100 text-purple-700 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                            </span>
                            Data Pelayanan
                        </h3>
                        
                        <!-- Pilihan Wilayah -->
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Wilayah / Sektor</label>
                            <div class="relative">
                                <select wire:model="wilayah_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 shadow-sm appearance-none">
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach($refWilayahs as $rw)
                                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('wilayah_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Keanggotaan</label>
                            <div class="relative">
                                <select wire:model="status" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 shadow-sm appearance-none">
                                    <option value="aktif">Aktif</option>
                                    <option value="pindah">Pindah Jemaat</option>
                                    <option value="keluar">Keluar / Atestasi</option>
                                    <option value="disiplin">Dalam Disiplin</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div>
                        <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all transform active:scale-[0.98] disabled:opacity-70">
                            
                            <span wire:loading.remove>Simpan Data KK</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>