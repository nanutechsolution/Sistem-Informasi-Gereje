<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col gap-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Data Jemaat</h1>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Total: {{ $members->total() }} Jiwa ({{ ucfirst($statusTab) }})</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('people.index') }}" class="p-3 bg-white text-slate-600 border border-slate-200 rounded-full sm:rounded-2xl shadow-sm hover:border-slate-300 transition-colors" title="Master Data Orang">
                        <span class="hidden sm:inline font-bold text-xs uppercase tracking-widest px-2">Master Orang</span>
                        <svg class="w-6 h-6 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>

                    <a href="{{ route('families.index') }}" class="p-3 bg-slate-900 text-white rounded-full sm:rounded-2xl shadow-lg hover:bg-primary transition-colors" title="Kelola Keluarga">
                        <span class="hidden sm:inline font-black text-xs uppercase tracking-widest px-2">+ Anggota (Via KK)</span>
                        <svg class="w-6 h-6 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                @foreach(['aktif', 'pindah', 'meninggal'] as $status)
                <button wire:click="setTab('{{ $status }}')"
                    class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border
                    {{ $statusTab === $status ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300' }}">
                    {{ $status }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-4 rounded-[24px] shadow-sm border border-slate-100 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative col-span-1 sm:col-span-2 lg:col-span-1">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari Nama / NIK..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 text-sm focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400 transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div class="relative">
                <select wire:model.live="wilayahFilter" class="w-full py-3 px-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-sm focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Wilayah</option>
                    @foreach($refWilayahs as $w) <option value="{{ $w->id }}">{{ $w->nama }}</option> @endforeach
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg></div>
            </div>

            <div class="relative">
                <select wire:model.live="genderFilter" class="w-full py-3 px-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-sm focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Gender</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg></div>
            </div>

            <div class="relative">
                <select wire:model.live="pekerjaanFilter" class="w-full py-3 px-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-sm focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <option value="">Semua Pekerjaan</option>
                    @foreach($refPekerjaans as $p) <option value="{{ $p->id }}">{{ $p->nama }}</option> @endforeach
                </select>
                <div class="absolute right-4 top-3.5 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($members as $m)
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $m->status_keanggotaan == 'aktif' ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-lg shrink-0 uppercase">
                        {{ substr($m->churchPeople->full_name ?? 'X', 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-black text-slate-800 text-base truncate">{{ $m->churchPeople->full_name ?? '-' }}</h3>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $m->refHubunganKeluarga->nama ?? 'Anggota' }}</p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-slate-50 rounded-lg text-[10px] font-bold text-slate-500 border border-slate-100">
                                {{ $m->churchPeople->gender == 'L' ? 'L' : 'P' }}
                            </span>
                            <span class="px-2 py-1 bg-blue-50 rounded-lg text-[10px] font-bold text-blue-600 border border-blue-100">
                                {{ $m->family->wilayah->nama ?? '-' }}
                            </span>
                            <span class="px-2 py-1 bg-amber-50 rounded-lg text-[10px] font-bold text-amber-600 border border-amber-100">
                                {{ $m->refPekerjaan->nama ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <a href="{{ route('members.edit', $m->uuid) }}" class="p-2 text-amber-300 hover:text-amber-500 bg-amber-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <button wire:click="delete('{{ $m->uuid }}')" wire:confirm="Hapus data keanggotaan ini?" class="p-2 text-rose-300 hover:text-rose-500 bg-rose-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 text-center bg-white rounded-[24px] border border-slate-100 border-dashed">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-bold text-slate-400">Tidak ada data ditemukan.</p>
            </div>
            @endforelse
        </div>

        <div class="hidden md:block bg-white rounded-[32px] shadow-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">JK</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Hub. Keluarga</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wilayah</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pekerjaan</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($members as $m)
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6">
                            <span class="block font-black text-slate-800">{{ $m->churchPeople->full_name ?? '-' }}</span>
                            <span class="text-xs text-slate-400 font-medium font-mono">NIK: {{ $m->churchPeople->nik ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6 text-sm font-bold text-slate-600">
                            {{ $m->churchPeople->gender ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-sm font-bold text-slate-600">
                            {{ $m->refHubunganKeluarga->nama ?? '-' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-slate-600">
                                {{ $m->family->wilayah->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm font-bold text-slate-600">
                            {{ $m->refPekerjaan->nama ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('members.show', $m) }}"
                                    class="p-2 bg-slate-100 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all shadow-sm group"
                                    title="Lihat Detail Profil">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('members.edit', $m->uuid) }}" class="text-amber-400 hover:text-amber-500 font-bold text-xs uppercase tracking-wider">Edit</a>
                                <button wire:click="delete('{{ $m->uuid }}')" wire:confirm="Hapus data keanggotaan ini?" class="text-rose-400 hover:text-rose-500 font-bold text-xs uppercase tracking-wider">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>
</div>