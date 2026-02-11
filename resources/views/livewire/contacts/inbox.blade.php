<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ open: @entangle('isModalOpen').live }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Kotak Masuk</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-primary pl-4 uppercase text-[10px] tracking-widest">Pesan dari Website Publik</p>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/5 shadow-sm" placeholder="Cari pesan...">
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5">Pengirim</th>
                            <th class="px-6 py-5">Subjek</th>
                            <th class="px-6 py-5">Waktu</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($messages as $msg)
                        <tr class="hover:bg-blue-50/30 transition-colors cursor-pointer {{ $msg->is_read ? 'opacity-70' : 'bg-white font-bold' }}" wire:click="openMessage({{ $msg->id }})">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $msg->is_read ? 'bg-slate-200' : 'bg-primary animate-pulse' }}"></div>
                                    <span class="{{ $msg->is_read ? 'font-medium' : 'font-black' }}">{{ $msg->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-600 truncate max-w-xs">{{ $msg->subjek }}</td>
                            <td class="px-6 py-5 text-xs text-slate-400 font-bold uppercase">{{ $msg->created_at->diffForHumans() }}</td>
                            <td class="px-8 py-5 text-right">
                                <button class="p-2 text-slate-300 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-20 text-center text-slate-300 font-black uppercase tracking-widest text-[10px]">Belum ada pesan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $messages->links() }}</div>
    </div>

    <!-- MODAL DETAIL PESAN -->
    @if($activeMessage)
    <div x-show="open" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="open = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-[40px] p-10 shadow-2xl animate-in zoom-in-95 duration-200">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>
                
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 italic uppercase leading-none mb-2">{{ $activeMessage->subjek }}</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $activeMessage->nama }} &bull; {{ $activeMessage->email }}</p>
                    </div>
                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full uppercase tracking-wider">{{ $activeMessage->created_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap font-medium">
                    {{ $activeMessage->pesan }}
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <button wire:click="delete({{ $activeMessage->id }})" class="px-6 py-3 bg-rose-50 text-rose-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-100 transition-all">Hapus Pesan</button>
                    <button @click="open = false" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary transition-all shadow-xl">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>