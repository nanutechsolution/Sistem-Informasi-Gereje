<div>
    <!-- HERO -->
    <section class="relative pt-40 pb-24 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-5xl md:text-8xl font-serif italic mb-6 tracking-tighter animate-in fade-in slide-in-from-bottom-8 duration-700">
                Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200">Kami.</span>
            </h1>
            <p class="text-slate-400 text-sm md:text-base font-medium max-w-xl mx-auto leading-relaxed animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                Kami siap mendengar, melayani, dan mendoakan Anda. Silakan kirimkan pesan atau kunjungi sekretariat kami.
            </p>
        </div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-primary/20 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
    </section>

    <section class="py-20 px-6 lg:px-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                
                <!-- INFORMASI KONTAK -->
                <div class="space-y-10">
                    <div class="bg-white p-10 rounded-[3rem] shadow-xl border border-slate-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-32 h-32 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.54 5.57-5 7.91-2.46-2.34-5-5.03-5-7.91z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black italic text-slate-900 mb-6 uppercase tracking-tighter">Sekretariat Gereja</h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm mb-8">
                            {{ $setting->alamat }}
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                                <span class="font-bold text-slate-700 text-sm">{{ $setting->email }}</span>
                            </div>
                            @if($setting->telepon)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                                <span class="font-bold text-slate-700 text-sm">{{ $setting->telepon }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Embed Map (Static Image / Placeholder for Speed) -->
                    <div class="bg-slate-200 rounded-[3rem] h-64 overflow-hidden relative shadow-inner">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover opacity-80" alt="Map Location">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <a href="https://maps.google.com/?q={{ urlencode($setting->alamat) }}" target="_blank" class="px-6 py-3 bg-white/90 backdrop-blur text-slate-900 rounded-full font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl">
                                Buka Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FORMULIR PESAN -->
                <div class="bg-white p-10 lg:p-14 rounded-[3rem] shadow-2xl border border-slate-100">
                    <h3 class="text-2xl font-black italic text-slate-900 mb-2 uppercase tracking-tighter">Kirim Pesan</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-10">Pertanyaan, Permohonan Doa, atau Saran</p>

                    @if($successMessage)
                        <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl text-center mb-8 animate-in fade-in zoom-in-95">
                            <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h4 class="font-black text-emerald-800 text-lg uppercase italic">Pesan Terkirim!</h4>
                            <p class="text-xs text-emerald-600 mt-1 font-medium">Terima kasih telah menghubungi kami.</p>
                            <button wire:click="$set('successMessage', false)" class="mt-4 text-[10px] font-bold text-emerald-700 underline uppercase">Kirim Pesan Lain</button>
                        </div>
                    @else
                        <form wire:submit="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Nama Lengkap</label>
                                    <input wire:model="nama" type="text" class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300" placeholder="Nama Anda...">
                                    @error('nama') <span class="text-rose-500 text-[9px] font-bold ml-4 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Email / Kontak</label>
                                    <input wire:model="email" type="email" class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300" placeholder="Email aktif...">
                                    @error('email') <span class="text-rose-500 text-[9px] font-bold ml-4 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Subjek / Topik</label>
                                <input wire:model="subjek" type="text" class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300" placeholder="Hal yang ingin disampaikan...">
                                @error('subjek') <span class="text-rose-500 text-[9px] font-bold ml-4 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-4">Isi Pesan</label>
                                <textarea wire:model="pesan" rows="5" class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-5 font-medium text-sm focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-slate-300 resize-none" placeholder="Tulis pesan Anda di sini..."></textarea>
                                @error('pesan') <span class="text-rose-500 text-[9px] font-bold ml-4 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4 text-right">
                                <button type="submit" wire:loading.attr="disabled" class="px-10 py-5 bg-primary text-white rounded-full font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-primary/30 hover:scale-105 transition-all active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove>Kirim Pesan</span>
                                    <span wire:loading>Mengirim...</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>