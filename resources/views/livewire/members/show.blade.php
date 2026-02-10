<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ showModal: @entangle('isAddingEvent') }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 1. HEADER PROFIL -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <a href="{{ route('members.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 hover:text-primary transition-colors mb-4 uppercase tracking-widest group">
                    <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    Daftar Jemaat
                </a>
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-[32px] bg-primary text-white flex items-center justify-center text-3xl font-black shadow-xl shadow-blue-500/20">
                        {{ substr($member->nama, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase italic">{{ $member->nama }}</h1>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 italic">
                                {{ $member->refHubunganKeluarga->nama ?? 'Anggota' }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">
                                {{ $member->family->refWilayah->nama ?? 'Tanpa Wilayah' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('members.edit', $member) }}" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">Edit Profil</a>
                <button @click="showModal = true" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-primary transition-all">Catat Peristiwa</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- 2. SIDEBAR INFO (KIRI) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Data Diri -->
                <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Informasi Dasar</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase">NIK / Identitas</p>
                            <p class="font-bold text-slate-800">{{ $member->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase">Pekerjaan</p>
                            <p class="font-bold text-slate-800">{{ $member->refPekerjaan->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase">Tempat, Tanggal Lahir</p>
                            <p class="font-bold text-slate-800">{{ $member->tempat_lahir ?? '-' }}, {{ $member->tanggal_lahir?->format('d M Y') ?? '-' }}</p>
                            @if($member->tanggal_lahir)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-blue-50 text-primary text-[10px] font-black rounded-lg">Usia: {{ $member->tanggal_lahir->age }} Tahun</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Rohani -->
                <div class="bg-slate-900 rounded-[40px] p-8 text-white shadow-xl relative overflow-hidden">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 relative z-10">Status Rohani</h3>
                    <div class="grid grid-cols-3 gap-4 relative z-10">
                        <div class="text-center">
                            <span class="block text-[8px] font-black text-slate-500 uppercase mb-2">Baptis</span>
                            <div class="h-10 w-10 mx-auto rounded-xl flex items-center justify-center {{ $member->status_baptis == 'Sudah' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div class="text-center border-x border-white/5">
                            <span class="block text-[8px] font-black text-slate-500 uppercase mb-2">Sidi</span>
                            <div class="h-10 w-10 mx-auto rounded-xl flex items-center justify-center {{ $member->status_sidi == 'Sudah' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="block text-[8px] font-black text-slate-500 uppercase mb-2">Nikah</span>
                            <div class="h-10 w-10 mx-auto rounded-xl flex items-center justify-center {{ $member->status_nikah == 'Sudah' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>
                    <svg class="absolute -right-10 -bottom-10 w-48 h-48 text-white/5 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.516L20.297 19H3.703L12 5.516z"/></svg>
                </div>
            </div>

            <!-- 3. TAB CONTENT (KANAN) -->
            <div class="lg:col-span-2">
                <!-- TAB NAVIGASI -->
                <div class="flex gap-2 mb-6 bg-white p-1.5 rounded-[24px] border border-slate-200 shadow-sm w-fit">
                    <button wire:click="setTab('peristiwa')" class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab == 'peristiwa' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                        Riwayat Peristiwa
                    </button>
                    <button wire:click="setTab('tanggungan')" class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab == 'tanggungan' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                        Riwayat Tanggungan
                    </button>
                </div>

                <!-- ISI TAB: PERISTIWA (TIMELINE) -->
                <div x-show="$wire.activeTab == 'peristiwa'" class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                    <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm min-h-[400px]">
                        <h3 class="text-xl font-black text-slate-900 italic uppercase mb-10">Garis Waktu Jemaat</h3>
                        
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-slate-100"></div>
                            
                            @forelse($member->events as $event)
                            <div class="mb-10 relative">
                                <div class="absolute -left-10 top-0 w-4 h-4 rounded-full border-4 border-white bg-primary shadow-sm"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $event->tanggal->isoFormat('D MMMM Y') }}</p>
                                <h4 class="text-lg font-black text-slate-800 leading-tight">{{ $event->eventType->nama }}</h4>
                                <p class="text-sm font-bold text-slate-500 mt-1 italic">
                                    {{ $event->lokasi ? 'Di '.$event->lokasi : '' }} 
                                    {{ $event->pendeta ? ' oleh '.$event->pendeta : '' }}
                                </p>
                                @if($event->nomor_surat)
                                    <span class="mt-2 inline-block px-2 py-1 bg-slate-50 border border-slate-100 rounded text-[9px] font-mono text-slate-400">No: {{ $event->nomor_surat }}</span>
                                @endif
                            </div>
                            @empty
                            <div class="py-20 text-center">
                                <p class="text-slate-300 font-black uppercase text-xs italic tracking-widest leading-relaxed">
                                    Belum ada catatan peristiwa rohani.<br>Klik "Catat Peristiwa" untuk menambah data.
                                </p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ISI TAB: TANGGUNGAN (BARU) -->
                <div x-show="$wire.activeTab == 'tanggungan'" class="animate-in fade-in slide-in-from-bottom-2 duration-500" x-cloak>
                    <div class="space-y-6">
                        
                        <!-- 1. TANGGUNGAN PERSONAL -->
                        <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span> Tanggungan Pribadi
                            </h3>
                            @forelse($personalDues as $pd)
                            <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Tahun {{ $pd->fiscalYear->tahun }}</p>
                                    <h4 class="text-lg font-black text-slate-800 italic uppercase leading-none">{{ $pd->dueType->nama }}</h4>
                                    <p class="text-xs font-bold text-slate-500 mt-2">
                                        @if($pd->dueType->unit_type == 'money')
                                            Target: Rp {{ number_format($pd->target_nominal, 0, ',', '.') }}
                                        @else
                                            Target: {{ $pd->target_qty }} {{ $pd->dueType->satuan_barang }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $pd->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $pd->status }}
                                    </span>
                                    <p class="text-xs font-black text-slate-900 mt-2">
                                        Masuk: {{ $pd->dueType->unit_type == 'money' ? 'Rp '.number_format($pd->current_paid_nominal, 0, ',', '.') : $pd->current_paid_qty.' '.$pd->dueType->satuan_barang }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-10 text-slate-300 font-bold text-xs italic uppercase">Tidak ada tanggungan pribadi.</p>
                            @endforelse
                        </div>

                        <!-- 2. TANGGUNGAN KELUARGA -->
                        <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span> Kolektif Keluarga (KK)
                            </h3>
                            @forelse($familyDues as $fd)
                            <div class="p-5 rounded-3xl bg-amber-50/50 border border-amber-100 mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h4 class="text-lg font-black text-slate-800 italic uppercase leading-none">{{ $fd->dueType->nama }}</h4>
                                    <p class="text-[9px] font-bold text-amber-600 mt-2 uppercase tracking-widest">Tanggungan Bersama Anggota KK</p>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $fd->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $fd->status }}
                                    </span>
                                    <p class="text-xs font-black text-slate-900 mt-2">
                                        Terbayar: {{ $fd->dueType->unit_type == 'money' ? 'Rp '.number_format($fd->current_paid_nominal, 0, ',', '.') : $fd->current_paid_qty.' '.$fd->dueType->satuan_barang }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-10 text-slate-300 font-bold text-xs italic uppercase">Tidak ada tanggungan keluarga.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MODAL CATAT PERISTIWA (Existing) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="showModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-[40px] p-10 shadow-2xl animate-in zoom-in-95 duration-300">
                    <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter text-center leading-none">Catat Peristiwa</h3>
                    <form wire:submit="saveEvent" class="space-y-6 text-left">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jenis Peristiwa</label>
                            <select wire:model="event_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($eventTypes as $et) <option value="{{ $et->id }}">{{ $et->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lokasi / Gereja</label>
                            <input wire:model="lokasi" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm" placeholder="Contoh: GKS Reda Pada">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pendeta</label>
                            <input wire:model="pendeta" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                        </div>
                        <button type="submit" class="w-full py-5 bg-primary text-white rounded-3xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-blue-500/30 hover:scale-105 transition-all">Simpan Riwayat</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>