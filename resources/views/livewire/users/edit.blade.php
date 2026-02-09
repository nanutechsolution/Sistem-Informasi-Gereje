<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Navigasi -->
        <div class="mb-8">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Edit Personil</h1>
            <p class="text-gray-500 mt-1">Perbarui data untuk <span class="font-bold text-gray-800">{{ $user->name }}</span>.</p>
        </div>

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: Form Input -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Kartu Identitas -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-primary p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </span>
                            Edit Identitas
                        </h3>

                        <div class="space-y-5">
                            <!-- Input Nama -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold text-sm">Aa</span>
                                    </div>
                                    <input wire:model="name" type="text" 
                                        class="pl-10 w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 transition-all" 
                                        placeholder="Contoh: Pdt. John Doe">
                                </div>
                                @error('name') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                            </div>

                            <!-- Input Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                    </div>
                                    <input wire:model="email" type="email" 
                                        class="pl-10 w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block p-3 transition-all" 
                                        placeholder="nama@gereja.id">
                                </div>
                                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Keamanan -->
                    <div class="bg-yellow-50 rounded-2xl shadow-sm border border-yellow-100 p-6 sm:p-8">
                         <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                            <span class="bg-white text-yellow-600 p-1.5 rounded-lg mr-3 border border-yellow-200 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            Ubah Password (Opsional)
                        </h3>

                        <div class="bg-white p-4 rounded-xl border border-yellow-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input wire:model="password" type="text" 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 block p-3 transition-all" 
                                placeholder="Kosongkan jika tidak ingin mengubah...">
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Isi hanya jika personil lupa password atau ingin menggantinya.
                            </p>
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Sidebar Role & Aksi -->
                <div class="space-y-6">
                    
                    <!-- Pilihan Role -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <label class="block text-sm font-bold text-gray-900 mb-4">Peran Pengguna (Role)</label>
                        
                        <div class="space-y-3">
                            @foreach(['operator' => 'Operator Multimedia', 'admin' => 'Administrator', 'pendeta' => 'Pendeta', 'majelis' => 'Majelis Jemaat', 'bendahara' => 'Bendahara', 'sekretaris' => 'Sekretaris'] as $val => $label)
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors hover:bg-gray-50 {{ $role === $val ? 'border-primary bg-blue-50/50 ring-1 ring-primary' : 'border-gray-200' }}">
                                <input type="radio" wire:model="role" value="{{ $val }}" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                                <span class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-4">
                        <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                        
                        <a href="{{ route('users.index') }}" class="mt-3 w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                            Batal
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>