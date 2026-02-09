<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 text-center sm:text-left">
            <h1 class="text-3xl font-black text-slate-900 italic uppercase leading-none">Jadwal Pelayanan Saya</h1>
            <p class="text-slate-500 mt-3 font-medium">Daftar tugas pelayanan Anda di Jemaat Reda Pada.</p>
        </div>

        <div class="space-y-6">
            @forelse($schedules as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-50 text-primary text-[10px] font-black uppercase rounded-full tracking-widest border border-blue-100">
                                {{ $item->type->nama }}
                            </span>
                            <span class="text-[10px] font-black text-slate-400 uppercase italic">
                                {{ $item->tanggal->isoFormat('dddd, D MMMM Y') }}
                            </span>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-900 mb-2 leading-tight uppercase italic">{{ $item->tema ?? 'Ibadah Rutin' }}</h3>
                        
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $item->lokasi_display }}
                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-[32px] p-6 text-white min-w-[200px] flex flex-col justify-center items-center text-center shadow-xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Peran Anda</p>
                        <p class="text-xl font-black text-accent italic uppercase">
                            {{ $item->servants->where('member_id', auth()->user()->member_id)->first()->peran ?? 'Pelayan' }}
                        </p>
                        <div class="mt-4 pt-4 border-t border-white/10 w-full text-[10px] font-bold">
                            Pukul {{ $item->jam_mulai->format('H:i') }} WITA
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-20 text-center bg-white rounded-[40px] border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest italic">Belum ada tugas pelayanan terjadwal.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $schedules->links() }}
        </div>
    </div>
</div>