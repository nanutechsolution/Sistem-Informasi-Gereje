<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data="{
        formatRupiah(value) {
            if(!value) return '';
            let number = value.toString().replace(/[^0-9]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        showYearModal: @entangle('isYearModalOpen'),
        showPostModal: @entangle('isPostModalOpen')
    }"
>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Atur RAPB</h1>
                <p class="text-gray-500 mt-1">Target anggaran pendapatan & belanja jemaat.</p>
            </div>
            
            <!-- Pilihan Tahun & Tombol Tambah -->
            <div class="flex items-center gap-2 w-full sm:w-auto bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                <select wire:model.live="fiscalYearId" class="bg-transparent border-none text-gray-700 font-bold focus:ring-0 cursor-pointer py-2 pl-3 pr-8 text-sm">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($fiscalYears as $year)
                        <option value="{{ $year->id }}">{{ $year->tahun }} {{ $year->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
                
                <div class="h-6 w-px bg-gray-200"></div>

                <!-- Tombol Tambah Tahun -->
                <button @click="showYearModal = true" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Buat Tahun Baru">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </button>

                <!-- Tombol Set Aktif -->
                @php $selectedYear = $fiscalYears->firstWhere('id', $fiscalYearId); @endphp
                @if($selectedYear && !$selectedYear->is_active)
                    <button wire:click="activateYear" wire:confirm="Aktifkan Tahun Anggaran {{ $selectedYear->tahun }}?" class="ml-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-lg font-bold text-xs hover:bg-green-200 transition flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Aktifkan
                    </button>
                @endif
            </div>
        </div>

        @if($fiscalYearId)
        <form wire:submit="save" class="space-y-6">
            
            <!-- Sticky Header Actions -->
            <div class="sticky top-0 z-20 bg-gray-50/95 backdrop-blur-sm border-b border-gray-200 py-4 flex justify-between items-center -mx-4 px-4 sm:mx-0 sm:px-0">
                <button type="button" @click="showPostModal = true" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-lg border border-blue-100 hover:bg-blue-100 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pos
                </button>
                <button type="submit" 
                    wire:loading.attr="disabled" wire:target='save'
                    class="inline-flex items-center px-6 py-2.5 bg-primary border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-800 focus:outline-none transition transform active:scale-95 shadow-lg shadow-blue-500/30">
                    <span wire:loading.remove wire:target='save'>Simpan Perubahan</span>
                    <span wire:loading class="flex items-center gap-2" wire:target='save'>
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>

            <!-- List Anggaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @foreach($groupedPosts as $parent)
                <div class="border-b border-gray-100 last:border-0">
                    <!-- Judul Kategori Besar -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold px-2 py-1 rounded bg-white border border-gray-200 text-gray-500 uppercase tracking-wide">{{ $parent->jenis }}</span>
                            <h2 class="text-lg font-extrabold text-gray-800">{{ $parent->kode }}. {{ $parent->nama }}</h2>
                        </div>
                    </div>

                    <!-- List Sub-Kategori -->
                    <div class="divide-y divide-gray-50">
                        @foreach($parent->children as $child)
                        <div class="p-4 sm:px-6 hover:bg-blue-50/20 transition-colors">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                
                                <!-- Label -->
                                <div class="sm:col-span-7">
                                    <div class="flex items-start gap-3">
                                        <span class="font-mono text-xs font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded mt-0.5">{{ $child->kode }}</span>
                                        <div>
                                            <p class="font-bold text-gray-700">{{ $child->nama }}</p>
                                            @if($child->children->count() > 0)
                                                <p class="text-[10px] text-orange-500 mt-0.5 font-medium">* Isi detail pada sub-pos di bawah</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Nominal -->
                                <div class="sm:col-span-5">
                                    @if($child->children->count() == 0)
                                    <div class="relative group">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm group-focus-within:text-primary transition-colors">Rp</span>
                                        <input type="text" 
                                            wire:model="targets.{{ $child->id }}"
                                            x-init="$el.value = formatRupiah($el.value)"
                                            x-on:input="$el.value = formatRupiah($el.value)"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary font-mono text-right font-bold text-gray-900 transition-all shadow-sm"
                                            placeholder="0">
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Level 3 (Sub-Sub Kategori) -->
                            @if($child->children->count() > 0)
                            <div class="mt-3 ml-4 sm:ml-8 border-l-2 border-gray-100 pl-4 space-y-3">
                                @foreach($child->children as $subChild)
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center py-2">
                                    <div class="sm:col-span-7 flex items-center gap-2">
                                        <span class="font-mono text-[10px] text-gray-400">{{ $subChild->kode }}</span>
                                        <span class="text-sm font-medium text-gray-600">{{ $subChild->nama }}</span>
                                    </div>
                                    <div class="sm:col-span-5 relative group">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs group-focus-within:text-primary">Rp</span>
                                        <input type="text" 
                                            wire:model="targets.{{ $subChild->id }}"
                                            x-init="$el.value = formatRupiah($el.value)"
                                            x-on:input="$el.value = formatRupiah($el.value)"
                                            class="w-full pl-9 pr-3 py-2 bg-white border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary font-mono text-right text-sm font-bold text-gray-800 shadow-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </form>
        @else
        <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Pilih Tahun Anggaran</h3>
            <p class="text-sm text-gray-500 mt-1">Silakan pilih tahun di atas atau buat baru untuk memulai penyusunan RAPB.</p>
        </div>
        @endif

    </div>

    <!-- MODAL 1: TAMBAH TAHUN -->
    <div x-show="showYearModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showYearModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tahun Anggaran Baru</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun (Angka)</label>
                        <input wire:model="newYear" type="number" placeholder="Contoh: 2027" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary font-bold">
                        @error('newYear') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                        <input wire:model="newYearDesc" type="text" placeholder="RAPB Tahun 2027" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary">
                    </div>
                    <button wire:click="saveYear" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-blue-800 transition shadow-lg">Simpan Tahun</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TAMBAH POS ANGGARAN -->
    <div x-show="showPostModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showPostModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Pos Anggaran Baru</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Pos</label>
                            <input wire:model="newPostCode" type="text" placeholder="Misal: 1.3.01" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary font-mono font-bold text-sm">
                            @error('newPostCode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis</label>
                            <select wire:model="newPostType" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary">
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Pos</label>
                        <input wire:model="newPostName" type="text" placeholder="Contoh: Subsidi Pembangunan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary font-medium">
                        @error('newPostName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Induk Kategori (Optional)</label>
                        <select wire:model="newPostParentId" class="w-full border-gray-200 rounded-xl p-3 focus:ring-primary text-sm">
                            <option value="">-- Pos Utama (Level 1) --</option>
                            @foreach($parentPosts as $p)
                                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="savePost" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-blue-800 transition shadow-lg">Simpan Pos</button>
                </div>
            </div>
        </div>
    </div>

</div>
