<?php

namespace App\Livewire\Finance;

use App\Models\Payroll;
use App\Models\PayrollPayment;
use App\Models\ChurchOfficer;
use App\Models\FiscalYear;
use App\Models\Transaction;
use App\Models\RefAccount;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan Log

class PayrollManager extends Component
{
    public $bulan, $tahun, $ref_account_id;
    public $isPaymentModalOpen = false, $selectedPayrollId, $nominal_bayar, $catatan_bayar;
    public $selectedPayrollDetails = null; 

    public function mount()
    {
        $this->bulan = date('n');
        $this->tahun = date('Y');
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first() ?: RefAccount::where('jenis', 'kas_tunai')->first();
        $this->ref_account_id = $acc?->id;
    }

    public function generate()
    {
        $fiscalYear = FiscalYear::active();
        if (!$fiscalYear) {
            $this->dispatch('notify', message: 'Tahun Anggaran Aktif tidak ditemukan!', type: 'error');
            return;
        }

        $activeOfficers = ChurchOfficer::with(['salaryComponents', 'position'])->where('is_active', true)->get();
        $count = 0;

        foreach ($activeOfficers as $off) {
            $exists = Payroll::where('church_officer_id', $off->id)->where('bulan', $this->bulan)->where('tahun', $this->tahun)->exists();

            if (!$exists) {
                // Logika Hitung dari Komponen
                $totalPenerimaan = $off->salaryComponents->where('jenis', 'penerimaan')->where('is_active', true)->sum('nominal');
                
                $tunjanganRumah = $off->salaryComponents->where('jenis', 'penerimaan')
                    ->filter(function ($value) { 
                        return str_contains(strtolower($value->nama_komponen), 'rumah'); 
                    })->sum('nominal');

                $gajiPokok = $totalPenerimaan - $tunjanganRumah;

                $totalPensiun = $off->salaryComponents->where('jenis', 'potongan')
                    ->filter(function ($value) { 
                        return str_contains(strtolower($value->nama_komponen), 'pensiun'); 
                    })->sum('nominal');
                
                $netto = $off->net_salary; 

                if ($netto > 0) {
                    Payroll::create([
                        'uuid' => (string) Str::uuid(),
                        'church_officer_id' => $off->id,
                        'fiscal_year_id' => $fiscalYear->id,
                        'bulan' => $this->bulan,
                        'tahun' => $this->tahun,
                        'gaji_pokok' => $gajiPokok, 
                        'tunjangan_perumahan' => $tunjanganRumah,
                        'iuran_pensiun' => $totalPensiun,
                        'netto' => $netto,
                        'status' => 'draft',
                        'status_bayar' => 'belum'
                    ]);
                    $count++;
                }
            }
        }
        
        $msg = $count > 0 ? "$count Draf gaji berhasil dibuat." : 'Semua pegawai sudah ada drafnya.';
        $this->dispatch('notify', message: $msg, type: 'success');
    }

    public function openPaymentModal($id)
    {
        $this->selectedPayrollId = $id;
        $pay = Payroll::with('officer')->findOrFail($id);
        $this->selectedPayrollDetails = $pay;
        $this->nominal_bayar = number_format($pay->sisa_gaji, 0, ',', '.');
        $this->catatan_bayar = '';
        $this->isPaymentModalOpen = true;
    }

    public function payFull($id)
    {
        if (!$this->ref_account_id) {
            $this->dispatch('notify', message: 'Pilih Akun Sumber Dana di atas dulu!', type: 'error');
            return;
        }

        $this->selectedPayrollId = $id;
        $pay = Payroll::findOrFail($id);
        $this->nominal_bayar = number_format($pay->sisa_gaji, 0, ',', '.');
        $this->catatan_bayar = 'Pelunasan Gaji';
        $this->savePayment();
    }

    public function savePayment()
    {
        $this->validate(['nominal_bayar' => 'required', 'ref_account_id' => 'required']);
        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_bayar);

        if ($cleanNominal <= 0) return;

        // Load dengan Komponen Gaji
        $pay = Payroll::with(['officer.member', 'officer.salaryComponents'])->findOrFail($this->selectedPayrollId);

        // --- DEBUG LOGGING ---
        Log::info('Debug Pembayaran Gaji', [
            'nama' => $pay->officer->member->nama,
            'pos_legacy' => $pay->officer->ref_budget_post_id,
            'komponen' => $pay->officer->salaryComponents->toArray()
        ]);

        // FIX: Cari Pos Anggaran secara cerdas
        // 1. Cek kolom legacy
        $mainBudgetPostId = $pay->officer->ref_budget_post_id;

        // 2. Jika kosong, ambil dari komponen Gaji Pokok / Terbesar
        if (!$mainBudgetPostId) {
            $mainComponent = $pay->officer->salaryComponents
                ->where('jenis', 'penerimaan')
                ->sortByDesc('nominal') // Ambil nominal terbesar (biasanya gaji pokok)
                ->first();
            
            $mainBudgetPostId = $mainComponent?->ref_budget_post_id;
        }

        // Validasi Akhir
        if (!$mainBudgetPostId) {
            $this->dispatch('notify', message: "GAGAL: Pos Anggaran tidak ditemukan di profil pegawai. Silakan edit pegawai dan set 'Pos RAPB' pada komponen Gaji.", type: 'error');
            $this->isPaymentModalOpen = false;
            return;
        }

        DB::transaction(function() use ($pay, $cleanNominal, $mainBudgetPostId) {
            
            // 1. JIKA BAYAR LUNAS / FULL THP
            if ($cleanNominal >= $pay->sisa_gaji) {
                
                // A. Transaksi Gaji Pokok (Gunakan ID Pos yang sudah ditemukan)
                $trxGaji = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $pay->fiscal_year_id,
                    'tanggal' => now(),
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $mainBudgetPostId, // FIX: Gunakan variabel hasil pencarian
                    'nominal' => $pay->gaji_pokok + $pay->tunjangan_lain,
                    'keterangan' => "Gaji Pokok {$pay->nama_bulan}: " . $pay->officer->member->nama,
                    'user_id' => Auth::id(),
                ]);

                // B. Transaksi Tunjangan Perumahan
                // Cari Pos Perumahan dari Komponen atau Legacy
                $posRumah = $pay->officer->ref_perumahan_post_id;
                if (!$posRumah) {
                    $compRumah = $pay->officer->salaryComponents->where('jenis', 'penerimaan')
                        ->filter(fn($c) => str_contains(strtolower($c->nama_komponen), 'rumah'))->first();
                    $posRumah = $compRumah?->ref_budget_post_id;
                }

                if ($pay->tunjangan_perumahan > 0 && $posRumah) {
                    Transaction::create([
                        'uuid' => (string) Str::uuid(),
                        'fiscal_year_id' => $pay->fiscal_year_id,
                        'tanggal' => now(),
                        'jenis' => 'keluar',
                        'ref_account_id' => $this->ref_account_id,
                        'ref_budget_post_id' => $posRumah,
                        'nominal' => $pay->tunjangan_perumahan,
                        'keterangan' => "Tunj. Rumah {$pay->nama_bulan}: " . $pay->officer->member->nama,
                        'user_id' => Auth::id(),
                    ]);
                }

                // C. Transaksi Iuran Pensiun
                $posPensiun = $pay->officer->ref_pensiun_post_id;
                if (!$posPensiun) {
                    $compPensiun = $pay->officer->salaryComponents->where('jenis', 'potongan')
                        ->filter(fn($c) => str_contains(strtolower($c->nama_komponen), 'pensiun'))->first();
                    $posPensiun = $compPensiun?->ref_budget_post_id;
                }

                if ($pay->iuran_pensiun > 0 && $posPensiun) {
                    Transaction::create([
                        'uuid' => (string) Str::uuid(),
                        'fiscal_year_id' => $pay->fiscal_year_id,
                        'tanggal' => now(),
                        'jenis' => 'keluar',
                        'ref_account_id' => $this->ref_account_id,
                        'ref_budget_post_id' => $posPensiun,
                        'nominal' => $pay->iuran_pensiun,
                        'keterangan' => "Iuran Pensiun {$pay->nama_bulan}: " . $pay->officer->member->nama,
                        'user_id' => Auth::id(),
                    ]);
                }

                // Catat di riwayat payroll
                PayrollPayment::create([
                    'uuid' => (string) Str::uuid(),
                    'payroll_id' => $pay->id,
                    'transaction_id' => $trxGaji->id,
                    'nominal' => $cleanNominal,
                    'tanggal_bayar' => now(),
                    'keterangan' => 'Pelunasan Gaji & Tunjangan',
                ]);

            } else {
                // 2. JIKA BAYAR CICIL / KASBON (Masuk ke Pos Utama)
                $trx = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $pay->fiscal_year_id,
                    'tanggal' => now(),
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $mainBudgetPostId, // FIX: Gunakan variabel hasil pencarian
                    'nominal' => $cleanNominal,
                    'keterangan' => "Cicilan Gaji {$pay->nama_bulan}: " . $pay->officer->member->nama . " ({$this->catatan_bayar})",
                    'user_id' => Auth::id(),
                ]);

                PayrollPayment::create([
                    'uuid' => (string) Str::uuid(),
                    'payroll_id' => $pay->id,
                    'transaction_id' => $trx->id,
                    'nominal' => $cleanNominal,
                    'tanggal_bayar' => now(),
                    'keterangan' => $this->catatan_bayar,
                ]);
            }

            // Update Status Payroll
            $pay->refresh();
            $statusBayar = $pay->is_lunas ? 'lunas' : 'cicil';
            $transactionId = isset($trxGaji) ? $trxGaji->id : ($trx->id ?? null);

            $pay->update([
                'status_bayar' => $statusBayar,
                'status' => $pay->is_lunas ? 'paid' : 'draft',
                'tanggal_bayar' => now(),
                'transaction_id' => $transactionId 
            ]);
        });

        $this->dispatch('notify', message: 'Pembayaran berhasil dipecah ke pos anggaran masing-masing.', type: 'success');
        $this->isPaymentModalOpen = false;
        $this->reset(['nominal_bayar', 'catatan_bayar', 'selectedPayrollDetails']);
    }

    public function render()
    {
        return view('livewire.finance.payroll-manager', [
            'payrolls' => Payroll::with(['officer.member', 'officer.position', 'payments'])
                ->where('bulan', $this->bulan)
                ->where('tahun', $this->tahun)
                ->get()
                ->sortBy(fn($q) => $q->officer->position->urutan ?? 99),
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}