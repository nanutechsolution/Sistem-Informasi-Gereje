<div class="py-6 sm:py-12 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Top Navigation -->
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('members.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 uppercase tracking-widest hover:text-primary transition-colors group">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex gap-2">
                @if($member->is_active)
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-200">Aktif</span>
                @else
                    <span class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-200">{{ strtoupper($member->status_keanggotaan) }}</span>
                @endif
            </div>
        </div>

        <!-- Profile Header -->
        <div class="bg-white rounded-[40px] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[100px]"></div>
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
                <!-- Avatar -->
                <div class="w-32 h-32 rounded-[40px] bg-slate-900 flex items-center justify-center text-white text-4xl font-black shadow-lg shadow-slate-200">
                    {{ substr($member->churchPeople->full_name, 0, 1) }}
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Anggota Jemaat</p>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-tight mb-2">{{ $member->churchPeople->full_name }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            NIK: {{ $member->churchPeople->nik ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            KK: {{ $member->family->nomor_kk ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex bg-slate-200/50 p-1.5 rounded-[28px] mb-8 gap-1 border border-slate-200/30">
            <button wire:click="setTab('peristiwa')" class="flex-1 py-4 rounded-[22px] text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $activeTab === 'peristiwa' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                Peristiwa & Arsip
            </button>
            <button wire:click="setTab('iuran')" class="flex-1 py-4 rounded-[22px] text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $activeTab === 'iuran' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                Iuran & Setoran
            </button>
            <button wire:click="setTab('profil')" class="flex-1 py-4 rounded-[22px] text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $activeTab === 'profil' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                Detail Profil
            </button>
        </div>

        <!-- Tab Content -->
        <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
            
            @if($activeTab === 'peristiwa')
                <div class="space-y-6">
                    <div class="flex justify-between items-center px-2">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Riwayat Peristiwa</h2>
                        <button wire:click="$toggle('isAddingEvent')" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all">
                            {{ $isAddingEvent ? 'Batal' : '+ Peristiwa' }}
                        </button>
                    </div>

                    @if($isAddingEvent)
                        <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-slate-100 shadow-xl animate-in zoom-in-95">
                            <form wire:submit.prevent="saveEvent" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Jenis Peristiwa</label>
                                    <select wire:model.live="event_type_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                        <option value="">-- Pilih Peristiwa --</option>
                                        @foreach($eventTypes as $type) <option value="{{ $type->id }}">{{ $type->nama }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Tanggal</label>
                                    <input wire:model="tanggal" type="date" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Pelayan Firman / Pendeta</label>
                                    <input wire:model="pendeta" type="text" placeholder="Nama Pendeta..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">No. Surat / Akta</label>
                                    <input wire:model="nomor_surat" type="text" placeholder="No Register..." class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <button type="submit" wire:loading.attr="disabled" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-emerald-500 transition-all flex items-center justify-center gap-2">
                                        <span wire:loading.remove wire:target="saveEvent">Simpan Peristiwa</span>
                                        <span wire:loading wire:target="saveEvent">Memproses...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse($member->events as $event)
                            <div class="bg-white rounded-[28px] p-6 border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-black text-slate-800 uppercase tracking-tight">{{ $event->eventType->nama }}</h4>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($event->tanggal)->isoFormat('D MMMM Y') }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-500 italic">{{ $event->nomor_surat ?? 'Tanpa Nomor Surat' }} • Di {{ $event->lokasi ?? 'Gedung Gereja' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest bg-white rounded-[32px] border border-dashed border-slate-200">
                                Belum ada riwayat peristiwa.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if($activeTab === 'iuran')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Dues -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-primary rounded-full"></span> Iuran Perorangan
                        </h3>
                        @forelse($personalDues as $due)
                            <div class="bg-white rounded-[28px] p-6 border border-slate-100 shadow-sm">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="text-xs font-black text-slate-800 uppercase">{{ $due->dueType->nama }}</span>
                                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase {{ $due->status === 'lunas' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $due->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">Sisa Tagihan</p>
                                        <p class="text-lg font-black text-slate-900">Rp {{ number_format($due->sisa_nominal, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase">{{ $due->fiscalYear->tahun }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs font-bold text-slate-300 text-center py-6">Tidak ada iuran perorangan.</p>
                        @endforelse
                    </div>

                    <!-- Family Dues -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-400 rounded-full"></span> Iuran Keluarga
                        </h3>
                        @forelse($familyDues as $due)
                            <div class="bg-white rounded-[28px] p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-400/20"></div>
                                <div class="flex justify-between items-start mb-4">
                                    <span class="text-xs font-black text-slate-800 uppercase">{{ $due->dueType->nama }}</span>
                                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase {{ $due->status === 'lunas' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $due->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">Sisa Tagihan KK</p>
                                        <p class="text-lg font-black text-slate-900">Rp {{ number_format($due->sisa_nominal, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase">{{ $due->fiscalYear->tahun }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs font-bold text-slate-300 text-center py-6">Tidak ada iuran keluarga.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            @if($activeTab === 'profil')
                <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 p-8 sm:p-10 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] border-b border-slate-100 pb-3">Identitas Dasar</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-300 uppercase">Tempat Lahir</label>
                                    <p class="text-sm font-bold text-slate-700">{{ $member->churchPeople->place_of_birth }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-300 uppercase">Tanggal Lahir</label>
                                    <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($member->churchPeople->date_of_birth)->isoFormat('D MMMM Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-300 uppercase">Jenis Kelamin</label>
                                    <p class="text-sm font-bold text-slate-700">{{ $member->churchPeople->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-300 uppercase">Pekerjaan</label>
                                    <p class="text-sm font-bold text-slate-700">{{ $member->refPekerjaan->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] border-b border-slate-100 pb-3">Keluarga & Wilayah</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-[9px] font-black text-slate-300 uppercase">Wilayah Pelayanan</span>
                                    <span class="text-xs font-black text-primary uppercase">{{ $member->family->wilayah->nama ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-[9px] font-black text-slate-300 uppercase">Status di Keluarga</span>
                                    <span class="text-xs font-bold text-slate-700">{{ $member->refHubunganKeluarga->nama ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase block mb-1">Alamat Domisili</span>
                                    <p class="text-xs font-bold text-slate-700">{{ $member->churchPeople->address ?? $member->family->alamat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>