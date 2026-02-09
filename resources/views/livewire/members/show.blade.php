<div class="py-6 sm:py-12 bg-gray-50 min-h-screen" x-data="{ showModal: @entangle('isAddingEvent') }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 1. HEADER & NAVIGASI -->
        <div class="mb-8 flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
            <!-- Navigasi Balik -->
            <div class="flex-1">
                <a href="{{ route('members.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors group mb-4">
                    <div class="p-1 rounded-full group-hover:bg-blue-50 transition mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </div>
                    Kembali ke Daftar Jemaat
                </a>

                <div class="flex items-start gap-4">
                    <!-- Avatar Besar (Inisial) -->
                    <div class="hidden sm:flex h-16 w-16 rounded-2xl bg-gradient-to-br from-primary to-blue-600 items-center justify-center text-white text-2xl font-extrabold shadow-lg shadow-blue-500/20">
                        {{ substr($member->nama, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">{{ $member->nama }}</h1>
                        <div class="mt-2 flex flex-wrap gap-3 text-sm text-gray-500 font-medium">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                {{ $member->nik ?? 'Belum ada NIK' }}
                            </span>
                            <span class="hidden sm:inline text-gray-300">|</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $member->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi (Mobile Friendly) -->
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('members.edit', $member) }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Profil
                </a>
                <button @click="showModal = true" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-xl text-sm font-bold text-white hover:bg-blue-800 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Catat Peristiwa
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- 2. KARTU PROFIL RINGKAS (KIRI) -->
            <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Dasar</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs text-gray-400 font-semibold uppercase">Hubungan Keluarga</dt>
                            <!-- Menampilkan data dari tabel master -->
                            <dd class="mt-1 font-medium text-gray-900 bg-gray-50 inline-block px-2 py-1 rounded text-sm border border-gray-100">
                                {{ $member->refHubunganKeluarga->nama ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 font-semibold uppercase">Pekerjaan</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $member->refPekerjaan->nama ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 font-semibold uppercase">TTL / Usia</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ $member->tempat_lahir ?? '-' }}, {{ $member->tanggal_lahir ? $member->tanggal_lahir->format('d M Y') : '-' }}
                                @if($member->tanggal_lahir)
                                <span class="block mt-1 text-xs font-bold text-primary bg-blue-50 w-fit px-2 py-0.5 rounded">
                                    {{ $member->tanggal_lahir->age }} Tahun
                                </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Status Gerejawi (Sama seperti sebelumnya) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Gerejawi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between"><span class="text-sm font-medium text-gray-600">Baptis</span><span class="font-bold text-xs">{{ $member->status_baptis }}</span></div>
                        <div class="flex justify-between"><span class="text-sm font-medium text-gray-600">Sidi</span><span class="font-bold text-xs">{{ $member->status_sidi }}</span></div>
                        <div class="flex justify-between"><span class="text-sm font-medium text-gray-600">Nikah</span><span class="font-bold text-xs">{{ $member->status_nikah }}</span></div>
                    </div>
                </div>
            </div>

            <!-- 3. TIMELINE SEJARAH (KANAN) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 relative overflow-hidden">
                    <!-- Dekorasi Background -->
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-purple-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="p-2 bg-purple-100 text-purple-700 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Perjalanan Iman & Sipil</h2>
                        </div>

                        <!-- Garis Timeline -->
                        <div class="relative border-l-2 border-gray-200 ml-3 space-y-10 pl-8 pb-4">

                            @forelse($member->events as $event)
                            <div class="relative group">
                                <!-- Bullet -->
                                <div class="absolute -left-[43px] top-1.5 h-6 w-6 rounded-full border-4 border-white bg-primary shadow-sm ring-1 ring-gray-100 group-hover:scale-110 transition-transform"></div>

                                <!-- Card Event -->
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-300">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $event->eventType->nama }}</h4>
                                                @if($event->eventType->kategori == 'rohani')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-100 text-purple-700 font-bold uppercase tracking-wide">Rohani</span>
                                                @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-gray-200 text-gray-600 font-bold uppercase tracking-wide">{{ $event->eventType->kategori }}</span>
                                                @endif
                                            </div>

                                            <p class="text-sm font-medium text-primary mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $event->tanggal->isoFormat('D MMMM Y') }}
                                            </p>
                                        </div>

                                        <!-- Hapus Button (Hanya Admin) -->
                                        @if(in_array(auth()->user()->role, ['admin', 'pendeta']))
                                        <button wire:click="deleteEvent({{ $event->id }})" wire:confirm="Yakin ingin menghapus catatan sejarah ini?" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50" title="Hapus Riwayat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Detail Grid -->
                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4 text-sm text-gray-600">
                                        @if($event->lokasi)
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="flex-1"><span class="block text-xs text-gray-400 uppercase font-bold">Lokasi</span>{{ $event->lokasi }}</span>
                                        </div>
                                        @endif

                                        @if($event->pendeta)
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="flex-1"><span class="block text-xs text-gray-400 uppercase font-bold">Dilayani Oleh</span>{{ $event->pendeta }}</span>
                                        </div>
                                        @endif

                                        @if($event->nomor_surat)
                                        <div class="flex items-start gap-2 sm:col-span-2">
                                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="flex-1"><span class="block text-xs text-gray-400 uppercase font-bold">No. Surat / Akta</span><span class="font-mono text-gray-800 bg-white px-1 rounded border border-gray-200">{{ $event->nomor_surat }}</span></span>
                                        </div>
                                        @endif
                                    </div>

                                    @if($event->keterangan)
                                    <div class="mt-3 pt-3 border-t border-gray-200/50">
                                        <p class="text-sm text-gray-500 italic">"{{ $event->keterangan }}"</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada riwayat tercatat.</p>
                                <p class="text-sm text-gray-400 max-w-xs mt-1">Tambahkan peristiwa seperti Baptis, Sidi, atau Pernikahan melalui tombol di atas.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MODAL FORM (Z-Index Fix & Backdrop Blur) -->
        <div x-show="showModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="showModal = false"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <!-- Modal Panel -->
                    <div x-show="showModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">

                        <!-- Header Modal -->
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 leading-6" id="modal-title">Catat Peristiwa Baru</h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Form Content -->
                        <form wire:submit="saveEvent">
                            <div class="px-4 py-5 sm:p-6 space-y-5">

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Peristiwa <span class="text-red-500">*</span></label>
                                    <select wire:model="event_type_id" class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 transition-all">
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($eventTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_type_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal" class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5">
                                        @error('tanggal') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Surat / SK</label>
                                        <input type="text" wire:model="nomor_surat" placeholder="No. Administrasi..." class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi / Gereja</label>
                                    <input type="text" wire:model="lokasi" placeholder="Contoh: GKS Jemaat Waingapu" class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Pendeta Pelayan</label>
                                    <input type="text" wire:model="pendeta" placeholder="Nama Pendeta..." class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan Tambahan</label>
                                    <textarea wire:model="keterangan" rows="2" placeholder="Catatan khusus..." class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5"></textarea>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                <button type="submit" wire:loading.attr="disabled" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-blue-500/30 px-5 py-2.5 bg-primary text-base font-bold text-white hover:bg-blue-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-all transform active:scale-95 disabled:opacity-70">
                                    <span wire:loading.remove>Simpan Riwayat</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Menyimpan...
                                    </span>
                                </button>
                                <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>