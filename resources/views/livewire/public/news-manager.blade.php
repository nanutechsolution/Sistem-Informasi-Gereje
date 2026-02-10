<div class="py-6 sm:py-12 bg-slate-50 min-h-screen"
    x-data="{ showForm: @entangle('isModalOpen').live }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter leading-none italic uppercase">Manajemen Warta</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Publikasi Berita & Renungan Jemaat</p>
            </div>
            <button wire:click="create" class="px-8 py-4 bg-slate-900 text-white rounded-[24px] font-black text-xs shadow-2xl hover:scale-105 transition-all active:scale-95 uppercase tracking-widest">
                + TERBITKAN WARTA
            </button>
        </div>

        <!-- LIST WARTA -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden flex flex-col group">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $post->gambar_fitur) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[9px] font-black uppercase tracking-widest text-primary shadow-sm">
                            {{ $post->kategori }}
                        </span>
                    </div>
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-black text-slate-900 uppercase italic leading-tight mb-4">{{ $post->judul }}</h3>
                    <p class="text-slate-500 text-xs line-clamp-3 mb-6 flex-1 italic font-medium">
                        {{ Str::limit(strip_tags($post->konten), 120) }}
                    </p>
                    <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ $post->published_at->format('d M Y') }}
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $post->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $post->id }})" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 text-center bg-white rounded-[50px] border-2 border-dashed border-slate-200">
                <p class="font-black text-slate-300 uppercase tracking-widest text-xs italic">Belum ada warta yang diterbitkan.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-12">{{ $posts->links() }}</div>
    </div>

    <!-- MODAL FORM PRO -->
    <div x-show="showForm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showForm = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-[40px] p-10 shadow-2xl text-left animate-in zoom-in-95 duration-200">
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
                <h3 class="text-3xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center">Formulir Warta</h3>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Judul Warta / Artikel</label>
                            <input wire:model="judul" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold shadow-inner focus:ring-4 focus:ring-primary/5" placeholder="Input judul menarik...">
                            @error('judul') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kategori Konten</label>
                            <select wire:model="kategori" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-slate-700 cursor-pointer">
                                <option value="Berita">Berita Jemaat</option>
                                <option value="Renungan">Renungan Harian</option>
                                <option value="Pengumuman">Pengumuman Penting</option>
                                <option value="Diakonia">Laporan Diakonia</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Gambar Fitur (Maks 2MB)</label>
                            <input type="file" wire:model="image" class="w-full text-xs font-bold text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-primary file:text-white hover:file:bg-blue-800">
                            <div wire:loading wire:target="image" class="text-[9px] font-bold text-primary mt-2 uppercase animate-pulse">Mengunggah...</div>
                        </div>
                    </div>

                    @if ($image)
                    <div class="p-2 border-2 border-dashed border-slate-100 rounded-3xl">
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-2xl shadow-inner">
                    </div>
                    @elseif($existingImage)
                    <div class="p-2 border-2 border-dashed border-slate-100 rounded-3xl">
                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-48 object-cover rounded-2xl shadow-inner">
                    </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Isi Konten</label>
                        <textarea wire:model="konten" rows="8" class="w-full bg-slate-50 border-none rounded-[32px] p-6 font-medium text-slate-700 shadow-inner focus:ring-4 focus:ring-primary/5" placeholder="Tuliskan isi warta secara lengkap..."></textarea>
                        @error('konten') <span class="text-rose-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showForm = false" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-400 tracking-widest transition-all">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl hover:bg-primary transition-all tracking-widest">
                            <span wire:loading.remove>TERBITKAN SEKARANG</span>
                            <span wire:loading>MENYIMPAN...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>