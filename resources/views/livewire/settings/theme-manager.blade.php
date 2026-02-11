<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Pengaturan Tema Jemaat</h1>
            <p class="text-slate-500 font-medium mt-1">Kustomisasi identitas visual website tanpa ubah kode.</p>
        </div>

        <form wire:submit="save" class="space-y-8">
            <!-- WARNA BRANDING -->
            <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-200">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary rounded-full"></span> Warna Branding
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Warna Utama (Primary)</label>
                        <div class="flex gap-3">
                            <input type="color" wire:model.live="color_primary" class="h-14 w-14 rounded-2xl cursor-pointer border-none p-0">
                            <input type="text" wire:model.live="color_primary" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold text-slate-500 uppercase">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Warna Aksen (Accent)</label>
                        <div class="flex gap-3">
                            <input type="color" wire:model.live="color_accent" class="h-14 w-14 rounded-2xl cursor-pointer border-none p-0">
                            <input type="text" wire:model.live="color_accent" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold text-slate-500 uppercase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- UI & LAYOUT -->
            <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-200">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 bg-slate-900 rounded-full"></span> Interface & Sidebar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Background Website</label>
                        <div class="flex gap-3">
                            <input type="color" wire:model.live="color_background" class="h-14 w-14 rounded-2xl cursor-pointer border-none p-0">
                            <input type="text" wire:model.live="color_background" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Warna Sidebar Admin</label>
                        <div class="flex gap-3">
                            <input type="color" wire:model.live="color_sidebar" class="h-14 w-14 rounded-2xl cursor-pointer border-none p-0">
                            <input type="text" wire:model.live="color_sidebar" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-mono font-bold">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Tampilan Mode</label>
                        <select wire:model="appearance_mode" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 appearance-none cursor-pointer">
                            <option value="light">☀️ Light Mode (Terang)</option>
                            <option value="dark">🌙 Dark Mode (Gelap)</option>
                        </select>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-black text-slate-700">Sudut Kelengkungan (Radius)</label>
                        <select wire:model="ui_rounded" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 appearance-none cursor-pointer">
                            <option value="0.5rem">Minimalis (Small)</option>
                            <option value="1rem">Modern (Medium)</option>
                            <option value="1.5rem">Premium (Large)</option>
                            <option value="2.5rem">Ultra Round (Extra Large)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 pb-20">
                <button type="submit" class="px-12 py-5 bg-slate-900 text-white rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-primary hover:scale-105 transition-all active:scale-95">
                    Terapkan Perubahan Tema
                </button>
            </div>
        </form>
    </div>
</div>