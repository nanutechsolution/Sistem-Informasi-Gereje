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
        
        <!-- Header -->
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
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <button wire:click="generate" class="ml-2 px-5 py-2.5 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Generate Draft
                </button>
            </div>
        </div>

        <!-- Sumber Dana -->
        <div class="mb-8 p-6 bg-slate-900 rounded-[32px] text-white flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Sumber Dana Pembayaran</span>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl"><svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <select wire:model="ref_account_id" class="bg-slate-800 border-none rounded-xl font-bold text-sm focus:ring-2 focus:ring-emerald-500 py-2.5 pl-4 pr-10 text-white cursor-pointer shadow-inner">
                        <option value="">-- Pilih Rekening Kas --</option>
                        @foreach($accounts as $acc) <option value="{{ $acc->id }}">{{ $acc->nama }}</option> @endforeach
                    </select>
                </div>
                @error('ref_account_id') <span class="text-rose-400 text-[10px] font-bold italic mt-1 block">Wajib dipilih sebelum bayar!</span> @enderror
            </div>
            <div class="text-right relative z-10">
                <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Total Belanja Bulan Ini</p>
                <p class="text-3xl font-black">Rp {{ number_format($payrolls->sum('netto'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Gaji -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama Pengerja</th>
                        <th class="px-6 py-5 text-right">Total Pendapatan</th>
                        <th class="px-6 py-5 text-right">Potongan</th>
                        <th class="px-6 py-5 text-right">THP (Bersih)</th>
                        <th class="px-6 py-5 text-right">Sisa Piutang</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $pay)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-500 text-xs border border-slate-200">
                                    {{ substr($pay->officer->member->nama ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-900 text-base leading-tight">{{ $pay->officer->member->nama ?? 'Data Hilang' }}</div>
                                    <div class="text-[10px] font-bold text-primary uppercase tracking-tighter mt-1">{{ $pay->officer->position->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Kolom Pendapatan (Gabungan Gaji+Tunjangan) --}}
                        <td class="px-6 py-6 text-right font-bold text-emerald-600">
                            Rp {{ number_format($pay->gaji_pokok + $pay->tunjangan_perumahan + $pay->tunjangan_lain, 0, ',', '.') }}
                        </td>
                        
                        {{-- Kolom Potongan --}}
                        <td class="px-6 py-6 text-right font-bold text-rose-500">
                            Rp {{ number_format($pay->iuran_pensiun, 0, ',', '.') }}
                        </td>
                        
                        {{-- THP --}}
                        <td class="px-6 py-6 text-right font-black text-slate-900 text-base">
                            Rp {{ number_format($pay->netto, 0, ',', '.') }}
                        </td>

                        {{-- Status Bayar --}}
                        <td class="px-6 py-6 text-right">
                            @if($pay->sisa_gaji > 0)
                                <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight">Sisa: Rp {{ number_format($pay->sisa_gaji, 0, ',', '.') }}</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Lunas</span>
                            @endif
                        </td>
                        
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$pay->is_lunas)
                                    <button wire:click="payFull({{ $pay->id }})" wire:confirm="Bayar lunas?" class="px-4 py-2 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all">Lunas</button>
                                    <button wire:click="openPaymentModal({{ $pay->id }})" class="p-2 bg-white border border-slate-200 text-slate-500 rounded-xl hover:bg-slate-50 hover:text-primary transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                @else
                                    <a href="{{ route('payroll.slip', $pay->uuid) }}" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Slip
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">Belum ada draf gaji bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN CICILAN -->
    <div x-show="showPay" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showPay = false"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-t-[40px] sm:rounded-[40px] p-10 shadow-2xl transition-all">
                
                <!-- Info Personil -->
                @if($selectedPayrollDetails && $selectedPayrollDetails->officer)
                <div class="mb-8 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pembayaran Gaji Untuk</p>
                    <h3 class="text-2xl font-black text-slate-900 leading-none">{{ $selectedPayrollDetails->officer->member->nama }}</h3>
                </div>
                
                <!-- Rincian Komponen (Preview) -->
                <div class="mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="space-y-2">
                        @foreach($selectedPayrollDetails->officer->salaryComponents as $comp)
                        <div class="flex justify-between text-xs">
                            <span class="font-medium text-slate-500">{{ $comp->nama_komponen }}</span>
                            <span class="font-bold {{ $comp->jenis == 'penerimaan' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $comp->jenis == 'penerimaan' ? '+' : '-' }} Rp {{ number_format($comp->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                        <div class="border-t border-slate-200 mt-2 pt-2 flex justify-between font-black text-slate-900">
                            <span>TOTAL THP</span>
                            <span>Rp {{ number_format($selectedPayrollDetails->netto, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <form wire:submit="savePayment" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 text-center">Nominal Pembayaran</label>
                        <input x-ref="payInput" type="tel" x-on:input="localNominal = formatRupiah($el.value); $el.value = localNominal"
                            class="w-full bg-emerald-50 border-none rounded-[32px] p-6 text-center text-4xl font-black text-emerald-700 focus:ring-4 focus:ring-emerald-200 shadow-inner">
                        @error('nominal_bayar') <span class="text-rose-500 text-[10px] font-bold mt-1 block text-center">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Keterangan</label>
                        <textarea wire:model="catatan_bayar" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-4 focus:ring-primary/10" rows="2" placeholder="Contoh: Panjar awal bulan..."></textarea>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showPay = false" class="flex-1 py-4 bg-slate-100 rounded-2xl font-black text-xs uppercase text-slate-500">Batal</button>
                        <button type="submit" class="flex-[2] py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-emerald-600 transition transform active:scale-95">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>