<div class="py-6 sm:py-12 bg-slate-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
    "
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Data Keluarga</h1>
                <p class="text-gray-500 mt-2 text-lg">Basis data Kepala Keluarga (KK) dan Domisili.</p>
            </div>
            
            <a href="{{ route('families.create') }}" class="inline-flex justify-center items-center px-6 py-3 bg-primary text-white rounded-2xl font-bold shadow-lg hover:bg-blue-800 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah KK Baru
            </a>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-6 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                    placeholder="Cari Kepala Keluarga atau No. KK...">
            </div>
        </div>

        <!-- MOBILE VIEW: Cards -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($families as $family)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">NO. KK: {{ $family->nomor_kk }}</div>
                        {{-- Tautkan Nama ke Halaman Show --}}
                        <a href="{{ route('families.show', $family) }}" class="font-black text-primary text-lg uppercase italic hover:underline">
                            {{ $family->kepala_keluarga }}
                        </a>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        {{ $family->refWilayah->nama ?? 'Wilayah -' }}
                    </span>
                </div>
                
                <p class="text-sm text-gray-500 mb-4">{{ Str::limit($family->alamat, 60) }}</p>
                
                <div class="flex items-center gap-2 pt-3 border-t border-slate-50">
                    <a href="{{ route('families.show', $family) }}" class="flex-1 text-center text-sm font-bold text-slate-600 bg-slate-50 py-2 rounded-lg border border-slate-200">Detail</a>
                    <a href="{{ route('families.edit', $family) }}" class="flex-1 text-center text-sm font-bold text-primary bg-blue-50 py-2 rounded-lg border border-blue-100">Edit</a>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-400">Belum ada data keluarga.</div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Table -->
        <div class="hidden sm:block bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
             <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Kepala Keluarga & No. KK</th>
                        <th class="px-6 py-4">Wilayah</th>
                        <th class="px-6 py-4">Alamat Domisili</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($families as $family)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            {{-- Tautkan Nama ke Halaman Show --}}
                            <a href="{{ route('families.show', $family) }}" class="font-black text-primary text-base uppercase italic hover:underline decoration-2 underline-offset-4">
                                {{ $family->kepala_keluarga }}
                            </a>
                            <div class="text-xs font-mono text-slate-400 mt-1">KK: {{ $family->nomor_kk }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $family->refWilayah->nama ?? 'Tanpa Wilayah' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 truncate max-w-xs">{{ Str::limit($family->alamat, 50) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $family->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $family->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('families.show', $family) }}" class="p-2 text-slate-400 hover:text-primary transition-all" title="Lihat Profil Keluarga">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('families.edit', $family) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-all" title="Edit Data & Anggota">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                @if(auth()->user()->can('manage_database'))
                                <button wire:click="delete('{{ $family->id }}')" wire:confirm="Hapus KK ini beserta anggotanya?" class="p-2 text-slate-300 hover:text-rose-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-20 text-center text-slate-400 italic font-medium">Data keluarga tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $families->links() }}
        </div>
    </div>
</div>