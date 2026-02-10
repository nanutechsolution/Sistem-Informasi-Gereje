<div class="py-6 sm:py-12 bg-slate-50 min-h-screen" 
     x-data="{ 
        showPay: @entangle('isPaymentModalOpen').live,
        formatRupiah(value) {
            if(!value) return '';
            let val = value.toString().replace(/\D/g, '');
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
     }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Navigasi Periode -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none italic uppercase">Penggajian Pengerja</h1>
                <p class="text-slate-500 mt-2 font-medium">Manajemen pembayaran rutin & insentif bulanan.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white p-2 rounded-[24px] shadow-sm border border-slate-200">
                <select wire:model.live="bulan" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer text-slate-700">
                    @foreach(range(1,12) as $m) 
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option> 
                    @endforeach
                </select>
                <div class="h-4 w-px bg-slate-300"></div>
                <select wire:model.live="tahun" class="border-none bg-transparent font-black text-sm focus:ring-0 cursor-pointer text-slate-700">
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                <button wire:click="generate" class="ml-2 px-5 py-2.5 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Generate Draft
                </button>
            </div>
        </div>

        <!-- Pemilihan Sumber Kas -->
        <div class="mb-8 p-6 bg-slate-900 rounded-[32px] text-white flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block font-mono italic">Rekening Sumber Dana:</span>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl"><svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <select wire:model="ref_account_id" class="bg-slate-800 border-none rounded-xl font-bold text-sm focus:ring-2 focus:ring-emerald-500 py-2.5 pl-4 pr-10 text-white cursor-pointer shadow-inner">
                        <option value="">-- Pilih Rekening Kas --</option>
                        @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                    </select>
                </div>
                @error('ref_account_id') <span class="text-rose-400 text-[10px] font-bold italic mt-1 block">Wajib pilih akun sumber dana!</span> @enderror
            </div>
            <div class="text-right relative z-10">
                <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Total Tagihan (Draft)</p>
                <p class="text-3xl font-black italic tracking-tighter">Rp {{ number_format($payrolls->sum('netto'), 0, ',', '.') }}</p>
            </div>
            <!-- Dekorasi -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2"></div>
        </div>

        <!-- Tabel Monitoring Payroll -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama Pengerja</th>
                        <th class="px-6 py-5 text-right">Pendapatan</th>
                        <th class="px-6 py-5 text-right">Iuran/Pot.</th>
                        <th class="px-6 py-5 text-right">THP (Netto)</th>
                        <th class="px-6 py-5 text-right">Status Bayar</th>
                        <th class="px-8 py-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $pay)
                    <tr class="hover:bg-blue-50/20 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs border border-slate-200 group-hover:bg-primary group-hover:text-white transition-colors">
                                    {{ substr($pay->officer->member->nama ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-900 text-base leading-tight uppercase italic">{{ $pay->officer->member->nama ?? 'DATA HILANG' }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $pay->officer->position->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-6 text-right font-bold text-emerald-600">
                            Rp {{ number_format($pay->gaji_pokok + $pay->tunjangan_perumahan + $pay->tunjangan_lain, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-6 py-6 text-right font-bold text-rose-500">
                            - Rp {{ number_format($pay->iuran_pensiun, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-6 py-6 text-right font-black text-slate-900 text-base italic">
                            Rp {{ number_format($pay->netto, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-6 text-right">
                            <div class="flex flex-col items-end">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $pay->status_bayar == 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($pay->status_bayar == 'cicil' ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $pay->status_bayar }}
                                </span>
                                @if($pay->status_bayar != 'lunas' && $pay->total_terbayar > 0)
                                    <span class="text-[8px] font-bold text-slate-400 mt-1">Masuk: Rp {{ number_format($pay->total_terbayar, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$pay->is_lunas)
                                    <button wire:click="payFull({{ $pay->id }})" wire:confirm="Proses pelunasan gaji sekaligus?" class="px-4 py-2 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all active:scale-95">LUNAS</button>
                                    <button wire:click="openPaymentModal({{ $pay->id }})" class="p-2 bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-slate-50 hover:text-primary transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                @else
                                    <a href="{{ route('payroll.slip', $pay->uuid) }}" target="_blank" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-primary transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Slip
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-400 italic uppercase">Belum Ada Draft Gaji</h3>
                            <p class="text-slate-400 text-xs font-medium mt-1">Klik tombol 'Generate Draft' untuk membuat daftar gaji bulan ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN CICILAN / PARSIAL -->
    <div x-show="showPay" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" @click="showPay = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-8 sm:p-10 shadow-2xl transition-all animate-in slide-in-from-bottom duration-300 overflow-hidden"
                 x-data="{ 
                    localNominal: @entangle('nominal_bayar'),
                    init() { this.$watch('localNominal', v => { if(this.$refs.payInput) this.$refs.payInput.value = this.formatRupiah(v); }); }
                 }">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                
                @if($selectedPayrollDetails)
                <div class="mb-10 text-center">
                    <div class="h-16 w-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center font-black text-2xl mx-auto mb-4 shadow-sm">
                        {{ substr($selectedPayrollDetails->officer->member->nama, 0, 1) }}
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Proses Bayar Gaji</p>
                    <h3 class="text-2xl font-black text-slate-900 italic leading-none uppercase">{{ $selectedPayrollDetails->officer->member->nama }}</h3>
                    <p class="text-xs font-bold text-slate-400 mt-2 italic">{{ $selectedPayrollDetails->nama_bulan }} {{ $selectedPayrollDetails->tahun }}</p>
                </div>
                @endif

                <form wire:submit="savePayment" class="space-y-8 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1 text-center">Input Nominal Uang Fisik</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-emerald-200 italic">Rp</span>
                            <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                                class="w-full bg-emerald-50 border-none rounded-[32px] p-8 text-center text-4xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200 shadow-inner transition-all">
                        </div>
                        @error('nominal_bayar') <span class="text-rose-500 text-[10px] font-bold mt-2 block text-center uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Catatan Internal</label>
                        <textarea wire:model="catatan_bayar" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-primary/20 shadow-sm" rows="2" placeholder="Misal: Panjar awal bulan, sisanya minggu depan..."></textarea>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" @click="showPay = false" class="flex-1 py-5 bg-slate-100 rounded-3xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-[2] py-5 bg-primary text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-800 transition transform active:scale-95 disabled:opacity-70">
                            <span wire:loading.remove>KONFIRMASI BAYAR</span>
                            <span wire:loading>Memproses Jurnal...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>