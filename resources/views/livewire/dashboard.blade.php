<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- WELCOME BANNER -->
        <div class="mb-10 bg-slate-900 rounded-[40px] p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Selamat Datang</p>
                <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter">{{ auth()->user()->name }}</h1>
                <p class="text-slate-400 mt-2 font-medium">Sistem Informasi Gereja Kristen Sumba (SIG-GKS).</p>
            </div>
            <div class="relative z-10 flex gap-3">
                <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="px-6 py-3 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all shadow-lg">
                    Input Kas
                </a>
                <a href="{{ route('schedules.pks.verify') }}" class="px-6 py-3 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg border border-white/20">
                    Verifikasi PKS
                </a>
            </div>
            
            <!-- Dekorasi -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -translate-y-10 translate-x-10 pointer-events-none"></div>
        </div>

        <!-- SECTION 1: KEUANGAN -->
        <div class="mb-10">
            <h2 class="text-xl font-black text-slate-900 mb-6 italic flex items-center gap-2">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                Ringkasan Keuangan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Aset -->
                <div class="bg-emerald-600 p-6 rounded-[32px] text-white shadow-lg shadow-emerald-500/20 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-2">Total Saldo Kas</p>
                        <h3 class="text-2xl font-black tracking-tighter">Rp {{ number_format($totalUang, 0, ',', '.') }}</h3>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-emerald-400 opacity-20 rotate-12 group-hover:scale-110 transition-transform">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h14a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

                <!-- Loop Akun -->
                @foreach($accounts as $acc)
                <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl transition-all relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl {{ $acc->jenis == 'bank' ? 'bg-indigo-50 text-indigo-600' : ($acc->nama == 'Kas Pembangunan' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600') }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $acc->jenis == 'bank' ? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2 4h2' : 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z' }}"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $acc->nama }}</p>
                    <p class="text-xl font-black text-slate-900">Rp {{ number_format($acc->saldo_akhir, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- SECTION 2: AGENDA (KIRI) -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="text-xl font-black text-slate-900 mb-6 italic flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        Agenda Pekan Ini
                    </h2>
                    <div class="space-y-4">
                        @forelse($schedules as $sch)
                        <div class="bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm flex items-center gap-5 hover:border-primary/30 transition-all">
                            <div class="h-16 w-16 bg-slate-100 rounded-2xl flex flex-col items-center justify-center text-slate-600">
                                <span class="text-xl font-black leading-none">{{ $sch->tanggal->format('d') }}</span>
                                <span class="text-[9px] font-bold uppercase">{{ $sch->tanggal->format('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary">{{ $sch->type->nama }}</span>
                                    <span class="text-[9px] font-bold text-slate-300">•</span>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $sch->jam_mulai->format('H:i') }} WITA</span>
                                </div>
                                <h4 class="font-bold text-slate-900 truncate">{{ $sch->tema ?? $sch->family->kepala_keluarga ?? 'Agenda Rutin' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 truncate">{{ $sch->lokasi_display }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white p-8 rounded-[28px] border border-dashed border-slate-200 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                            Tidak ada agenda dalam 7 hari ke depan.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- SECTION 3: STATISTIK JEMAAT -->
                <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6">Statistik Jemaat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div>
                            <span class="text-3xl font-black text-slate-900">{{ $totalKK }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Kepala Keluarga</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-slate-900">{{ $totalJiwa }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Total Jiwa</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-blue-600">{{ $totalLaki }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Laki-laki</span>
                        </div>
                        <div>
                            <span class="text-3xl font-black text-pink-500">{{ $totalPerempuan }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-1">Perempuan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: ULANG TAHUN (KANAN) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-[40px] border border-slate-100 shadow-sm h-full">
                    <h2 class="text-lg font-black text-slate-900 mb-6 italic flex items-center gap-2">
                        <span class="text-xl">🎂</span> Sukacita Minggu Ini
                    </h2>
                    
                    <div class="space-y-4">
                        @forelse($birthdays as $bday)
                        <div class="flex items-center gap-3 p-3 hover:bg-yellow-50/50 rounded-2xl transition-colors">
                            <div class="h-10 w-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center font-bold text-xs">
                                {{ $bday->tanggal_lahir->format('d') }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $bday->nama }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ $bday->tanggal_lahir->age + 1 }} Tahun • {{ $bday->family->wilayah_id ? 'Wil. '.$bday->family->wilayah_id : '-' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center text-slate-300 text-xs font-bold uppercase tracking-widest italic">
                            Tidak ada yang berulang tahun.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>