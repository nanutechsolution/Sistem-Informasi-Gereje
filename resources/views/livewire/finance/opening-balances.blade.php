<div class="py-6 sm:py-12 bg-gray-50 min-h-screen"
    x-data="{
        formatRupiah(value) {
            if(!value) return '0';
            let number = value.toString().replace(/[^0-9]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">Saldo Awal</h1>
                <p class="text-gray-500 mt-1">Atur sisa uang (Carry Over) dari tahun sebelumnya.</p>
            </div>
            
            <!-- Pilihan Tahun -->
            <div class="w-full sm:w-48 bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                <select wire:model.live="fiscalYearId" class="w-full bg-transparent border-none text-gray-700 font-bold focus:ring-0 cursor-pointer py-2 pl-3 pr-8 text-sm">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($fiscalYears as $year)
                        <option value="{{ $year->id }}">{{ $year->tahun }} {{ $year->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($fiscalYearId)
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form wire:submit="save">
                
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-primary rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Dompet & Rekening</h2>
                        <p class="text-xs text-gray-500">Masukkan nominal saldo per 1 Januari.</p>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    @foreach($accounts as $acc)
                    <div class="group bg-white border border-gray-100 rounded-2xl p-4 hover:border-primary/30 hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            
                            <!-- Info Akun -->
                            <div class="flex-1 flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl flex items-center justify-center text-xl font-bold {{ $acc->jenis == 'bank' ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600' }}">
                                    {{ substr($acc->nama, 0, 1) }}
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-800">{{ $acc->nama }}</label>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded {{ $acc->jenis == 'bank' ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600' }}">
                                        {{ $acc->jenis == 'bank' ? 'Rekening Bank' : 'Kas Tunai' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Input Nominal -->
                            <div class="w-full sm:w-64 relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                <input type="text" 
                                    wire:model="balances.{{ $acc->id }}"
                                    x-init="$el.value = formatRupiah($el.value)"
                                    x-on:input="$el.value = formatRupiah($el.value)"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 font-mono text-right font-bold text-lg text-gray-900 transition-all placeholder-gray-300 group-hover:bg-white group-hover:border-gray-200"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end">
                    <button type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-primary to-blue-800 border border-transparent rounded-xl font-bold text-white shadow-lg hover:shadow-xl hover:to-blue-900 focus:outline-none transition-all transform hover:-translate-y-1 active:scale-95">
                        <span wire:loading.remove>Simpan Saldo Awal</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
        @else
        <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Pilih Tahun Anggaran</h3>
            <p class="text-sm text-gray-500 mt-1">Pilih tahun di atas untuk mengatur saldo awal pembukuan.</p>
        </div>
        @endif

    </div>
</div>