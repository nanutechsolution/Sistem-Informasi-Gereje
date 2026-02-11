<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                Identitas Gereja
            </h1>
            <p class="text-slate-500 mt-2 font-medium">
                Pengaturan profil, branding, dan informasi publik.
            </p>
        </div>

        {{-- NOTIFICATION --}}
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8">

            <!-- ================= BRANDING ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">
                    Branding & Logo
                </h3>

                <div class="flex items-center gap-8 mb-8">
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-24 w-24 rounded-2xl object-cover shadow-lg">
                    @elseif($existingLogo)
                        <img src="{{ asset('storage/'.$existingLogo) }}" class="h-24 w-24 rounded-2xl object-cover shadow-lg">
                    @else
                        <div class="h-24 w-24 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300 font-black text-xs">
                            NO LOGO
                        </div>
                    @endif

                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Upload Logo Baru
                        </label>
                        <input type="file" wire:model="logo"
                            class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-xs file:font-black
                            file:bg-slate-900 file:text-white
                            hover:file:bg-blue-800 transition-all">
                        @error('logo') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Nama Gereja
                        </label>
                        <input wire:model="nama_gereja" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                        @error('nama_gereja') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Nama Jemaat
                        </label>
                        <input wire:model="nama_jemaat" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <div class="mt-6">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                        Deskripsi Singkat
                    </label>
                    <textarea wire:model="deskripsi_singkat" rows="3"
                        class="w-full bg-slate-50 border-none rounded-2xl font-medium p-4"></textarea>
                </div>

                <!-- SEJARAH -->
                <div class="mt-6">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                        Sejarah Gereja
                    </label>
                    <textarea wire:model="sejarah_singkat" rows="5"
                        class="w-full bg-slate-50 border-none rounded-2xl font-medium p-4"></textarea>
                </div>

                <!-- WARNA -->
                <div class="grid grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Warna Utama
                        </label>
                        <div class="flex gap-2">
                            <input wire:model="warna_utama" type="color"
                                class="h-10 w-10 rounded cursor-pointer border-none p-0 bg-transparent">
                            <input wire:model="warna_utama" type="text"
                                class="flex-1 bg-slate-50 border-none rounded-xl font-mono text-xs p-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Warna Aksen
                        </label>
                        <div class="flex gap-2">
                            <input wire:model="warna_aksen" type="color"
                                class="h-10 w-10 rounded cursor-pointer border-none p-0 bg-transparent">
                            <input wire:model="warna_aksen" type="text"
                                class="flex-1 bg-slate-50 border-none rounded-xl font-mono text-xs p-3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= KONTAK ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">
                    Kontak & Media Sosial
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Alamat Lengkap
                        </label>
                        <input wire:model="alamat" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                        @error('alamat') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Email
                        </label>
                        <input wire:model="email" type="email"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                        @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Telepon / WA
                        </label>
                        <input wire:model="telepon" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Facebook
                        </label>
                        <input wire:model="facebook" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            Instagram
                        </label>
                        <input wire:model="instagram" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                            YouTube
                        </label>
                        <input wire:model="youtube" type="text"
                            class="w-full bg-slate-50 border-none rounded-xl font-bold p-3">
                    </div>
                </div>
            </div>

            <!-- ================= VISI MISI ================= -->
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">
                    Visi & Misi
                </h3>

                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                        Visi Gereja
                    </label>
                    <textarea wire:model="visi" rows="3"
                        class="w-full bg-slate-50 border-none rounded-2xl font-bold p-4"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">
                        Poin Misi
                    </label>

                    @foreach($misi as $index => $m)
                        <div class="flex gap-2 mb-2">
                            <input type="text"
                                wire:model="misi.{{ $index }}"
                                class="flex-1 bg-slate-50 border-none rounded-xl font-medium p-3"
                                placeholder="Tulis poin misi...">
                            <button type="button"
                                wire:click="removeMisi({{ $index }})"
                                class="p-3 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-100">
                                X
                            </button>
                        </div>
                    @endforeach

                    <button type="button"
                        wire:click="addMisi"
                        class="mt-2 text-xs font-black text-blue-600 uppercase tracking-wide hover:underline">
                        + Tambah Poin Misi
                    </button>
                </div>
            </div>

            <!-- ================= SAVE ================= -->
            <div class="flex justify-end">
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-10 py-4 bg-slate-900 text-white rounded-full font-black text-xs uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                    <span wire:loading.remove>Simpan Pengaturan</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>

        </form>
    </div>
</div>
