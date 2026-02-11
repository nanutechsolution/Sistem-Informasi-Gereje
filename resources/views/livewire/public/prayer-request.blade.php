<div>
    <!-- HERO SECTION -->
    <section class="relative pt-40 pb-24 px-6 lg:px-10 bg-slate-900 text-white overflow-hidden">
        <!-- Background Image -->
        <img src="https://images.unsplash.com/photo-1512403754473-27835f7b9984?auto=format&fit=crop&q=80&w=1920" 
             class="absolute inset-0 w-full h-full object-cover opacity-40 scale-105" 
             alt="Prayer Hands">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-slate-900/80"></div>
        
        <div class="relative z-10 text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-violet-500/20 backdrop-blur-md rounded-full border border-violet-500/30 mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                <span class="text-violet-200 text-[10px] font-black uppercase tracking-[0.2em]">Pelayanan Doa 24 Jam</span>
            </div>
            
            <h1 class="text-5xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-[0.9] animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                "Marilah kepada-Ku, <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-200 to-white">semua yang letih lesu."</span>
            </h1>
            
            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto leading-relaxed font-medium animate-in fade-in slide-in-from-bottom-12 duration-700 delay-200">
                Tidak ada beban yang terlalu berat untuk ditanggung bersama. Tim Doa dan Pendeta kami siap mendukung pergumulan Anda dalam doa.
            </p>
        </div>
    </section>

    <!-- FORM SECTION -->
    <section class="relative -mt-20 z-20 pb-32 px-4">
        <div class="max-w-3xl mx-auto">
            
            @if($successSent)
                <!-- SUCCESS STATE -->
                <div class="bg-white rounded-[3rem] p-12 md:p-16 shadow-2xl text-center border border-emerald-100 animate-in zoom-in duration-300">
                    <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 italic mb-4 tracking-tight">Doa Anda Telah Diterima</h3>
                    <p class="text-slate-500 mb-10 leading-relaxed max-w-md mx-auto">
                        Tim doa kami akan segera membawa pokok doa ini ke hadapan Tuhan. Kiranya damai sejahtera Kristus menyertai Anda.
                    </p>
                    <button wire:click="$set('successSent', false)" class="px-10 py-4 bg-slate-900 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl">
                        Kirim Doa Lain
                    </button>
                </div>
            @else
                <!-- FORM STATE -->
                <div class="bg-white/90 backdrop-blur-xl rounded-[3rem] p-8 md:p-12 shadow-2xl border border-white/50 relative overflow-hidden">
                    <!-- Decorative Gradient -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

                    <form wire:submit="save" class="space-y-8 relative z-10">
                        
                        <!-- Pilihan Kategori -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-2">Tentang Apa Doa Ini?</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['Pergumulan', 'Sakit Penyakit', 'Ucapan Syukur', 'Keluarga'] as $kat)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="kategori" value="{{ $kat }}" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-center peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-700 transition-all hover:border-violet-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wide">{{ $kat }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Isi Doa -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Pokok Doa</label>
                            <textarea wire:model="pokok_doa" rows="6" class="w-full bg-slate-50 border-none rounded-[2rem] p-6 font-medium text-slate-700 focus:ring-4 focus:ring-violet-500/10 placeholder:text-slate-400 transition-all resize-none shadow-inner" placeholder="Ceritakan apa yang sedang Anda alami..."></textarea>
                            @error('pokok_doa') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>

                        <!-- Identitas (Opsional) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Nama (Boleh Kosong)</label>
                                <input wire:model="nama_pemohon" type="text" class="w-full bg-slate-50 border-none rounded-[1.5rem] px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-violet-500/10" placeholder="Hamba Tuhan">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">No. WA (Opsional)</label>
                                <input wire:model="kontak" type="tel" class="w-full bg-slate-50 border-none rounded-[1.5rem] px-6 py-4 font-bold text-sm focus:ring-4 focus:ring-violet-500/10" placeholder="08...">
                            </div>
                        </div>

                        <hr class="border-slate-100 my-6">

                        <!-- Opsi Privasi & Konseling -->
                        <div class="space-y-4">
                            <!-- Toggle Privasi -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors cursor-pointer group">
                                <div class="flex items-center gap-4">
                                    <div class="p-2 bg-slate-100 rounded-xl text-slate-400 group-hover:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-800">Rahasiakan Doa Ini</span>
                                        <span class="block text-[10px] text-slate-400 font-medium">Hanya Pendeta & Tim Doa yang boleh tahu (Tidak dibacakan di Warta).</span>
                                    </div>
                                </div>
                                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-slate-200 rounded-full" :class="{'bg-violet-500': @entangle('is_private')}">
                                    <input type="checkbox" wire:model.live="is_private" class="absolute block w-6 h-6 bg-white border-4 border-slate-200 rounded-full appearance-none cursor-pointer transition-transform top-0 left-0" :class="{'translate-x-6 border-violet-500': @entangle('is_private')}">
                                </div>
                            </label>

                            <!-- Toggle Konseling -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors cursor-pointer group">
                                <div class="flex items-center gap-4">
                                    <div class="p-2 bg-slate-100 rounded-xl text-slate-400 group-hover:text-emerald-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-800">Butuh Konseling/Kunjungan?</span>
                                        <span class="block text-[10px] text-slate-400 font-medium">Pendeta akan menghubungi nomor Anda secara pribadi.</span>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="butuh_konseling" class="w-6 h-6 rounded-md border-slate-300 text-violet-600 focus:ring-violet-500">
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="pt-6">
                            <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-violet-600 text-white rounded-[2rem] font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-violet-500/30 hover:bg-violet-700 hover:scale-[1.02] transition-all active:scale-95 disabled:opacity-70 disabled:scale-100 flex items-center justify-center gap-3">
                                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Kirim Permohonan Doa
                            </button>
                            <p class="text-center text-[10px] text-slate-400 mt-4 font-bold uppercase tracking-wider">Kerahasiaan data Anda terjamin.</p>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>
</div>