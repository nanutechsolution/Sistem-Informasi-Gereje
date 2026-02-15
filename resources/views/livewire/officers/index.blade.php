<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Data Pejabat</h1>
                <p class="text-slate-500 mt-2 font-medium">Majelis, Pendeta, dan Pegawai Gereja.</p>
            </div>
            <!-- Arahkan ke Create Officer -->
            <a href="{{ route('officers.create') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary transition-colors shadow-lg shadow-slate-200">
                + Pejabat Baru
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="sm:col-span-2 relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama, NIP, atau No SK..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Filter Jabatan -->
            <div class="relative">
                <select wire:model.live="filterPosition" class="w-full py-3 px-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-sm focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Jabatan</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->nama }}</option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>

            <!-- Filter Status -->
            <div class="relative">
                <select wire:model.live="filterStatus" class="w-full py-3 px-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-sm focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="organik">Organik</option>
                    <option value="non_organik">Non Organik</option>
                    <option value="vicaris">Vicaris</option>
                    <option value="majelis">Majelis</option>
                    <option value="relawan">Relawan</option>
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>
        </div>

        <!-- CONTENT -->

        <!-- MOBILE VIEW (Cards) -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($officers as $officer)
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden">
                <!-- Status Stripe -->
                @php
                    $isActive = is_null($officer->tanggal_selesai) || $officer->tanggal_selesai >= now();
                    $stripeColor = $isActive ? 'bg-emerald-400' : 'bg-slate-300';
                @endphp
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $stripeColor }}"></div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-lg shrink-0 uppercase">
                        {{ substr($officer->member->churchPeople->full_name ?? '?', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-black text-slate-800 text-base truncate">{{ $officer->member->churchPeople->full_name ?? '-' }}</h3>
                        <p class="text-xs font-bold text-primary mt-0.5">{{ $officer->position->nama ?? '-' }}</p>
                        
                        <div class="mt-2 text-[10px] text-slate-400 font-mono space-y-1">
                            <p>NIP: {{ $officer->nip_gereja ?? '-' }}</p>
                            <p>SK : {{ $officer->nomor_sk ?? '-' }}</p>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-slate-50 rounded text-[10px] font-bold text-slate-500 border border-slate-100 uppercase">
                                {{ str_replace('_', ' ', $officer->status_kepegawaian) }}
                            </span>
                            <span class="px-2 py-1 bg-blue-50 rounded text-[10px] font-bold text-blue-600 border border-blue-100 uppercase">
                                {{ $officer->lokasi_tugas }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('officers.edit', $officer->uuid) }}" class="p-2 text-amber-400 bg-amber-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        <button wire:click="delete('{{ $officer->uuid }}')" wire:confirm="Hapus data?" class="p-2 text-rose-400 bg-rose-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-400 text-sm">Data tidak ditemukan.</div>
            @endforelse
            <div class="mt-4">{{ $officers->links() }}</div>
        </div>

        <!-- DESKTOP VIEW (Table) -->
        <div class="hidden md:block bg-white rounded-[32px] shadow-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama & NIP</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jabatan</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status & Lokasi</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Masa Bhakti</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($officers as $officer)
                    @php
                        $isActive = is_null($officer->tanggal_selesai) || $officer->tanggal_selesai >= now();
                    @endphp
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6">
                            <span class="block font-black text-slate-800">{{ $officer->member->churchPeople->full_name ?? '-' }}</span>
                            <span class="text-xs text-slate-400 font-mono font-bold">{{ $officer->nip_gereja ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="block text-sm font-bold text-primary">{{ $officer->position->nama ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400">SK: {{ $officer->nomor_sk ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-600 uppercase">{{ str_replace('_', ' ', $officer->status_kepegawaian) }}</span>
                                <span class="text-[10px] text-slate-400 uppercase">Lokasi: {{ $officer->lokasi_tugas }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-xs font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($officer->tanggal_mulai)->format('d M Y') }} - 
                                {{ $officer->tanggal_selesai ? \Carbon\Carbon::parse($officer->tanggal_selesai)->format('d M Y') : 'Sekarang' }}
                            </div>
                            @if(!$isActive)
                                <span class="inline-block px-2 py-0.5 bg-slate-200 text-slate-500 rounded text-[10px] font-bold mt-1">Non-Aktif</span>
                            @else
                                <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-600 rounded text-[10px] font-bold mt-1">Aktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('officers.edit', $officer->uuid) }}" class="text-amber-400 hover:text-amber-500 font-bold text-xs uppercase tracking-wider">Edit</a>
                                <button wire:click="delete('{{ $officer->uuid }}')" wire:confirm="Hapus data?" class="text-rose-400 hover:text-rose-500 font-bold text-xs uppercase tracking-wider">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100">
                {{ $officers->links() }}
            </div>
        </div>
    </div>
</div>