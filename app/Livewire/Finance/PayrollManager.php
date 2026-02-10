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
use Illuminate\Support\Facades\Log;

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
                $totalPenerimaan = $off->salaryComponents->where('jenis', 'penerimaan')->where('is_active', true)->sum('nominal');
                $tunjanganRumah = $off->salaryComponents->where('jenis', 'penerimaan')
                    ->filter(fn($c) => str_contains(strtolower($c->nama_komponen), 'rumah'))->sum('nominal');
                $gajiPokok = $totalPenerimaan - $tunjanganRumah;
                $totalPensiun = $off->salaryComponents->where('jenis', 'potongan')
                    ->filter(fn($c) => str_contains(strtolower($c->nama_komponen), 'pensiun'))->sum('nominal');

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

        $this->dispatch('notify', message: $count > 0 ? "$count Draf gaji berhasil dibuat." : 'Semua sudah ada drafnya.', type: 'success');
    }

    public function openPaymentModal($id)
    {
        $this->selectedPayrollId = $id;
        $pay = Payroll::with('officer.member')->findOrFail($id);
        $this->selectedPayrollDetails = $pay;
        // Pre-fill nominal dengan sisa gaji (fresh calculation)
        $this->nominal_bayar = number_format($pay->netto - $pay->payments()->sum('nominal'), 0, ',', '.');
        $this->catatan_bayar = '';
        $this->isPaymentModalOpen = true;
    }

    public function payFull($id)
    {
        if (!$this->ref_account_id) {
            $this->dispatch('notify', message: 'Pilih Akun Sumber Dana dulu!', type: 'error');
            return;
        }
        $this->selectedPayrollId = $id;
        $pay = Payroll::findOrFail($id);
        $this->nominal_bayar = number_format($pay->netto - $pay->payments()->sum('nominal'), 0, ',', '.');
        $this->catatan_bayar = 'Pelunasan Gaji';
        $this->savePayment();
    }

    public function savePayment()
    {
        $this->validate(['nominal_bayar' => 'required', 'ref_account_id' => 'required']);
        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_bayar);
        if ($cleanNominal <= 0) return;

        DB::transaction(function () use ($cleanNominal) {
            // RE-LOAD FRESH DATA di dalam transaksi untuk akurasi saldo
            $pay = Payroll::with(['officer.member', 'officer.salaryComponents'])->lockForUpdate()->findOrFail($this->selectedPayrollId);
            // Hitung sisa riil sebelum transaksi ini
            $sisaRiil = $pay->netto - $pay->payments()->sum('nominal');

            // Cari Pos Anggaran Utama
            $mainBudgetPostId = $pay->officer->ref_budget_post_id;
            if (!$mainBudgetPostId) {
                $mainBudgetPostId = $pay->officer->salaryComponents->where('jenis', 'penerimaan')->sortByDesc('nominal')->first()?->ref_budget_post_id;
            }

            if (!$mainBudgetPostId) {
                throw new \Exception("Pos Anggaran tidak ditemukan pada profil pegawai.");
            }

            $transactionId = null;

            // 1. LOGIKA PEMBAYARAN
            if ($cleanNominal >= $sisaRiil) {
                // BAYAR LUNAS: Pecah Jurnal sesuai komponen
                $trxGaji = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $pay->fiscal_year_id,
                    'tanggal' => now(),
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $mainBudgetPostId,
                    'nominal' => $pay->gaji_pokok + $pay->tunjangan_lain,
                    'keterangan' => "Gaji Pokok {$pay->nama_bulan}: " . $pay->officer->member->nama,
                    'user_id' => Auth::id(),
                ]);
                $transactionId = $trxGaji->id;

                // Tunjangan Rumah
                $posRumah = $pay->officer->ref_perumahan_post_id;
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

                // Iuran Pensiun
                $posPensiun = $pay->officer->ref_pensiun_post_id;
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
            } else {
                // BAYAR CICIL: Satu Jurnal Gabungan
                $trx = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $pay->fiscal_year_id,
                    'tanggal' => now(),
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $mainBudgetPostId,
                    'nominal' => $cleanNominal,
                    'keterangan' => "Cicilan Gaji {$pay->nama_bulan}: " . $pay->officer->member->nama . " ({$this->catatan_bayar})",
                    'user_id' => Auth::id(),
                ]);
                $transactionId = $trx->id;
            }

            // 2. CATAT LOG PEMBAYARAN
            PayrollPayment::create([
                'uuid' => (string) Str::uuid(),
                'payroll_id' => $pay->id,
                'transaction_id' => $transactionId,
                'nominal' => $cleanNominal,
                'tanggal_bayar' => now(),
                'keterangan' => $this->catatan_bayar ?: 'Pembayaran Gaji',
            ]);

            // 3. UPDATE STATUS (Re-calculate fresh)
            $newTotalPaid = $pay->payments()->sum('nominal');
            $isLunas = $newTotalPaid >= $pay->netto;

            $pay->update([
                'status_bayar' => $isLunas ? 'lunas' : 'cicil',
                'status' => $isLunas ? 'paid' : 'draft',
                'tanggal_bayar' => now(),
                'transaction_id' => $transactionId
            ]);
        });

        $this->dispatch('notify', message: 'Pembayaran gaji berhasil dicatat.', type: 'success');
        $this->isPaymentModalOpen = false;
        $this->reset(['nominal_bayar', 'catatan_bayar']);
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
