<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 1. WELCOME HERO (PERSONALIZED) -->
        <div class="mb-10 bg-slate-900 rounded-[40px] p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-2">
                    <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black border border-white/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black italic tracking-tighter leading-tight">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-slate-400 mt-2 font-medium text-sm sm:text-base max-w-lg">Selamat datang di Sistem Informasi Gereja Kristen Sumba (SIG-GKS) Jemaat Reda Pada.</p>
            </div>

            <!-- Quick Actions Based on Role -->
            <div class="relative z-10 flex flex-wrap gap-3">
                @can('manage_finance')
                    <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="px-6 py-3 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Input Kas
                    </a>
                @endcan
                
                @can('input_pks')
                    <a href="{{ route('schedules.pks') }}" class="px-6 py-3 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Jadwal PKS
                    </a>
                @endcan

                @can('manage_database')
                    <a href="{{ route('members.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Data Jemaat
                    </a>
                @endcan
            </div>
            
            <!-- Dekorasi -->
            <div class="absolute right-0 top-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        </div>

        <!-- 2. WIDGET PRIBADI (UNTUK MAJELIS/PELAYAN) -->
        @if($myNextSchedule)
        <div class="mb-10 bg-gradient-to-r from-amber-400 to-orange-500 rounded-[32px] p-1 shadow-xl">
            <div class="bg-white rounded-[30px] p-6 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="flex items-center gap-6 relative z-10">
                    <div class="bg-amber-100 text-amber-600 p-4 rounded-2xl hidden sm:block">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Agenda Pelayanan Anda Berikutnya</p>
                        <h3 class="text-xl font-black text-slate-900 leading-tight">
                            {{ $myNextSchedule->tema ?? ($myNextSchedule->family ? 'PKS Kel. '.$myNextSchedule->family->kepala_keluarga : $myNextSchedule->type->nama) }}
                        </h3>
                        <p class="text-sm font-bold text-slate-500 mt-1">
                            {{ $myNextSchedule->tanggal->isoFormat('dddd, D MMMM Y') }} • Pukul {{ $myNextSchedule->jam_mulai->format('H:i') }}
                        </p>
                    </div>
                </div>
                <div class="relative z-10 w-full md:w-auto text-center md:text-right">
                    @php $myRole = $myNextSchedule->servants->where('member_id', auth()->user()->member_id)->first()->peran ?? 'Pelayan'; @endphp
                    <span class="inline-block px-4 py-1 rounded-lg bg-slate-900 text-white text-xs font-black uppercase tracking-widest mb-2 shadow-lg">
                        Tugas: {{ $myRole }}
                    </span>
                    
                    @if(str_contains(strtolower($myNextSchedule->type->nama), 'pks'))
                        <br>
                        <a href="{{ route('schedules.my') }}" class="inline-block mt-2 text-[10px] font-bold text-amber-600 hover:underline uppercase tracking-wide">
                            Input Kolekte &rarr;
                        </a>
                    @endif
                </div>
                <!-- Dekorasi -->
                <div class="absolute -right-6 -bottom-10 text-amber-50 opacity-50 rotate-12 pointer-events-none">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: DASHBOARD KEUANGAN & DATA -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- WIDGET BENDAHARA (KEUANGAN) -->
                @if(isset($financial))
                <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span> Posisi Keuangan
                        </h3>
                        @if($pendingPksCount > 0)
                            <a href="{{ route('schedules.pks.verify') }}" class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black uppercase tracking-wide animate-pulse">
                                {{ $pendingPksCount }} Setoran PKS Pending
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($financial['accounts'] as $acc)
                        <div class="p-5 rounded-3xl border border-slate-100 {{ $acc->nama == 'Kas Pembangunan' ? 'bg-amber-50' : 'bg-slate-50' }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $acc->nama == 'Kas Pembangunan' ? 'text-amber-600' : 'text-slate-400' }}">
                                    {{ $acc->nama }}
                                </span>
                                <svg class="w-4 h-4 {{ $acc->nama == 'Kas Pembangunan' ? 'text-amber-400' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-2xl font-black text-slate-900">Rp {{ number_format($acc->saldo_akhir, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- WIDGET SEKRETARIS (STATISTIK) -->
                @if(isset($stats))
                <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span> Statistik Jemaat
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div>
                            <span class="text-3xl font-black text-slate-900">{{ $stats['kk'] }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Kepala Keluarga</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-slate-900">{{ $stats['jiwa'] }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Total Jiwa</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-blue-600">{{ $stats['laki'] }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Laki-laki</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-pink-500">{{ $stats['perempuan'] }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Perempuan</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- AGENDA UMUM (SEMUA ROLE) -->
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-slate-300 rounded-full"></span> Agenda 2 Minggu Kedepan
                    </h3>
                    <div class="space-y-4">
                        @forelse($upcomingGeneralSchedules as $sch)
                        <div class="bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm flex items-center gap-5 hover:border-primary/30 transition-all group">
                            <div class="h-14 w-14 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-slate-600 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="text-lg font-black leading-none">{{ $sch->tanggal->format('d') }}</span>
                                <span class="text-[8px] font-bold uppercase">{{ $sch->tanggal->format('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary bg-blue-50 px-2 py-0.5 rounded">{{ $sch->type->nama }}</span>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $sch->jam_mulai->format('H:i') }} WITA</span>
                                </div>
                                <h4 class="font-bold text-slate-900 truncate text-sm">{{ $sch->tema ?? $sch->family->kepala_keluarga ?? 'Agenda Rutin' }}</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $sch->lokasi_display }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white p-6 rounded-[28px] border-2 border-dashed border-slate-100 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                            Belum ada agenda terjadwal.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: INFO LAIN -->
            <div class="lg:col-span-1 space-y-8">
                <!-- ULANG TAHUN -->
                <div class="bg-white p-6 rounded-[40px] border border-slate-100 shadow-sm h-full">
                    <h2 class="text-sm font-black text-slate-900 mb-6 italic flex items-center gap-2 uppercase tracking-widest">
                        <span class="text-xl">🎂</span> Sukacita Sepekan
                    </h2>
                    
                    <div class="space-y-3">
                        @forelse($birthdays as $bday)
                        <div class="flex items-center gap-3 p-3 hover:bg-yellow-50/50 rounded-2xl transition-colors border border-transparent hover:border-yellow-100">
                            <div class="h-10 w-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                                {{ $bday->tanggal_lahir->format('d') }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $bday->nama }}</p>
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">
                                    {{ $bday->tanggal_lahir->age + 1 }} Tahun • 
                                    @if($bday->family && $bday->family->refWilayah)
                                        {{ $bday->family->refWilayah->nama }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center text-slate-300 text-[10px] font-bold uppercase tracking-widest italic">
                            Tidak ada yang berulang tahun.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>