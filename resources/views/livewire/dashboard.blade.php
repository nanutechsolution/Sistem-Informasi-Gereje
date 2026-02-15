<div class="py-6 sm:py-10 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none">Dashboard</h1>
                <p class="text-slate-500 mt-2 font-medium text-xs uppercase tracking-widest border-l-4 border-primary pl-3">
                    Selamat Datang, {{ Auth::user()->name }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                <p class="text-[10px] text-primary font-bold uppercase tracking-tighter">SIG-GKS Jemaat Reda Pada</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Stats & Personal (Database & Dashboard Access) -->
            <div class="lg:col-span-2 space-y-8">
                
                @can('manage_database')
                <!-- Statistik Jemaat -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Kepala Keluarga</span>
                        <p class="text-3xl font-black text-slate-900 text-center leading-none">{{ number_format($stats['kk']) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Total Jiwa</span>
                        <p class="text-3xl font-black text-primary text-center leading-none">{{ number_format($stats['jiwa']) }}</p>
                    </div>
                    <div class="bg-slate-900 p-6 rounded-[32px] shadow-xl shadow-slate-200">
                        <div class="flex justify-around items-center">
                            <div class="text-center border-r border-slate-700 pr-4">
                                <span class="block text-[8px] font-black text-slate-500 uppercase mb-1">L</span>
                                <p class="text-xl font-black text-white leading-none">{{ $stats['laki'] }}</p>
                            </div>
                            <div class="text-center pl-4">
                                <span class="block text-[8px] font-black text-slate-500 uppercase mb-1">P</span>
                                <p class="text-xl font-black text-white leading-none">{{ $stats['perempuan'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex flex-col justify-center">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Surat (Bln Ini)</span>
                        <p class="text-2xl font-black text-slate-700 text-center leading-none">{{ $stats['letters_this_month'] }}</p>
                    </div>
                </div>
                @endcan

                <!-- Jadwal Saya & Agenda Mendatang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Tugas Terdekat -->
                    <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 text-slate-100 group-hover:text-primary transition-colors">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Tugas Mendatang Saya</h3>
                        
                        @if($myNextSchedule)
                            <div class="relative z-10">
                                <span class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">
                                    {{ $myNextSchedule->type->nama }}
                                </span>
                                <h4 class="text-2xl font-black text-slate-900 uppercase tracking-tighter leading-tight mb-2 truncate">
                                    {{ $myNextSchedule->family->kepala_keluarga ?? 'Gedung Gereja' }}
                                </h4>
                                <p class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($myNextSchedule->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </div>
                        @else
                            <div class="py-10 text-center">
                                <p class="text-slate-300 font-black text-xs uppercase tracking-widest">Belum ada tugas terjadwal</p>
                            </div>
                        @endif
                    </div>

                    <!-- Agenda Jemaat -->
                    <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Agenda Jemaat</h3>
                        <div class="space-y-4">
                            @forelse($upcomingGeneralSchedules as $sch)
                                <div class="flex items-center gap-4 group">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-50 flex flex-col items-center justify-center border border-slate-100 group-hover:bg-primary group-hover:text-white transition-all">
                                        <span class="text-xs font-black leading-none">{{ \Carbon\Carbon::parse($sch->tanggal)->format('d') }}</span>
                                        <span class="text-[8px] font-bold uppercase">{{ \Carbon\Carbon::parse($sch->tanggal)->format('M') }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-black text-slate-800 uppercase truncate leading-tight">{{ $sch->tema ?: $sch->type->nama }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $sch->wilayah->nama ?? 'Umum' }} • {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Tidak ada agenda dalam 2 minggu.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                @can('manage_finance')
                <!-- Ringkasan Saldo (Khusus Finance) -->
                <div class="bg-white rounded-[45px] p-8 shadow-sm border border-slate-100 overflow-hidden relative">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 rounded-full opacity-50"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 relative z-10">
                        <div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Kas & Bank Aktif</h3>
                            <div class="space-y-3">
                                @foreach($financial['accounts'] as $acc)
                                    <div class="flex justify-between gap-10 items-center border-b border-slate-50 pb-2">
                                        <span class="text-xs font-bold text-slate-600">{{ $acc->nama }}</span>
                                        <span class="font-mono text-sm font-black text-slate-900">Rp {{ number_format($acc->saldo_akhir, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-emerald-500 p-8 rounded-[35px] text-white w-full md:w-auto shadow-xl shadow-emerald-200">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70">Total Saldo Konsolidasi</span>
                            <p class="text-3xl font-black mt-2">Rp {{ number_format($financial['total_saldo'], 0, ',', '.') }}</p>
                            
                            @if($pendingPksCount > 0)
                                <div class="mt-4 flex items-center gap-2 bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm">
                                    <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ $pendingPksCount }} Kolekte PKS Pending Setor</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endcan

            </div>

            <!-- Kolom Kanan: Ulang Tahun & Alerts -->
            <div class="space-y-8">
                
                <!-- Ulang Tahun -->
                <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-4 -left-4 text-primary/5 transform -rotate-12">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 10-2 0v1a1 1 0 102 0zm6.364-1.636a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM15 16v-1a1 1 0 10-2 0v1a1 1 0 102 0z"/></svg>
                    </div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 relative z-10">Ulang Tahun Pekan Ini</h3>
                    <div class="space-y-6 relative z-10">
                        @forelse($birthdays as $bd)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-primary font-black text-xs border border-blue-100">
                                    {{ Carbon::parse($bd->churchPeople->date_of_birth)->format('d') }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-800 uppercase truncate leading-none mb-1">{{ $bd->churchPeople->full_name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $bd->family->wilayah->nama ?? 'Wilayah -' }} • {{ Carbon::parse($bd->churchPeople->date_of_birth)->age }} Thn</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-300 italic">Tidak ada hari istimewa pekan ini.</p>
                        @endforelse
                    </div>
                </div>

                @can('manage_settings')
                <!-- Quick Settings Link (Hanya Admin) -->
                <div class="bg-slate-900 rounded-[35px] p-6 text-white text-center">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50 mb-4">Akses Administrator</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('users.index') }}" class="p-3 bg-white/10 rounded-2xl hover:bg-white/20 transition-all text-[10px] font-bold uppercase tracking-widest">User</a>
                        <a href="#" class="p-3 bg-white/10 rounded-2xl hover:bg-white/20 transition-all text-[10px] font-bold uppercase tracking-widest">Sistem</a>
                    </div>
                </div>
                @endcan

            </div>
        </div>
    </div>
</div>