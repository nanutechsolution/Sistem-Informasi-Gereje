<div class="bg-slate-50 min-h-screen">
    <!-- HERO SECTION -->
    <section class="relative pt-48 pb-32 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <img src="https://images.unsplash.com/photo-1512403754473-27835f7b9984?auto=format&fit=crop&q=80&w=1920" 
             class="absolute inset-0 w-full h-full object-cover opacity-30 scale-105" 
             alt="Background">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 via-slate-900 to-slate-50"></div>
        
        <div class="relative z-10 text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8">
                <span class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Pelayanan Doa & Konseling</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-none">
                "Marilah kepada-Ku, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-indigo-300">semua yang letih lesu."</span>
            </h1>
            
            <p class="text-slate-400 text-sm md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                Pintu kami selalu terbuka. Bagikan pokok doa Anda, dan biarkan kami berdiri bersama Anda di dalam iman.
            </p>
        </div>
    </section>

    <!-- FORM SECTION -->
    <section class="relative -mt-24 z-20 pb-32 px-4">
        <div class="max-w-3xl mx-auto">
            @if($successSent)
                <div class="bg-white rounded-[3rem] p-12 md:p-16 shadow-2xl text-center border border-emerald-100 transition-all">
                    <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 italic mb-4 tracking-tight">Terima Kasih, Doa Anda Telah Kami Terima</h3>
                    <p class="text-slate-500 mb-10 leading-relaxed max-w-sm mx-auto font-medium">
                        Tim doa kami akan segera membawa permohonan Anda dalam persekutuan doa. Kiranya kasih dan damai sejahtera Kristus senantiasa menyertai setiap langkah Anda.
                    </p>
                    <button wire:click="$set('successSent', false)" class="px-10 py-4 bg-primary text-white rounded-full font-black text-[10px] uppercase tracking-widest hover:shadow-2xl transition-all">
                        Kirim Pokok Doa Lainnya
                    </button>
                </div>
            @else
                <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-2xl shadow-slate-200 border border-white relative overflow-hidden">
                    <form wire:submit="save" class="space-y-10 relative z-10">
                        
                        <!-- KATEGORI -->
                        <div x-data="{ active: @entangle('kategori') }">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 ml-2">Pilih Jenis Pokok Doa</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach(['Pergumulan', 'Kesehatan', 'Syukur', 'Keluarga'] as $kat)
                                <button type="button" @click="active = '{{ $kat }}'" 
                                        class="px-4 py-4 rounded-2xl border-2 transition-all text-center"
                                        :class="active == '{{ $kat }}' ? 'border-primary bg-primary/5 text-primary' : 'border-slate-50 bg-slate-50 text-slate-400 hover:border-slate-200'">
                                    <span class="text-[10px] font-black uppercase tracking-wider">{{ $kat }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- POKOK DOA -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">
                                Apa yang ingin Anda doakan? <span class="text-rose-500 ml-1 lowercase font-bold italic">(Wajib diisi agar kami dapat ikut mendoakan)</span>
                            </label>
                            <textarea wire:model="pokok_doa" rows="6" 
                                      class="w-full bg-slate-50 border-none rounded-[2rem] p-8 font-medium text-slate-700 focus:ring-4 focus:ring-primary/5 placeholder:text-slate-300 transition-all resize-none shadow-inner" 
                                      placeholder="Silakan ceritakan kerinduan atau beban hati Anda di sini..."></textarea>
                            @error('pokok_doa') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-4 block uppercase tracking-widest">Mohon maaf, bagian ini wajib diisi ya agar kami tahu pokok doanya.</span> @enderror
                        </div>

                        <!-- IDENTITAS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Nama Anda (Boleh dikosongkan)</label>
                                <input wire:model="nama_pemohon" type="text" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-primary/5" placeholder="Misal: Hamba Tuhan">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">WhatsApp (Hanya jika ingin dihubungi)</label>
                                <input wire:model="kontak" type="tel" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-primary/5" placeholder="08...">
                            </div>
                        </div>

                        <!-- PRIVASI TOGGLE -->
                        <div class="space-y-4 pt-4">
                            <label class="flex items-center justify-between p-5 rounded-[1.5rem] border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group" x-data="{ private: @entangle('is_private') }">
                                <div class="flex items-center gap-5">
                                    <div class="p-3 rounded-xl transition-colors" :class="private ? 'bg-primary text-white' : 'bg-slate-100 text-slate-400'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-black text-slate-800 uppercase tracking-tight">Rahasiakan Pokok Doa Ini</span>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-widest">Hanya Tim Doa & Pendeta yang akan membacanya.</span>
                                    </div>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_private" class="sr-only peer">
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                            </label>

                            <label class="flex items-center justify-between p-5 rounded-[1.5rem] border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group" x-data="{ counsel: @entangle('butuh_konseling') }">
                                <div class="flex items-center gap-5">
                                    <div class="p-3 rounded-xl transition-colors" :class="counsel ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-black text-slate-800 uppercase tracking-tight">Saya Ingin Dihubungi Pendeta</span>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-widest">Silakan centang jika Anda memerlukan konseling pribadi.</span>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="butuh_konseling" class="w-6 h-6 rounded-md border-slate-200 text-primary focus:ring-primary">
                            </label>
                        </div>

                        <!-- SUBMIT -->
                        <div class="pt-6">
                            <button type="submit" wire:target="save" wire:loading.attr="disabled" class="w-full py-6 bg-slate-900 text-white rounded-[2.5rem] font-black uppercase text-xs tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-4">
                                <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <svg wire:loading wire:target="save" class="animate-spin w-5 h-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span>Kirim Pokok Doa Anda</span>
                            </button>
                            <p class="text-center text-[10px] text-slate-300 mt-6 font-bold uppercase tracking-widest italic">Segala informasi yang Anda bagikan akan dijaga kerahasiaannya dengan penuh kasih.</p>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>
</div>