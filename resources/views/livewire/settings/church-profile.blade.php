<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                Identitas & Tampilan
            </h1>
            <p class="text-slate-500 mt-2 font-medium">
                Pengaturan profil, skema warna, dan preferensi antarmuka publik.
            </p>
        </div>

        <form wire:submit.prevent="save" class="space-y-8">

            <!-- ================= BRANDING & INFO UTAMA ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-slate-200"></span> Branding & Identitas
                </h3>

                <div class="flex flex-col md:flex-row items-center gap-8 mb-10">
                    <div class="relative group">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="h-32 w-32 rounded-[2.5rem] object-cover shadow-2xl border-4 border-white">
                        @elseif($existingLogo)
                            <img src="{{ asset('storage/'.$existingLogo) }}" class="h-32 w-32 rounded-[2.5rem] object-cover shadow-2xl border-4 border-white">
                        @else
                            <div class="h-32 w-32 rounded-[2.5rem] bg-slate-100 flex flex-col items-center justify-center text-slate-300 border-2 border-dashed border-slate-200">
                                <i class="fas fa-image text-2xl mb-1"></i>
                                <span class="text-[8px] font-black uppercase">No Logo</span>
                            </div>
                        @endif
                        <div wire:loading wire:target="logo" class="absolute inset-0 bg-white/80 backdrop-blur-sm rounded-[2.5rem] flex items-center justify-center">
                            <i class="fas fa-spinner animate-spin text-primary"></i>
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Upload Logo (PNG/JPG max 1MB)</label>
                            <input type="file" wire:model="logo" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-primary transition-all cursor-pointer">
                            @error('logo') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 text-primary">Nama Gereja</label>
                        <input wire:model="nama_gereja" type="text" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-4 transition-all">
                        @error('nama_gereja') <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Jemaat</label>
                        <input wire:model="nama_jemaat" type="text" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-4 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Deskripsi Singkat (Slogan)</label>
                        <textarea wire:model="deskripsi_singkat" rows="2" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-medium p-4 transition-all"></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= APPEARANCE (NEW) ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-accent to-primary opacity-50"></div>
                <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-slate-200"></span> Tema & Warna Sistem
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Color Pickers -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black text-slate-400 uppercase">Warna Utama</label>
                                <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <input wire:model="color_primary" type="color" class="h-8 w-8 rounded-lg cursor-pointer border-none bg-transparent">
                                    <input wire:model="color_primary" type="text" class="flex-1 bg-transparent border-none p-0 text-[10px] font-mono font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black text-slate-400 uppercase">Warna Aksen</label>
                                <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <input wire:model="color_accent" type="color" class="h-8 w-8 rounded-lg cursor-pointer border-none bg-transparent">
                                    <input wire:model="color_accent" type="text" class="flex-1 bg-transparent border-none p-0 text-[10px] font-mono font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black text-slate-400 uppercase">Background</label>
                                <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <input wire:model="color_background" type="color" class="h-8 w-8 rounded-lg cursor-pointer border-none bg-transparent">
                                    <input wire:model="color_background" type="text" class="flex-1 bg-transparent border-none p-0 text-[10px] font-mono font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black text-slate-400 uppercase">Sidebar</label>
                                <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <input wire:model="color_sidebar" type="color" class="h-8 w-8 rounded-lg cursor-pointer border-none bg-transparent">
                                    <input wire:model="color_sidebar" type="text" class="flex-1 bg-transparent border-none p-0 text-[10px] font-mono font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UI Settings -->
                    <div class="space-y-6 bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase mb-3 ml-1">Mode Tampilan</label>
                            <div class="flex gap-2 p-1 bg-slate-100 rounded-2xl w-fit">
                                <button type="button" wire:click="$set('appearance_mode', 'light')" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all {{ $appearance_mode == 'light' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600' }}">Light</button>
                                <button type="button" wire:click="$set('appearance_mode', 'dark')" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all {{ $appearance_mode == 'dark' ? 'bg-slate-800 shadow-sm text-white' : 'text-slate-400 hover:text-slate-600' }}">Dark</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase mb-3 ml-1">Gaya Sudut (Rounded)</label>
                            <select wire:model="ui_rounded" class="w-full bg-white border-slate-200 rounded-xl text-xs font-bold p-3 focus:ring-primary/20">
                                <option value="none">Siku (None)</option>
                                <option value="md">Medium</option>
                                <option value="lg">Large</option>
                                <option value="xl">Extra Large</option>
                                <option value="2xl">Double Extra Large</option>
                                <option value="3xl">Triple Extra Large</option>
                                <option value="full">Pills (Full)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= KONTAK & SOSIAL ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-slate-200"></span> Kontak & Hubungan Jemaat
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Alamat Lengkap Kantor Sekretariat</label>
                        <input wire:model="alamat" type="text" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-4 transition-all">
                        @error('alamat') <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Email Resmi</label>
                        <input wire:model="email" type="email" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-4 transition-all">
                        @error('email') <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">No. Telepon / WhatsApp</label>
                        <input wire:model="telepon" type="text" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-4 transition-all">
                    </div>

                    <div class="pt-4 md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 italic opacity-80 text-primary">
                            <div class="flex flex-col gap-2">
                                <label class="text-[9px] font-black uppercase">Facebook</label>
                                <input wire:model="facebook" type="text" placeholder="https://..." class="w-full bg-blue-50/50 border-none rounded-xl text-xs p-3">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[9px] font-black uppercase">Instagram</label>
                                <input wire:model="instagram" type="text" placeholder="@username" class="w-full bg-rose-50/50 border-none rounded-xl text-xs p-3">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[9px] font-black uppercase">YouTube</label>
                                <input wire:model="youtube" type="text" placeholder="Channel Link" class="w-full bg-red-50/50 border-none rounded-xl text-xs p-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= VISI, MISI & SEJARAH ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-slate-200"></span> Visi, Misi & Sejarah
                </h3>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Visi Gereja</label>
                        <textarea wire:model="visi" rows="2" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-bold p-5 transition-all text-lg italic"></textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Poin-poin Misi Pelayanan</label>
                            <button type="button" wire:click="addMisi" class="text-[10px] font-black text-primary uppercase bg-primary/5 px-4 py-2 rounded-full hover:bg-primary hover:text-white transition-all">
                                + Tambah Misi
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach($misi as $index => $m)
                                <div class="flex gap-3 animate-in fade-in slide-in-from-left-2 duration-300">
                                    <div class="flex-shrink-0 h-12 w-12 bg-slate-100 rounded-xl flex items-center justify-center text-xs font-black text-slate-400">{{ $index + 1 }}</div>
                                    <input type="text" wire:model="misi.{{ $index }}" class="flex-1 bg-slate-50 border-none rounded-xl font-medium p-3 focus:ring-primary/10" placeholder="Tulis satu poin misi...">
                                    <button type="button" wire:click="removeMisi({{ $index }})" class="h-12 w-12 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Narasi Sejarah Singkat</label>
                        <textarea wire:model="sejarah_singkat" rows="6" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-primary/20 focus:bg-white focus:ring-0 rounded-2xl font-medium p-5 transition-all leading-relaxed"></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= SUBMIT BAR (STICKY) ================= -->
            <div class="sticky bottom-8 z-50 flex justify-end">
                <div class="bg-white/80 backdrop-blur-md p-2 rounded-full border border-white shadow-2xl">
                    <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="px-12 py-4 bg-slate-900 text-white rounded-full font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-primary transition-all flex items-center gap-3 active:scale-95 disabled:opacity-50">
                        
                        <span  wire:loading.remove wire:target="save" class="flex items-center gap-3">
                            Simpan Perubahan <i class="fas fa-check-circle text-xs text-green-400"></i>
                        </span>
                        
                        <span wire:loading wire:target="save" class="flex items-center gap-3">
                            <i class="fas fa-circle-notch animate-spin"></i> Memproses...
                        </span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>