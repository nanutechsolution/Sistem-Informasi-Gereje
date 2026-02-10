<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen User</h1>
                <p class="text-slate-500 mt-1">Kelola akun login dan hak akses sistem.</p>
            </div>
            <a href="{{ route('users.create') }}" class="inline-flex justify-center items-center px-6 py-3 bg-primary text-white rounded-2xl font-bold shadow-lg hover:scale-105 transition-transform active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Tambah User
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl font-bold text-sm focus:ring-2 focus:ring-primary/20" placeholder="Cari nama atau email...">
                <div class="absolute left-3 top-3.5 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
            </div>
            <select wire:model.live="filterRole" class="bg-slate-50 border-none rounded-xl font-bold text-sm text-slate-600 focus:ring-primary/20 cursor-pointer">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabel User -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama Pengguna</th>
                        <th class="px-6 py-5">Email</th>
                        <th class="px-6 py-5">Role / Jabatan</th>
                        <th class="px-6 py-5">Terdaftar</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black border border-slate-200">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-medium text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-5">
                            @foreach($user->roles as $role)
                                <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest 
                                    {{ $role->name == 'admin' ? 'bg-rose-100 text-rose-700' : ($role->name == 'bendahara' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-5 text-xs font-bold text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-primary bg-white border border-slate-200 rounded-xl transition-all shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"></path></svg></a>
                                @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Hapus user ini? Akses login akan hilang." class="p-2 text-slate-300 hover:text-rose-600 bg-white border border-slate-200 rounded-xl transition-all shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path></svg></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-20 text-center text-slate-400 italic font-medium">Tidak ada user ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</div>