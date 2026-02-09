<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" x-data="{ formatRupiah(v) { return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 leading-none italic uppercase">Verifikasi Setoran PKS</h1>
            <p class="text-slate-500 mt-3 font-medium italic underline decoration-amber-400 decoration-2 underline-offset-4">Audit fisik uang persembahan rumah tangga yang dibawa Majelis/Pelayan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pendings as $item)
            <div class="bg-white rounded-[40px] p-8 border border-slate-200 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:rotate-12 transition-transform">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>

                <div class="mb-6">
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded-full tracking-widest">Pending Setoran</span>
                    <p class="text-[10px] font-bold text-slate-400 mt-4 uppercase tracking-widest">{{ $item->tanggal->isoFormat('dddd, D MMMM') }} • {{ $item->wilayah->nama ?? 'Wilayah' }}</p>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-6 italic uppercase leading-tight">
                    {{ $item->family->kepala_keluarga ?? 'Ibadah Rumah Tangga' }}
                </h3>

                <div class="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Laporan Kolekte</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter italic">Rp {{ number_format($item->nominal_persembahan, 0, ',', '.') }}</p>
                </div>

                <button wire:click="openModal({{ $item->id }})" class="w-full py-4 bg-emerald-500 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">
                    Terima Uang Fisik
                </button>
            </div>
            @empty
            <div class="col-span-full py-32 text-center">
                <div class="bg-white inline-block p-10 rounded-[50px] shadow-sm border border-slate-100">
                    <p class="text-slate-300 font-black italic uppercase tracking-[0.3em]">Tidak ada setoran tertunda</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $pendings->links() }}</div>
    </div>

    <!-- MODAL VERIFIKASI -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="$set('isModalOpen', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                <h3 class="text-2xl font-black text-slate-900 mb-8 italic uppercase tracking-tighter">Konfirmasi Serah Terima</h3>
                
                <form wire:submit="verify" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 ml-1">Uang Fisik Yang Diterima (Rp)</label>
                        <input type="text" wire:model="nominal_verifikasi" x-on:input="$el.value = formatRupiah($el.value)" class="w-full bg-emerald-50 border-none rounded-2xl p-6 text-3xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200 transition-all shadow-inner text-center">
                        @error('nominal_verifikasi') <span class="text-rose-600 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Masuk ke Dompet/Kas</label>
                            <select wire:model="ref_account_id" class="w-full bg-slate-50 border-none rounded-xl p-4 font-bold appearance-none cursor-pointer">
                                @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Pos Anggaran Warta</label>
                            <select wire:model="ref_budget_post_id" class="w-full bg-slate-50 border-none rounded-xl p-4 font-bold appearance-none cursor-pointer">
                                @foreach($budgetPosts as $pos) <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="$set('isModalOpen', false)" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-5 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase shadow-2xl transition transform active:scale-95">Verifikasi & Cetak Jurnal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>