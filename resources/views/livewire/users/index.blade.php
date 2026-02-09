<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data
    x-init="
        @if (session()->has('message'))
            $dispatch('notify', { message: '{{ session('message') }}', type: 'success' });
        @endif
        @if (session()->has('error'))
            $dispatch('notify', { message: '{{ session('error') }}', type: 'error' });
        @endif
    "
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Daftar Personil</h1>
                <p class="text-gray-500 mt-2 text-lg">Kelola data pengurus, pendeta, dan staf gereja.</p>
            </div>
            
            <a href="{{ route('users.create') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Personil
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-150 ease-in-out" 
                    placeholder="Cari nama atau email...">
            </div>

            <!-- Filter Role -->
            <div class="w-full sm:w-56">
                <div class="relative">
                     <select wire:model.live="roleFilter" class="block w-full py-3 pl-3 pr-10 border border-gray-200 bg-gray-50 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none">
                        <option value="">Semua Peran</option>
                        <option value="admin">Administrator</option>
                        <option value="pendeta">Pendeta</option>
                        <option value="majelis">Majelis</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="sekretaris">Sekretaris</option>
                        <option value="operator">Operator</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE VIEW: Cards -->
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            @forelse($users as $user)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                         <div class="h-12 w-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' : 
                           ($user->role === 'pendeta' ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
                        {{ $user->role }}
                    </span>
                    
                    <div class="flex items-center gap-3">
                         <a href="{{ route('users.edit', $user->id) }}" class="text-gray-400 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <button wire:click="delete({{ $user->id }})" wire:confirm="Hapus {{ $user->name }}?" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
             <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada data ditemukan</h3>
                <p class="mt-1 text-gray-500">Coba ubah kata kunci pencarian atau filter Anda.</p>
            </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Nama & Email</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Peran</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider">Terdaftar</th>
                        <th class="px-6 py-4 font-bold text-gray-900 uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' : 
                                   ($user->role === 'pendeta' ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin ingin menghapus {{ $user->name }}?" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Tidak ada personil ditemukan</h3>
                                <p class="mt-1 text-gray-500 max-w-sm">Coba ubah kata kunci pencarian atau filter peran Anda untuk menemukan hasil.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
