<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Data Keluarga</h1>
                <p class="text-slate-500 mt-2 font-medium">Total {{ $families->total() }} Kartu Keluarga (KK) terdaftar.</p>
            </div>
            <a href="{{ route('families.create') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary transition-colors shadow-lg shadow-slate-200">
                + KK Baru
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2 relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari No. KK, Nama Kepala, atau Alamat..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="relative">
                <select wire:model.live="statusFilter" class="w-full pl-4 pr-10 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="pindah">Pindah</option>
                    <option value="keluar">Keluar</option>
                    <option value="disiplin">Disiplin</option>
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>

            <div class="relative">
                <select wire:model.live="wilayahFilter" class="w-full pl-4 pr-10 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Wilayah</option>
                    @foreach($refWilayahs as $w)
                        <option value="{{ $w->id }}">{{ $w->nama }}</option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($families as $family)
            @php
                $kepala = $family->members->first();
                $namaKepala = $kepala ? ($kepala->churchPeople->full_name ?? 'Data Orang Hilang') : 'Belum Ada Anggota';
            @endphp
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $family->status == 'aktif' ? 'bg-emerald-400' : 'bg-slate-300' }}"></div>
                
                <div class="flex justify-between items-start mb-2 pl-3">
                    <a href="{{ route('families.show', $family->uuid) }}" class="block">
                        <h3 class="font-black text-slate-800 text-lg leading-tight hover:text-primary transition-colors">{{ $namaKepala }}</h3>
                        <p class="text-xs font-mono text-slate-400 mt-1">NO. KK: {{ $family->nomor_kk }}</p>
                    </a>
                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-600">
                        {{ $family->members_count }} Jiwa
                    </span>
                </div>

                <div class="pl-3 mt-3 space-y-2">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            {{ $family->alamat }} <br>
                            <span class="text-primary font-bold">Wilayah {{ $family->wilayah->nama ?? '-' }}</span>
                        </p>
                    </div>
                </div>

                <div class="pl-3 mt-4 pt-3 border-t border-slate-50 flex justify-between items-center">
                    <span class="inline-block px-2 py-1 rounded text-[10px] font-bold uppercase {{ $family->status == 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                        {{ $family->status }}
                    </span>
                    <div class="flex gap-4">
                        <a href="{{ route('families.show', $family->uuid) }}" class="text-blue-600 font-black text-[10px] uppercase tracking-widest">Detail</a>
                        <a href="{{ route('families.edit', $family->uuid) }}" class="text-amber-500 font-black text-[10px] uppercase tracking-widest">Edit</a>
                        <button wire:click="delete('{{ $family->uuid }}')" wire:confirm="Hapus KK ini?" class="text-rose-400 font-black text-[10px] uppercase tracking-widest">Hapus</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-400 text-sm">Data tidak ditemukan.</div>
            @endforelse
            
            <div class="mt-4">{{ $families->links() }}</div>
        </div>

        <!-- Desktop View -->
        <div class="hidden md:block bg-white rounded-[32px] shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kepala Keluarga & No. KK</th>
                            <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Anggota Keluarga</th>
                            <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Domisili</th>
                            <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($families as $family)
                        @php
                            $kepala = $family->members->first();
                            $namaKepala = $kepala ? ($kepala->churchPeople->full_name ?? 'Data Error') : 'Belum Ada Anggota';
                            $previewAnggota = $family->members->take(3)->map(function($m) {
                                return explode(' ', trim($m->churchPeople->full_name ?? ''))[0];
                            })->join(', ');
                            $sisaAnggota = $family->members_count > 3 ? '+' . ($family->members_count - 3) . ' lainnya' : '';
                        @endphp
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-sm uppercase shadow-md shadow-slate-200">
                                        {{ substr($namaKepala, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('families.show', $family->uuid) }}" class="block font-black text-slate-800 text-sm hover:text-primary transition-colors">{{ $namaKepala }}</a>
                                        <span class="text-xs text-slate-400 font-mono tracking-wide">{{ $family->nomor_kk }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6 align-top">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold mb-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ $family->members_count }} Jiwa
                                </span>
                                <p class="text-xs text-slate-500 mt-1 max-w-[200px] truncate">{{ $previewAnggota }} {{ $sisaAnggota }}</p>
                            </td>

                            <td class="py-4 px-6 align-top">
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase mb-1 inline-block">
                                    {{ $family->wilayah->nama ?? '-' }}
                                </span>
                                <p class="text-xs font-medium text-slate-600 line-clamp-2 max-w-[250px]">{{ $family->alamat ?? '-' }}</p>
                            </td>

                            <td class="py-4 px-6 align-top text-center">
                                @php
                                    $statusColor = match($family->status) {
                                        'aktif' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'pindah' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'keluar' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'disiplin' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600'
                                    };
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusColor }}">
                                    {{ $family->status }}
                                </span>
                            </td>

                            <td class="py-4 px-6 align-middle">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('families.show', $family->uuid) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-primary hover:bg-primary hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('families.edit', $family->uuid) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-amber-500 hover:bg-amber-50 hover:border-amber-200 transition-all shadow-sm" title="Edit KK">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button wire:click="delete('{{ $family->uuid }}')" wire:confirm="Hapus Data Keluarga {{ $namaKepala }}?" class="p-2 bg-white border border-slate-200 rounded-lg text-rose-500 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                {{ $families->links() }} 
            </div>
        </div>
    </div>
</div>