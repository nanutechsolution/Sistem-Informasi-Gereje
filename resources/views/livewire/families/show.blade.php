<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <a href="{{ route('families.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 hover:text-primary transition-colors mb-4 uppercase tracking-widest group">
                    <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Daftar Keluarga
                </a>
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-[32px] bg-slate-900 text-white flex items-center justify-center text-3xl font-black shadow-xl shadow-slate-900/20">
                        {{ substr($family->kepala_keluarga, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase italic">{{ $family->kepala_keluarga }}</h1>
                        <div class="mt-3 flex flex-wrap gap-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 italic border border-blue-200">NO. KK: {{ $family->nomor_kk }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-200 text-slate-600">{{ $family->refWilayah->nama ?? 'Tanpa Wilayah' }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">{{ $family->members->count() }} Jiwa</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('families.edit', $family) }}" class="px-8 py-4 bg-white border border-slate-200 rounded-[24px] font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all text-slate-600">Edit Data Keluarga</a>
                <a href="{{ route('members.create', $family) }}" class="inline-flex justify-center items-center px-3 py-1.5 font-black  text-xs bg-black border border-transparent  rounded-[24px] font-bold text-sm text-white hover:bg-primary shadow-lg  transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Anggota
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm overflow-hidden relative group">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Statistik PKS</h3>
                    <div class="space-y-6 relative z-10">
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase mb-1">Total Pelaksanaan</p>
                            <p class="text-2xl font-black text-slate-900">{{ $pksHistory->where('status', 'terlaksana')->count() }} <span class="text-xs font-bold text-slate-400 uppercase">Kali</span></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase mb-1">Total Persembahan</p>
                            <p class="text-xl font-black text-emerald-600">Rp {{ number_format($pksHistory->sum('nominal_persembahan'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] text-primary group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-xl relative overflow-hidden">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 relative z-10">Kewajiban Lelang</h3>
                    <div class="space-y-2 relative z-10">
                        <p class="text-[9px] font-black text-slate-400 uppercase leading-none">Total Sisa Piutang</p>
                        <h4 class="text-3xl font-black text-rose-400 italic tracking-tighter">
                            Rp {{ number_format($auctionHistory->sum('sisa_piutang'), 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-4 relative z-10 border-t border-white/10 pt-6">
                        <div>
                            <p class="text-[8px] font-black text-slate-500 uppercase">Item Lelang</p>
                            <p class="text-sm font-bold">{{ $auctionHistory->count() }} Nota</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-500 uppercase">Status Lunas</p>
                            <p class="text-sm font-bold text-emerald-400">{{ $auctionHistory->where('status_lunas', true)->count() }} Item</p>
                        </div>
                    </div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="flex gap-2 mb-8 bg-white p-2 rounded-[28px] border border-slate-200 shadow-sm w-full overflow-x-auto no-scrollbar">
                    @foreach([
                    'anggota' => 'Data Jiwa',
                    'tanggungan' => 'Iuran & Kewajiban',
                    'pks' => 'Riwayat PKS',
                    'lelang' => 'Nota Lelang'
                    ] as $key => $label)
                    <button wire:click="setTab('{{ $key }}')"
                        class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab == $key ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
                <div x-show="$wire.activeTab == 'anggota'" class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                    <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                <tr>
                                    <th class="px-8 py-6">Nama Jemaat</th>
                                    <th class="px-6 py-6">Hubungan</th>
                                    <th class="px-6 py-6 text-center">Status Rohani</th>
                                    <th class="px-8 py-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($family->members as $member)
                                <tr class="transition-colors group {{ $member->status_keanggotaan == 'meninggal' ? 'bg-slate-50/80 grayscale-[0.5]' : 'hover:bg-slate-50/50' }}">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-xl {{ $member->status_keanggotaan == 'meninggal' ? 'bg-slate-300' : ($member->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600') }} flex items-center justify-center font-black transition-transform group-hover:scale-110">
                                                    {{ substr($member->nama, 0, 1) }}
                                                </div>
                                                @if($member->status_keanggotaan == 'meninggal' || $member->hasEvent('MENINGGAL'))
                                                <div class="absolute -top-1 -right-1 bg-slate-900 text-white rounded-full p-0.5 shadow-sm border border-white">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M11 2h2v5h5v2h-5v13h-2v-13h-5v-2h5z" />
                                                    </svg>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-black text-slate-900 uppercase italic leading-none {{ $member->status_keanggotaan == 'meninggal' ? 'line-through text-slate-400' : '' }}">
                                                        {{ $member->nama }}
                                                    </p>
                                                    @if($member->status_keanggotaan == 'meninggal')
                                                    <span class="text-[8px] font-black bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded uppercase tracking-tighter">Wafat</span>
                                                    @endif
                                                </div>
                                                <p class="text-[9px] font-bold text-slate-400 mt-1.5 uppercase font-mono tracking-tighter">
                                                    {{ $member->nik ?? 'TIDAK ADA NIK' }}
                                                    @if($member->tanggal_lahir) • {{ \Carbon\Carbon::parse($member->tanggal_lahir)->age }} Thn @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-[9px] font-black uppercase text-slate-600">
                                        {{ $member->refHubunganKeluarga->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex justify-center gap-2">
                                            {{-- Baptis --}}
                                            <span title="Baptis" class="w-7 h-7 flex items-center justify-center rounded-lg border text-[9px] font-black transition-all 
                                                {{ $member->hasEvent('BAPTIS') ? 'bg-emerald-50 text-emerald-600 border-emerald-200 shadow-sm' : 'bg-slate-50 text-slate-200 border-slate-100' }}">B</span>

                                            {{-- Sidi --}}
                                            <span title="Sidi" class="w-7 h-7 flex items-center justify-center rounded-lg border text-[9px] font-black transition-all 
                                                {{ $member->hasEvent('SIDI') ? 'bg-emerald-50 text-emerald-600 border-emerald-200 shadow-sm' : 'bg-slate-50 text-slate-200 border-slate-100' }}">S</span>

                                            {{-- Nikah/Peneghuan --}}
                                            <span title="Nikah/Peneghuan" class="w-7 h-7 flex items-center justify-center rounded-lg border text-[9px] font-black transition-all 
                                                {{ ($member->hasEvent('NIKAH') || $member->hasEvent('PENEGUHAN')) ? 'bg-emerald-50 text-emerald-600 border-emerald-200 shadow-sm' : 'bg-slate-50 text-slate-200 border-slate-100' }}">N</span>

                                            {{-- Pindah --}}
                                            <span title="Mutasi Keluar" class="w-7 h-7 flex items-center justify-center rounded-lg border text-[9px] font-black transition-all 
                                                {{ ($member->hasEvent('MUTASI_KELUAR') || $member->status_keanggotaan == 'pindah') ? 'bg-amber-50 text-amber-600 border-amber-200 shadow-sm' : 'bg-slate-50 text-slate-200 border-slate-100' }}">P</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-primary hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="$wire.activeTab == 'tanggungan'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="space-y-6">
                        <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-3">
                                <span class="w-2 h-6 bg-primary rounded-full"></span> Kewajiban Keluarga
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($familyDues as $fd)
                                <div class="p-6 rounded-[32px] bg-slate-50 border border-slate-100 flex flex-col justify-between">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-[9px] font-black text-slate-400 uppercase mb-1">{{ $fd->fiscalYear->tahun }}</p>
                                            <h4 class="font-black text-slate-800 uppercase italic text-lg leading-tight">{{ $fd->dueType->nama }}</h4>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $fd->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $fd->status }}
                                        </span>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-[10px] font-bold uppercase text-slate-500">
                                            <span>Terbayar</span>
                                            <span>{{ $fd->dueType->unit_type == 'money' ? 'Rp '.number_format($fd->current_paid_nominal, 0, ',', '.') : $fd->current_paid_qty.' Unit' }}</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                            @php
                                            $percent = $fd->dueType->unit_type == 'money'
                                            ? ($fd->target_nominal > 0 ? ($fd->current_paid_nominal / $fd->target_nominal) * 100 : 0)
                                            : ($fd->target_qty > 0 ? ($fd->current_paid_qty / $fd->target_qty) * 100 : 0);
                                            @endphp
                                            <div class="h-full {{ $fd->status == 'lunas' ? 'bg-emerald-500' : 'bg-primary' }}" style="width: {{ min($percent, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-full py-10 text-center text-slate-300 font-bold uppercase text-[10px] italic">Tidak ada iuran keluarga aktif.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-3">
                                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span> Rekap Iuran Anggota
                            </h3>
                            <div class="space-y-3">
                                @forelse($individualDues as $id)
                                <div class="px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between group hover:bg-white hover:shadow-md transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="text-[10px] font-black text-slate-300 w-8">{{ $id->fiscalYear->tahun }}</div>
                                        <div>
                                            <p class="text-[10px] font-black text-primary uppercase leading-none mb-1">{{ $id->assignee->nama }}</p>
                                            <h5 class="font-bold text-slate-800 text-sm">{{ $id->dueType->nama }}</h5>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full {{ $id->status == 'lunas' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $id->status }}
                                    </span>
                                </div>
                                @empty
                                <p class="text-center py-10 text-slate-300 font-bold uppercase text-[10px] italic">Nihil.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="$wire.activeTab == 'pks'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm min-h-[400px]">
                        <h3 class="text-xl font-black text-slate-900 mb-10 italic uppercase leading-none">Garis Waktu Pelayanan (PKS)</h3>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-slate-100"></div>
                            @forelse($pksHistory as $pks)
                            <div class="mb-10 relative">
                                <div class="absolute -left-10 top-0 w-4 h-4 rounded-full border-4 border-white {{ $pks->status == 'terlaksana' ? 'bg-emerald-500 shadow-lg' : 'bg-amber-400' }}"></div>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 p-6 rounded-[32px] border border-slate-100">
                                    <div class="flex-1">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $pks->tanggal->isoFormat('dddd, D MMMM Y') }}</p>
                                        <h4 class="text-lg font-black text-slate-800 uppercase italic leading-tight">Firman Tuhan: {{ $pks->tema ?? 'Belum ditambahkan' }}</h4>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @forelse($pks->servants as $servant)
                                            <div class="flex items-center gap-1.5 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm">
                                                <span class="text-[8px] font-black text-primary uppercase tracking-tighter">{{ $servant->peran }}:</span>
                                                <span class="text-[9px] font-bold text-slate-700">{{ $servant->member->nama }}</span>
                                            </div>
                                            @empty
                                            <span class="text-[9px] font-bold text-slate-400 italic uppercase">Belum ada petugas terdaftar</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="text-left md:text-right border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-8 min-w-[150px]">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Persembahan</p>
                                        <p class="text-xl font-black text-slate-900 leading-none">Rp {{ number_format($pks->nominal_persembahan, 0, ',', '.') }}</p>
                                        <div class="mt-2">
                                            <span class="text-[8px] font-black uppercase {{ $pks->status_setoran == 'disetor' ? 'text-emerald-500' : 'text-rose-500' }}">
                                                ● {{ $pks->status_setoran == 'disetor' ? 'Telah Disetor' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="py-20 text-center text-slate-300 font-bold uppercase text-[10px] italic tracking-widest">Belum ada riwayat PKS.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="$wire.activeTab == 'lelang'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-xl font-black text-slate-900 italic uppercase leading-none">Nota Piutang Lelang</h3>
                            <div class="px-4 py-2 bg-rose-50 rounded-2xl border border-rose-100">
                                <p class="text-[8px] font-black text-rose-400 uppercase leading-none mb-1">Sisa Kolektif</p>
                                <p class="text-sm font-black text-rose-600 leading-none">Rp {{ number_format($auctionHistory->sum('sisa_piutang'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            @forelse($auctionHistory as $auc)
                            <div class="group p-6 rounded-[32px] border transition-all {{ $auc->status_lunas ? 'border-emerald-100 bg-emerald-50/20' : 'border-slate-100 bg-white hover:border-primary/20 hover:shadow-lg' }}">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[8px] font-black text-slate-400 uppercase">{{ $auc->event->nama_event ?? 'Event' }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $auc->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h4 class="text-lg font-black text-slate-800 uppercase italic leading-tight">{{ $auc->nama_barang }}</h4>
                                        <p class="text-[10px] font-bold text-slate-500 mt-2 uppercase tracking-tighter">Pemenang: <span class="text-slate-900">{{ $auc->pemenang_nama }}</span></p>
                                    </div>
                                    <div class="text-left md:text-right flex flex-col items-start md:items-end w-full md:w-auto border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-8">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Harga Jadi</p>
                                        <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">Rp {{ number_format($auc->harga_jadi, 0, ',', '.') }}</p>

                                        @if(!$auc->status_lunas)
                                        <div class="mt-4 w-full md:w-48 text-right">
                                            <div class="flex justify-between text-[9px] font-black uppercase text-rose-600 mb-1.5">
                                                <span>Sisa Piutang</span>
                                                <span>Rp {{ number_format($auc->sisa_piutang, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-rose-500" style="width: {{ ($auc->total_terbayar_cache / $auc->harga_jadi) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="mt-4 px-4 py-1.5 bg-emerald-500 text-white text-[9px] font-black uppercase rounded-full shadow-lg shadow-emerald-500/20 animate-pulse-slow">Lunas Terbayar</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-20 text-center text-slate-300 font-bold uppercase text-[10px] italic">Tidak ada catatan nota lelang untuk keluarga ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

</div>