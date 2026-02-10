<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <a href="{{ route('families.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 hover:text-primary transition-colors mb-4 uppercase tracking-widest group">
                    <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    Daftar Keluarga
                </a>
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-[32px] bg-slate-900 text-white flex items-center justify-center text-3xl font-black shadow-xl">
                        {{ substr($family->kepala_keluarga, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase italic">{{ $family->kepala_keluarga }}</h1>
                        <div class="mt-3 flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 italic">NO. KK: {{ $family->nomor_kk }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">{{ $family->refWilayah->nama ?? 'Tanpa Wilayah' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('families.edit', $family) }}" class="px-8 py-4 bg-white border border-slate-200 rounded-[24px] font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">Edit KK</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- SIDEBAR STATS (KIRI) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Info PKS -->
                <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm overflow-hidden relative">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Statistik PKS</h3>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-bold text-slate-500">Terlaksana</span>
                            <span class="text-xl font-black text-slate-900">{{ $pksHistory->where('status', 'terlaksana')->count() }}x</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-bold text-slate-500">Total Persembahan</span>
                            <span class="text-lg font-black text-emerald-600">Rp {{ number_format($pksHistory->sum('nominal_persembahan'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 text-primary"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg></div>
                </div>

                <!-- Info Lelang -->
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-xl relative overflow-hidden">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">Kewajiban Lelang</h3>
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-500 uppercase">Sisa Piutang</p>
                        <h4 class="text-3xl font-black text-rose-400 italic">Rp {{ number_format($auctionHistory->sum('harga_jadi') - $auctionHistory->sum('total_terbayar_cache'), 0, ',', '.') }}</h4>
                    </div>
                    <div class="mt-6 flex justify-between text-[10px] font-bold text-slate-400 border-t border-white/10 pt-4">
                        <span>Nota: {{ $auctionHistory->count() }} Item</span>
                        <span class="text-emerald-400">Lunas: {{ $auctionHistory->where('status_lunas', true)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT (KANAN) -->
            <div class="lg:col-span-3">
                <!-- Tab Switcher (Horizontal Scroll on Mobile) -->
                <div class="flex gap-2 mb-8 bg-white p-2 rounded-[28px] border border-slate-200 shadow-sm w-full overflow-x-auto no-scrollbar">
                    @foreach(['anggota' => 'Jiwa', 'tanggungan' => 'Iuran', 'pks' => 'Riwayat PKS', 'lelang' => 'Nota Lelang'] as $key => $label)
                    <button wire:click="setTab('{{ $key }}')" class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab == $key ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                <!-- TAB: ANGGOTA (JIWA) -->
                <div x-show="$wire.activeTab == 'anggota'" class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                    <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-8 py-6">Nama Jemaat</th>
                                    <th class="px-6 py-6">Status Gerejawi</th>
                                    <th class="px-8 py-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($family->members as $member)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6 flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center font-black">{{ substr($member->nama, 0, 1) }}</div>
                                        <div>
                                            <p class="font-black text-slate-900 uppercase italic leading-none">{{ $member->nama }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ $member->refHubunganKeluarga->nama ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex gap-1">
                                            <span class="text-[8px] px-1.5 py-0.5 rounded border {{ $member->status_baptis == 'Sudah' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-300' }} font-black uppercase">B</span>
                                            <span class="text-[8px] px-1.5 py-0.5 rounded border {{ $member->status_sidi == 'Sudah' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-300' }} font-black uppercase">S</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('members.show', $member) }}" class="p-2 bg-slate-100 text-slate-400 rounded-xl hover:text-primary transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB: RIWAYAT PKS (TRANSPARANSI PELAYANAN) -->
                <div x-show="$wire.activeTab == 'pks'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                        <h3 class="text-xl font-black text-slate-900 mb-8 italic uppercase leading-none">Jadwal & Realisasi PKS</h3>
                        <div class="space-y-4">
                            @forelse($pksHistory as $pks)
                            <div class="p-6 rounded-3xl border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-slate-50 transition-colors">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $pks->tanggal->isoFormat('D MMMM Y') }}</p>
                                    <h4 class="font-black text-slate-800 uppercase italic mt-1">{{ $pks->tema ?? 'Ibadah Rumah Tangga' }}</h4>
                                    <p class="text-xs font-bold text-slate-400 mt-2 italic">Status: <span class="uppercase {{ $pks->status == 'terlaksana' ? 'text-emerald-500' : 'text-amber-500' }}">{{ $pks->status }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Persembahan</p>
                                    <p class="text-xl font-black text-slate-900">Rp {{ number_format($pks->nominal_persembahan, 0, ',', '.') }}</p>
                                    <span class="text-[9px] font-bold {{ $pks->status_setoran == 'disetor' ? 'text-emerald-500' : 'text-rose-400' }} uppercase">
                                        {{ $pks->status_setoran == 'disetor' ? 'Telah Verifikasi' : 'Pending di Majelis' }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-20 text-slate-300 font-bold uppercase text-[10px] italic">Belum ada riwayat pelaksanaan PKS.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TAB: NOTA LELANG (TRANSPARANSI FINANSIAL) -->
                <div x-show="$wire.activeTab == 'lelang'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                        <h3 class="text-xl font-black text-slate-900 mb-8 italic uppercase leading-none">Daftar Nota Lelang</h3>
                        <div class="space-y-4">
                            @forelse($auctionHistory as $auc)
                            <div class="p-6 rounded-3xl border {{ $auc->status_lunas ? 'border-emerald-100 bg-emerald-50/30' : 'border-rose-100 bg-rose-50/30' }} flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[9px] font-black text-primary uppercase bg-white px-2 py-0.5 rounded shadow-sm">{{ $auc->event->nama_event }}</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $auc->created_at->format('d/m/y') }}</span>
                                    </div>
                                    <h4 class="text-lg font-black text-slate-800 uppercase italic leading-tight">{{ $auc->nama_barang }}</h4>
                                    <p class="text-[10px] font-bold text-slate-500 mt-2 uppercase">Pemenang: {{ $auc->pemenang_nama }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Nilai Nota</p>
                                    <p class="text-xl font-black text-slate-900 leading-none">Rp {{ number_format($auc->harga_jadi, 0, ',', '.') }}</p>
                                    
                                    @if(!$auc->status_lunas)
                                        <div class="mt-3">
                                            <span class="text-[10px] font-black text-rose-600 uppercase">Sisa: Rp {{ number_format($auc->sisa_piutang, 0, ',', '.') }}</span>
                                            <div class="w-32 h-1.5 bg-slate-200 rounded-full mt-1 ml-auto overflow-hidden">
                                                <div class="h-full bg-rose-500" style="width: {{ ($auc->total_terbayar_cache / $auc->harga_jadi) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="mt-2 inline-block px-3 py-1 bg-emerald-500 text-white text-[9px] font-black uppercase rounded-full shadow-lg shadow-emerald-500/20">Lunas</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-20 text-slate-300 font-bold uppercase text-[10px] italic">Belum ada nota lelang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TAB: TANGGUNGAN (Existing) -->
                <div x-show="$wire.activeTab == 'tanggungan'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    {{-- Konten Iuran sama seperti sebelumnya --}}
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm mb-6">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6">Iuran Keluarga</h3>
                        @forelse($familyDues as $fd)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-2 flex justify-between items-center">
                                <span class="font-bold text-slate-700">{{ $fd->dueType->nama }} ({{ $fd->fiscalYear->tahun }})</span>
                                <span class="px-2 py-1 rounded text-[9px] font-black uppercase {{ $fd->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $fd->status }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Nihil.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>