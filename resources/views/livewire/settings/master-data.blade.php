<div class="py-6 sm:py-12 bg-gray-50 min-h-screen" x-data="{ showForm: @entangle('isModalOpen'), showDelete: @entangle('isDeleteModalOpen') }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-primary tracking-tight">Pengaturan Sistem</h1>
            <p class="text-gray-500 mt-2 text-lg">Kelola data referensi (Master Data) untuk aplikasi.</p>
        </div>

        <!-- NAVIGASI TAB (Scrollable di Mobile) -->
        <div class="mb-8 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
            <nav class="flex space-x-2" aria-label="Tabs">
                @php
                    $tabs = [
                        'wilayah' => 'Wilayah Pelayanan',
                        'pekerjaan' => 'Pekerjaan',
                        'hubungan' => 'Hubungan Keluarga',
                        'event' => 'Jenis Peristiwa'
                    ];
                @endphp

                @foreach($tabs as $key => $label)
                <a href="{{ route('settings.master', $key) }}" 
                   class="{{ $currentType === $key ? 'bg-primary text-white shadow-lg shadow-blue-500/30' : 'bg-white text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} 
                          px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap transition-all">
                    {{ $label }}
                </a>
                @endforeach
            </nav>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $config['title'] }}</h2>
                    <p class="text-sm text-gray-500">Total Data: {{ $data->total() }}</p>
                </div>
                <button wire:click="create" class="inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-500/20 hover:bg-blue-800 transition-transform active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Data
                </button>
            </div>

            <!-- Search -->
            <div class="relative mb-6">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" placeholder="Cari data...">
                <div class="absolute left-3 top-3.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- LIST DATA (Card List) -->
            <div class="space-y-3">
                @forelse($data as $item)
                <div class="group flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl hover:border-blue-200 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-blue-50 text-primary flex items-center justify-center font-bold text-lg">
                            {{ substr($item->nama, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $item->nama }}</div>
                            @if($config['has_kategori'])
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600 uppercase">{{ $item->kategori }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                        <button wire:click="edit({{ $item->id }})" class="p-2 text-gray-400 hover:text-primary bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button wire:click="confirmDelete({{ $item->id }})" class="p-2 text-gray-400 hover:text-red-600 bg-gray-50 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada data.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $data->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL FORM -->
    <div x-show="showForm" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showForm = false"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editId ? 'Edit Data' : 'Tambah Data Baru' }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">{{ $config['label'] }}</label>
                            <input wire:model="nama" type="text" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Masukkan nama...">
                            @error('nama') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Input Kategori (Khusus Event) -->
                        @if($config['has_kategori'])
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                            <select wire:model="kategori" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">-- Pilih --</option>
                                <option value="rohani">Rohani</option>
                                <option value="sipil">Sipil</option>
                                <option value="mutasi">Mutasi</option>
                            </select>
                            @error('kategori') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button wire:click="save" type="button" class="inline-flex w-full justify-center rounded-xl bg-primary px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-blue-800 sm:ml-3 sm:w-auto">Simpan</button>
                    <button @click="showForm = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRM DELETE -->
    <div x-show="showDelete" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDelete = false"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Data?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Anda yakin ingin menghapus data <span class="font-bold text-gray-800">"{{ $deleteName }}"</span>?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button wire:click="delete" type="button" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Ya, Hapus</button>
                    <button @click="showDelete = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>

</div>