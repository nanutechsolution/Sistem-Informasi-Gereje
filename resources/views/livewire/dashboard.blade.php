<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- SECTION 1: KEUANGAN (FINANCIAL OVERVIEW) -->
        <div class="mb-10">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Ringkasan Keuangan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Aset -->
                <div class="bg-gradient-to-br from-primary to-blue-900 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/30 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-200 text-sm font-medium uppercase tracking-wider">Total Kas & Bank</p>
                        <h3 class="text-3xl font-extrabold mt-2">Rp {{ number_format($totalUang, 0, ',', '.') }}</h3>
                        <div class="mt-6">
                            <a href="{{ route('transactions.create', ['jenis' => 'masuk']) }}" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-lg text-xs font-bold transition">
                                + Input Pemasukan
                            </a>
                        </div>
                    </div>
                    <!-- Dekorasi -->
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h14a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

                <!-- Daftar Akun (Cards) -->
                @foreach($accounts as $acc)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:border-blue-300 transition-colors">
                    <div>
                        <div class="flex justify-between items-start">
                            <div class="p-2 rounded-lg {{ $acc->jenis == 'bank' ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600' }}">
                                @if($acc->jenis == 'bank')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-10h2m-2 4h2m-4-8h2m-2-4h2m-4 8h2m-2 4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2-4h2m-2 4h2m-2 4h2"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @endif
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 uppercase">{{ $acc->jenis == 'kas_tunai' ? 'Tunai' : 'Bank' }}</span>
                        </div>
                        <h4 class="mt-4 text-gray-500 text-sm font-medium">{{ $acc->nama }}</h4>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($acc->saldo_akhir, 0, ',', '.') }}</p>
                        @if($acc->nomor_rekening)
                            <p class="text-xs text-gray-400 mt-1 font-mono">{{ $acc->nomor_rekening }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SECTION 2: DATA JEMAAT -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Statistik Jemaat
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total KK -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 text-primary rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keluarga</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalKK }} <span class="text-sm text-gray-400 font-medium">KK</span></p>
                    </div>
                </div>

                <!-- Total Jiwa -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Jiwa</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalJiwa }} <span class="text-sm text-gray-400 font-medium">Orang</span></p>
                    </div>
                </div>

                <!-- Laki-laki -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laki-laki</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalLaki }}</p>
                    </div>
                </div>

                <!-- Perempuan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perempuan</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalPerempuan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: ULANG TAHUN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <span>🎂</span> Ulang Tahun Minggu Ini
                </h3>
            </div>
            <div class="p-4">
                @if($birthdays->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach($birthdays as $bday)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-yellow-50/50 border border-yellow-100">
                            <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-sm font-bold text-yellow-600 shadow-sm border border-yellow-100">
                                {{ substr($bday->nama, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 truncate w-32">{{ Str::limit($bday->nama, 15) }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $bday->tanggal_lahir->format('d M') }} ({{ $bday->tanggal_lahir->age + 1 }} thn)
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">Tidak ada yang ulang tahun dalam 7 hari ke depan.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
