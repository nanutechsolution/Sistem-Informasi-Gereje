<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Galeri Khotbah</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-red-500 pl-4 uppercase text-[10px] tracking-widest">Manajemen Video YouTube & Rekaman Ibadah</p>
            </div>
            <button wire:click="create" class="px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all active:scale-95 uppercase tracking-widest">
                + VIDEO BARU
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($sermons as $video)
            <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden group">
                <!-- Thumbnail dengan Tombol Play -->
                <div class="relative h-48 bg-black">
                    <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-60 transition-opacity">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $video->tanggal->format('d M Y') }}</p>
                    <h3 class="text-lg font-black text-slate-900 leading-tight mb-2 line-clamp-2">{{ $video->judul }}</h3>
                    <p class="text-xs text-primary font-bold uppercase mb-4">{{ $video->pengkhotbah }}</p>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                        <a href="{{ $video->youtube_url }}" target="_blank" class="text-[10px] font-bold text-red-600 hover:underline flex items-center gap-1">
                            Lihat di YouTube <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <button wire:click="delete({{ $video->id }})" class="p-2 text-slate-300 hover:text-rose-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-slate-300 italic font-bold">Belum ada video khotbah.</div>
            @endforelse
        </div>
    </div>

    <!-- MODAL INPUT -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95">
                <div class="absolute top-0 left-0 w-full h-2 bg-red-600"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center">Upload Khotbah</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Link YouTube</label>
                        <input wire:model="youtube_url" type="url" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner" placeholder="https://youtube.com/watch?v=...">
                        @error('youtube_url') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Judul Khotbah / Tema</label>
                        <input wire:model="judul" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pengkhotbah</label>
                            <input wire:model="pengkhotbah" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner" placeholder="Pdt. ...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Ringkasan (Opsional)</label>
                        <textarea wire:model="ringkasan" rows="3" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold shadow-inner"></textarea>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-5 bg-slate-100 rounded-[28px] font-black text-[10px] uppercase tracking-widest text-slate-400">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-red-600 text-white rounded-[28px] font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-red-700 transition">Publish Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>