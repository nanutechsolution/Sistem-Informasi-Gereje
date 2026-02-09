<div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Navigasi -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <a href="{{ route('officers.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-primary transition-colors group mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Personil
                </a>
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-[28px] bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-blue-500/20">
                        {{ substr($officer->member->nama, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none">{{ $officer->member->nama }}</h1>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">
                                {{ $officer->position->nama }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $officer->status_kepegawaian == 'organik' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                Status: {{ $officer->status_kepegawaian }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('officers.edit', $officer) }}" class="flex-1 md:flex-none justify-center inline-flex items-center px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition-all">Edit Data</a>
                <button wire:click="toggleStatus" class="flex-1 md:flex-none justify-center inline-flex items-center px-6 py-3 {{ $officer->is_active ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-2xl text-sm font-bold shadow-sm transition-all">
                    {{ $officer->is_active ? 'Non-aktifkan' : 'Aktifkan Kembali' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI: STATUS & PAYROLL -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card Gaji (Sesuai RAPB 2026) -->
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-6">Take Home Pay (Netto)</p>
                        <h3 class="text-4xl font-black tracking-tighter">Rp {{ number_format($officer->net_salary, 0, ',', '.') }}</h3>

                        <div class="mt-8 space-y-4 border-t border-white/10 pt-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400 font-bold uppercase text-[9px]">Pemeliharaan/Pokok</span>
                                <span class="font-bold">Rp {{ number_format($officer->gaji_pokok, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-blue-400">
                                <span class="font-bold uppercase text-[9px]">Tunj. Perumahan</span>
                                <span class="font-bold">+ Rp {{ number_format($officer->tunjangan_perumahan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-rose-400">
                                <span class="font-bold uppercase text-[9px]">Iuran Pensiun</span>
                                <span class="font-bold">- Rp {{ number_format($officer->iuran_pensiun, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Dekorasi Background -->
                    <div class="absolute -right-4 -bottom-4 text-white opacity-5 rotate-12">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h14a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Masa Bakti (Penting untuk Vicaris) -->
                <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Masa Bakti & Legalitas</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-blue-50 text-primary rounded-2xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Mulai Bertugas</p>
                                <p class="font-bold text-slate-900">{{ $officer->tanggal_mulai?->format('d F Y') ?? '-' }}</p>
                            </div>
                        </div>

                        @if($officer->tanggal_selesai)
                        <div class="flex items-start gap-4">
                            <div class="p-3 {{ $officer->is_expired ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }} rounded-2xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Batas Akhir Tugas</p>
                                <p class="font-bold text-slate-900">{{ $officer->tanggal_selesai->format('d F Y') }}</p>
                                @if($officer->is_expired)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-rose-100 text-rose-700 text-[9px] font-black uppercase rounded">Masa Tugas Berakhir</span>
                                @else
                                <span class="text-[10px] text-amber-600 font-bold">Sisa {{ now()->diffInDays($officer->tanggal_selesai) }} hari lagi</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-4 pt-4 border-t border-slate-50">
                            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase">Nomor SK Terakhir</p>
                                <p class="font-bold text-slate-900 truncate">{{ $officer->nomor_sk ?? 'Belum ada SK' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: TIMELINE RIWAYAT (AUDIT TRAIL) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[40px] p-8 sm:p-12 border border-slate-100 shadow-sm min-h-full">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-xl font-black text-slate-900">Riwayat Pelayanan & Perubahan</h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Audit Trail 20 Thn</span>
                    </div>

                    <div class="relative">
                        <!-- Garis Tengah Timeline -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                        <div class="space-y-12">
                            @forelse($officer->histories as $history)
                            <div class="relative pl-12">
                                <!-- Dot Timeline -->
                                <div class="absolute left-2.5 top-0 h-3.5 w-3.5 rounded-full border-4 border-white bg-primary shadow-sm"></div>

                                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mb-2">
                                    <h4 class="font-black text-slate-900 uppercase tracking-tight">{{ $history->jenis_perubahan }}</h4>
                                    <span class="text-xs font-bold text-slate-400">{{ $history->tanggal_perubahan->format('d M Y') }}</span>
                                </div>
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <p class="text-sm text-slate-600 font-medium italic">"{{ $history->sk_pendukung ?? 'Sesuai kebijakan pimpinan Jemaat.' }}"</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Diinput Oleh: {{ $history->user->name }}</span>
                                        <button class="text-[10px] font-black text-primary hover:underline uppercase">Lihat Detail &raquo;</button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada riwayat perubahan yang tercatat.</p>
                            </div>
                            @endforelse

                            <!-- Item Awal: Peneguhan -->
                            <div class="relative pl-12">
                                <div class="absolute left-2.5 top-0 h-3.5 w-3.5 rounded-full border-4 border-white bg-slate-300 shadow-sm"></div>
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mb-2 opacity-50">
                                    <h4 class="font-black text-slate-900 uppercase tracking-tight">Pengukuhan Pertama</h4>
                                    <span class="text-xs font-bold text-slate-400">{{ $officer->tanggal_mulai?->format('d M Y') ?? 'Lama' }}</span>
                                </div>
                                <div class="p-4 border-2 border-dashed border-slate-100 rounded-2xl">
                                    <p class="text-xs text-slate-400 font-medium">Data awal pengerja masuk ke dalam sistem SIG-GKS Jemaat Reda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>