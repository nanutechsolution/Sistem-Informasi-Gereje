<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Bilik Doa</h1>
                <p class="text-slate-500 mt-2 font-medium italic border-l-4 border-violet-500 pl-4 uppercase text-[10px] tracking-widest">
                    Daftar Permohonan Doa Jemaat
                </p>
            </div>
            
            <!-- Filter Tabs -->
            <div class="bg-white p-1 rounded-2xl border border-slate-200 shadow-sm flex">
                <button wire:click="$set('filterStatus', 'baru')" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filterStatus == 'baru' ? 'bg-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-violet-600' }}">
                    Baru <span class="ml-1 px-1.5 py-0.5 bg-white/20 rounded text-[9px]">{{ $counts['baru'] }}</span>
                </button>
                <button wire:click="$set('filterStatus', 'didoakan')" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filterStatus == 'didoakan' ? 'bg-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-violet-600' }}">
                    Proses <span class="ml-1 px-1.5 py-0.5 bg-white/20 rounded text-[9px]">{{ $counts['didoakan'] }}</span>
                </button>
                <button wire:click="$set('filterStatus', 'selesai')" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filterStatus == 'selesai' ? 'bg-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-violet-600' }}">
                    Arsip
                </button>
            </div>
        </div>

        <!-- Warning Konseling -->
        @if($counts['konseling'] > 0)
        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 animate-pulse">
            <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-xs font-bold text-rose-700">Ada {{ $counts['konseling'] }} permohonan yang membutuhkan KONSELING / KUNJUNGAN segera.</p>
        </div>
        @endif

        <!-- Grid Doa -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($requests as $req)
            <div class="bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all group flex flex-col relative overflow-hidden">
                
                <!-- Badge Kategori & Privasi -->
                <div class="flex justify-between items-start mb-6">
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest">
                        {{ $req->kategori }}
                    </span>
                    @if($req->is_private)
                        <span class="flex items-center gap-1 text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Rahasia
                        </span>
                    @else
                        <span class="flex items-center gap-1 text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            Publik (Warta)
                        </span>
                    @endif
                </div>

                <!-- Isi Doa -->
                <div class="mb-8 flex-1">
                    <h3 class="text-lg font-serif italic text-slate-800 leading-relaxed mb-4">
                        "{{ Str::limit($req->pokok_doa, 150) }}"
                    </h3>
                    @if(strlen($req->pokok_doa) > 150)
                        <button class="text-[10px] text-violet-600 font-bold uppercase hover:underline" onclick="alert('{{ js($req->pokok_doa) }}')">Baca Selengkapnya</button>
                    @endif
                </div>

                <!-- Info Pemohon -->
                <div class="bg-slate-50 rounded-3xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center font-black text-sm">
                            {{ substr($req->nama_pemohon ?? 'H', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $req->nama_pemohon ?? 'Hamba Tuhan' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $req->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($req->kontak)
                        <div class="mt-3 pt-3 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Kontak</span>
                            <a href="https://wa.me/{{ $req->kontak }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                {{ $req->kontak }}
                            </a>
                        </div>
                    @endif
                </div>
                
                @if($req->butuh_konseling)
                <div class="mb-6 px-4 py-2 bg-rose-50 border border-rose-100 rounded-xl text-center">
                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest">⚠️ Meminta Kunjungan</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-auto flex gap-2">
                    @if($req->status == 'baru')
                        <button wire:click="markAsPrayed({{ $req->id }})" class="flex-1 py-3 bg-violet-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-violet-700 transition shadow-lg shadow-violet-500/30">
                            Doakan
                        </button>
                    @elseif($req->status == 'didoakan')
                        <button wire:click="markAsDone({{ $req->id }})" class="flex-1 py-3 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition shadow-lg">
                            Selesai
                        </button>
                    @endif
                    
                    <button wire:click="delete({{ $req->id }})" wire:confirm="Hapus permohonan ini?" class="p-3 bg-slate-50 text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-slate-300 font-bold uppercase text-xs tracking-widest">
                Tidak ada permohonan doa pada status ini.
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $requests->links() }}</div>
    </div>
</div>