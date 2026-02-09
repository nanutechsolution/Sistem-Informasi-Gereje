<div class="py-6 sm:py-12 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER & NAVIGASI -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <a href="{{ route('officers.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-primary transition-colors group mb-4 uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Daftar Personil
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
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $officer->status_kepegawaian == 'organik' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $officer->status_kepegawaian }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $officer->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $officer->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('officers.edit', $officer) }}" class="flex-1 md:flex-none justify-center inline-flex items-center px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition-all">
                    Edit Data
                </a>
                <button wire:click="toggleStatus" class="flex-1 md:flex-none justify-center inline-flex items-center px-6 py-3 {{ $officer->is_active ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} rounded-2xl text-sm font-bold shadow-sm transition-all">
                    {{ $officer->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- KOLOM KIRI: STRUKTUR GAJI FLEKSIBEL -->
            <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-8">

                <!-- Card Take Home Pay -->
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">Estimasi Terima Bersih</p>
                        <!-- Menggunakan accessor model getNetSalaryAttribute -->
                        <h3 class="text-4xl font-black tracking-tighter">Rp {{ number_format($officer->net_salary, 0, ',', '.') }}</h3>

                        <div class="mt-8 pt-6 border-t border-white/10 space-y-4">

                            <!-- LOOPING KOMPONEN PENERIMAAN -->
                            @foreach($officer->salaryComponents->where('jenis', 'penerimaan') as $comp)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-400 font-bold uppercase text-[10px]">{{ $comp->nama_komponen }}</span>
                                    <span class="font-bold text-emerald-400">+ Rp {{ number_format($comp->nominal, 0, ',', '.') }}</span>
                                </div>
                                @if($comp->budgetPost)
                                <div class="flex items-center gap-1.5 text-[9px] text-slate-500 font-medium bg-white/5 px-2 py-1 rounded-lg w-fit">
                                    <span class="opacity-60">Pos:</span> {{ $comp->budgetPost->kode }}
                                </div>
                                @endif
                            </div>
                            @endforeach

                            <!-- LOOPING KOMPONEN POTONGAN -->
                            @if($officer->salaryComponents->where('jenis', 'potongan')->count() > 0)
                            <div class="pt-4 mt-4 border-t border-dashed border-white/10">
                                @foreach($officer->salaryComponents->where('jenis', 'potongan') as $comp)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-slate-400 font-bold uppercase text-[10px]">{{ $comp->nama_komponen }}</span>
                                        <span class="font-bold text-rose-400">- Rp {{ number_format($comp->nominal, 0, ',', '.') }}</span>
                                    </div>
                                    @if($comp->budgetPost)
                                    <div class="flex items-center gap-1.5 text-[9px] text-slate-500 font-medium bg-white/5 px-2 py-1 rounded-lg w-fit">
                                        <span class="opacity-60">Pos:</span> {{ $comp->budgetPost->kode }}
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Dekorasi -->
                    <div class="absolute -right-6 -bottom-10 text-white opacity-5 rotate-12 pointer-events-none">
                        <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h14a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Info Legalitas -->
                <div class="bg-white rounded-[32px] p-8 border border-slate-200 shadow-sm">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Masa Bakti & SK</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase">Nomor SK</p>
                                <p class="font-bold text-slate-900 truncate">{{ $officer->nomor_sk ?? 'Belum ada SK' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-blue-50 text-primary rounded-2xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Periode Tugas</p>
                                <p class="font-bold text-slate-900">{{ $officer->tanggal_mulai?->format('d M Y') ?? '-' }}</p>
                                @if($officer->tanggal_selesai)
                                <p class="text-xs text-slate-500 mt-0.5">s/d {{ $officer->tanggal_selesai->format('d M Y') }}</p>
                                @if($officer->is_expired)
                                <span class="inline-block mt-2 px-2 py-0.5 bg-rose-100 text-rose-700 text-[9px] font-black uppercase rounded">Masa Tugas Berakhir</span>
                                @endif
                                @else
                                <p class="text-xs text-emerald-600 font-bold mt-0.5 italic">Seterusnya (Tetap)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: RIWAYAT PERUBAHAN -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[40px] p-8 sm:p-10 border border-slate-200 shadow-sm min-h-full">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-xl font-black text-slate-900 italic uppercase">Riwayat Perubahan</h3>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">Audit Trail</span>
                    </div>

                    <div class="relative">
                        <!-- Garis Tengah -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                        <div class="space-y-10">
                            @forelse($officer->histories as $history)
                            <div class="relative pl-12 group">
                                <!-- Dot Timeline -->
                                <div class="absolute left-2.5 top-1.5 h-3.5 w-3.5 rounded-full border-4 border-white bg-slate-300 group-hover:bg-primary transition-colors shadow-sm z-10"></div>

                                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 mb-2">
                                    <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm">{{ $history->jenis_perubahan }}</h4>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $history->tanggal_perubahan->format('d M Y, H:i') }}</span>
                                </div>

                                <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-100 group-hover:border-slate-200 transition-colors">
                                    @if(isset($history->data_baru))
                                    @php $changes = is_string($history->data_baru) ? json_decode($history->data_baru, true) : $history->data_baru; @endphp
                                    @if(is_array($changes))
                                    <ul class="mb-3 space-y-1">
                                        @foreach($changes as $key => $val)
                                        <li class="text-xs text-slate-600">
                                            <span class="font-bold capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                            {{ is_numeric($val) ? number_format($val, 0, ',', '.') : $val }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    @endif

                                    <p class="text-xs text-slate-500 font-medium italic">Dasar Perubahan: "{{ $history->sk_pendukung ?? 'Update Sistem Rutin' }}"</p>

                                    <div class="mt-3 flex items-center gap-2 pt-3 border-t border-slate-200/50">
                                        <div class="h-5 w-5 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-black text-slate-500">
                                            {{ substr($history->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Oleh: {{ $history->user->name ?? 'Admin' }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-16 text-center">
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada riwayat perubahan data.</p>
                            </div>
                            @endforelse

                            <!-- Start Point -->
                            <div class="relative pl-12 opacity-60">
                                <div class="absolute left-2.5 top-1.5 h-3.5 w-3.5 rounded-full border-4 border-white bg-emerald-400 shadow-sm z-10"></div>
                                <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm">Terdaftar di Sistem</h4>
                                <span class="text-[10px] font-bold text-slate-400">{{ $officer->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>