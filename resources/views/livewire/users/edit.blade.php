<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <a href="{{ route('users.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase italic">Edit Pengguna</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-amber-400 pl-4">Perbarui informasi akun dan hak akses login.</p>
            </div>
            
            <div class="h-16 w-16 rounded-3xl bg-slate-900 text-white flex items-center justify-center font-black text-2xl shadow-xl">
                {{ substr($name, 0, 1) }}
            </div>
        </div>

        <form wire:submit="save" class="bg-white rounded-[40px] p-8 sm:p-12 shadow-xl border border-slate-100 relative overflow-hidden">
            <!-- Dekorasi Atas -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 to-primary"></div>

            <div class="space-y-8">
                
                <!-- Identitas Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('name') <span class="text-rose-500 text-[10px] font-bold block mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Login</label>
                        <input wire:model="email" type="email" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('email') <span class="text-rose-500 text-[10px] font-bold block mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Hak Akses (Role)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($roles as $r)
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model="role" value="{{ $r->name }}" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-50 bg-slate-50 hover:border-primary/20 peer-checked:border-primary peer-checked:bg-blue-50 peer-checked:text-primary transition-all text-center">
                                <span class="block font-black text-slate-400 peer-checked:text-primary uppercase text-[10px] tracking-widest">{{ $r->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('role') <span class="text-rose-500 text-[10px] font-bold block mt-3 uppercase">{{ $message }}</span> @enderror
                </div>

                <hr class="border-slate-100">

                <!-- Keamanan -->
                <div class="bg-amber-50 rounded-3xl p-6 border border-amber-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-white rounded-xl shadow-sm text-amber-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h4 class="text-sm font-black text-amber-900 uppercase italic">Ganti Password</h4>
                    </div>
                    
                    <p class="text-[10px] text-amber-600 font-bold mb-4 uppercase tracking-wide">Isi jika ingin mengganti password, jika tidak biarkan kosong.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input wire:model="password" type="password" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-amber-200 shadow-sm" placeholder="Password baru...">
                    </div>
                    @error('password') <span class="text-rose-500 text-[10px] font-bold block mt-2 ml-1 uppercase">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-[28px] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-500/40 hover:scale-[1.02] transition-all active:scale-95 disabled:opacity-70">
                        <span wire:loading.remove italic>Update Data Pengguna</span>
                        <span wire:loading>Memproses Perubahan...</span>
                    </button>
                    <a href="{{ route('users.index') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[28px] font-black text-xs text-center uppercase tracking-[0.2em] hover:bg-slate-200 transition-all italic">Batal</a>
                </div>

            </div>
        </form>
    </div>
</div>