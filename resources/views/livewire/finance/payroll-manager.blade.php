<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ showPay: @entangle('isPaymentModalOpen') }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none">Penggajian Pengerja</h1>
                <p class="text-slate-500 mt-2 font-medium">Kelola pembayaran pemeliharaan rutin dan insentif pengerja.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white p-2 rounded-3xl shadow-sm border border-slate-200">
                <select wire:model.live="bulan" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer">
                    @foreach(range(1,12) as $m) 
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option> 
                    @endforeach
                </select>
                <select wire:model.live="tahun" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <button wire:click="generate" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all active:scale-95">
                    Buat Draf
                </button>
            </div>
        </div>

        <!-- Akun Sumber Dana Default -->
        <div class="mb-8 p-6 bg-blue-900 rounded-[32px] text-white flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em]">Sumber Dana Utama</span>
                <div class="mt-1 flex items-center gap-3">
                    <select wire:model="ref_account_id" class="bg-blue-800/50 border-none rounded-xl font-bold text-sm focus:ring-blue-400 py-2 pl-3 pr-8">
                        <option value="">-- Pilih Rekening Kas --</option>
                        @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                    </select>
                    @error('ref_account_id') <span class="text-rose-400 text-[10px] font-bold italic">Wajib dipilih!</span> @enderror
                </div>
            </div>
            <div class="text-right relative z-10">
                <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Estimasi Belanja Pengerja</p>
                <p class="text-3xl font-black">Rp {{ number_format($payrolls->sum('netto'), 0, ',', '.') }}</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 -skew-x-12"></div>
        </div>

        <!-- Tabel Gaji -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama Pengerja / Jabatan</th>
                        <th class="px-6 py-5 text-right">Netto (THP)</th>
                        <th class="px-6 py-5 text-right">Telah Dibayar</th>
                        <th class="px-6 py-5 text-right">Sisa</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $pay)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-black text-slate-900 text-base">{{ $pay->officer?->member?->nama ?? 'Personil Tidak Ditemukan' }}</div>
                            <div class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ $pay->officer?->position?->nama ?? '-' }}</div>
                            @if($pay->officer && !$pay->officer->ref_budget_post_id)
                                <span class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter">⚠️ Pos Anggaran Belum Set</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right font-bold text-slate-400 italic">Rp {{ number_format($pay->netto, 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right text-emerald-600 font-black">Rp {{ number_format($pay->total_terbayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right">
                            @if($pay->sisa_gaji > 0)
                                <span class="text-rose-500 font-black">Rp {{ number_format($pay->sisa_gaji, 0, ',', '.') }}</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest">Lunas</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$pay->is_lunas)
                                    <button wire:click="payFull({{ $pay->id }})" wire:confirm="Lunaskan gaji pengerja ini?" class="px-4 py-2 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">Lunas</button>
                                    <button wire:click="openPaymentModal({{ $pay->id }})" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-200 transition-all">Cicil</button>
                                @else
                                    <a href="{{ route('payroll.slip', $pay->uuid) }}" target="_blank" class="p-2 text-slate-400 hover:text-primary transition-all" title="Cetak Slip">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 italic font-medium">Belum ada draf gaji bulan ini. Klik "Buat Draf" untuk menyisir pengerja aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN GAJI -->
    <div x-show="showPay" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPay = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 shadow-2xl transition-all">
                <h3 class="text-2xl font-black text-slate-900 mb-2 leading-none">Input Pembayaran</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">Pencatatan Realisasi Gaji Jemaat</p>
                
                <form wire:submit="savePayment" class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 text-center">Nominal Pembayaran (Rp)</label>
                        <input wire:model="nominal_bayar" type="number" class="w-full bg-emerald-50 border-none rounded-[32px] p-6 text-center text-3xl font-black text-emerald-700 focus:ring-2 focus:ring-emerald-200" placeholder="0">
                        @error('nominal_bayar') <span class="text-rose-500 text-[10px] font-bold mt-1 block text-center uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Keterangan Tambahan (Opsional)</label>
                        <textarea wire:model="catatan_bayar" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-primary/20" rows="2" placeholder="Misal: Panjar awal bulan..."></textarea>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showPay = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/30">Konfirmasi Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>