<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <a href="{{ route('users.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">User Baru</h1>
            <p class="text-slate-500 mt-2 font-medium">Buat akun login untuk pengurus gereja.</p>
        </div>

        <form wire:submit="save" class="bg-white rounded-[40px] p-8 sm:p-12 shadow-xl border border-slate-100 relative overflow-visible">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary rounded-t-[40px]"></div>

            <div class="space-y-8">
                
                <div class="relative">
                    <label class="block text-[10px] font-black text-primary uppercase tracking-widest mb-2 ml-1">Tautkan ke Data Jemaat (Opsional)</label>
                    
                    @if($selectedMemberId)
                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex justify-between items-center animate-in zoom-in-95">
                            <div>
                                <span class="block font-bold text-blue-900">{{ $selectedMemberName }}</span>
                                <span class="text-[10px] text-blue-600 font-medium">Data terhubung ke sistem jemaat</span>
                            </div>
                            <button type="button" wire:click="clearMember" class="text-[10px] font-black uppercase text-rose-500 hover:underline">
                                Batalkan
                            </button>
                        </div>
                    @else
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="searchMember" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-900 focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300" placeholder="Cari nama jemaat...">
                            
                            @if(count($foundMembers) > 0)
                                <div class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden divide-y divide-slate-50 max-h-60 overflow-y-auto">
                                    @foreach($foundMembers as $m)
                                    <button type="button" 
                                        wire:click="selectMember({{ $m->id }}, '{{ $m->full_name }}', '{{ $m->email ?? '' }}')" 
                                        class="w-full text-left p-4 hover:bg-slate-50 transition-colors group">
                                        <span class="block font-bold text-sm text-slate-700 group-hover:text-primary">{{ $m->full_name }}</span>
                                        <span class="block text-[10px] text-slate-400">NIK: {{ $m->nik ?? '-' }}</span>
                                    </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all">
                        @error('name') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Login</label>
                        <input wire:model="email" type="email" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all">
                        @error('email') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Hak Akses (Role)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($roles as $r)
                        <label class="cursor-pointer group relative">
                            <input type="radio" wire:model="role" value="{{ $r->name }}" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white hover:border-slate-300 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center h-full flex items-center justify-center">
                                <span class="block font-black text-slate-600 peer-checked:text-primary uppercase text-[10px] tracking-wider">{{ $r->name }}</span>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-primary transition-opacity">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('role') <span class="text-rose-500 text-[10px] font-bold block mt-2">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                        <input wire:model="password" type="password" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all">
                        @error('password') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Password</label>
                        <input wire:model="password_confirmation" type="password" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold text-slate-900 focus:border-primary focus:ring-0 transition-all">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:shadow-2xl hover:scale-[1.01] transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">
                        <span wire:loading.remove>Simpan User Baru</span>
                        <span wire:loading class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>