<?php

namespace App\Livewire\Finance;

use App\Models\OfficerPayroll;
use App\Models\OfficerPayrollItem;
use App\Models\ChurchOfficer;
use App\Models\FiscalYear;
use App\Models\OfficerPayrollPayment;
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
    public $isPaymentModalOpen = false, $selectedPayrollId, $nominal_bayar = 0, $catatan_bayar;
    public $selectedPayrollDetails = null;

    public function openPaymentModal($payrollId)
    {
        $this->selectedPayrollId = $payrollId;
        $this->isPaymentModalOpen = true;
    }

    public function mount()
    {
        $this->bulan = date('n');
        $this->tahun = date('Y');
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first()
            ?: RefAccount::where('jenis', 'kas_tunai')->first();
        $this->ref_account_id = $acc?->id;
    }


    public function generate()
    {
        // Ambil periode aktif sesuai bulan & tahun yang dipilih
        $currentPeriod = \App\Models\PayrollPeriod::whereMonth('tanggal_mulai', $this->bulan)
            ->whereYear('tanggal_mulai', $this->tahun)
            ->first();

        if (!$currentPeriod) {
            $this->dispatch('notify', message: 'Periode gaji untuk bulan ini belum dibuat!', type: 'error');
            return;
        }

        $activeOfficers = \App\Models\ChurchOfficer::with('salaryComponents.component')
            ->where('is_active', true)
            ->get();

        $count = 0;

        DB::transaction(function () use ($activeOfficers, $currentPeriod, &$count) {
            foreach ($activeOfficers as $off) {
                // Cek apakah sudah ada payroll untuk officer di periode ini
                $exists = \App\Models\OfficerPayroll::where('church_officer_id', $off->id)
                    ->where('payroll_period_id', $currentPeriod->id)
                    ->exists();

                if ($exists) continue;

                $totalPenerimaan = $off->salaryComponents
                    ->where('jenis', 'penerimaan')
                    ->where('is_active', true)
                    ->sum('nominal');

                $totalPotongan = $off->salaryComponents
                    ->where('jenis', 'potongan')
                    ->where('is_active', true)
                    ->sum('nominal');

                $thp = $totalPenerimaan - $totalPotongan;

                // Buat payroll
                $payroll = \App\Models\OfficerPayroll::create([
                    'payroll_period_id' => $currentPeriod->id,
                    'church_officer_id' => $off->id,
                    'fiscal_year_id'    => $currentPeriod->fiscal_year_id,
                    'total_penerimaan'  => $totalPenerimaan,
                    'total_potongan'    => $totalPotongan,
                    'take_home_pay'     => $thp,
                    'status'            => 'draft',

                ]);

                // Buat item gaji sesuai salaryComponents
                foreach ($off->salaryComponents as $comp) {
                    if (!$comp->is_active) continue;

                    // Pastikan nama snapshot valid
                    $namaSnapshot = $comp->component->nama ?? null;
                    if (!$namaSnapshot) {
                        throw new \Exception("Nama komponen gaji untuk officer {$off->id} tidak ditemukan!");
                    }

                    \App\Models\OfficerPayrollItem::create([
                        'officer_payroll_id'      => $payroll->id,
                        'ref_salary_component_id' => $comp->ref_salary_component_id,
                        'ref_budget_post_id'      => $comp->ref_budget_post_id,
                        'nama_snapshot'           => $namaSnapshot,
                        'jenis'                   => $comp->jenis,
                        'nominal_snapshot'        => $comp->nominal,
                    ]);
                }

                $count++;
            }
        });

        $this->dispatch('notify', message: $count > 0 ? "$count Draf gaji berhasil dibuat." : 'Semua sudah ada drafnya.', type: 'success');
    }


    public function payFull($id)
    {
        try {
            $this->selectedPayrollId = $id;

            // Ambil payroll beserta item dan officer
            $payroll = OfficerPayroll::with(['items', 'officer.member.churchPerson'])->findOrFail($id);

            // Hitung total yang bisa dibayar dari item
            $totalNominal = $payroll->items->sum(function ($item) {
                return ($item->nominal_snapshot - ($item->nominal_bayar ?? 0));
            });

            if ($totalNominal <= 0) {
                $this->dispatch('notify', message: 'Gaji officer ini sudah lunas atau 0, tidak bisa diproses!', type: 'error');
                return;
            }

            $this->nominal_bayar = $totalNominal;
            $this->catatan_bayar = 'Pelunasan Gaji';
            $this->savePayment();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan: ' . $e->getMessage(), type: 'error');
        }
    }


    public function savePayment()
    {
        try {
            $this->validate([
                'nominal_bayar' => 'required|numeric|min:1',
                'ref_account_id' => 'required'
            ]);


            $nominal = (float) $this->nominal_bayar;

            DB::transaction(function () use ($nominal) {
                // Lock payroll untuk menghindari race condition
                // Load relasi payroll_period saat ambil payroll
                $payroll = OfficerPayroll::with(['items', 'officer.member.churchPerson', 'payroll_period'])
                    ->lockForUpdate()
                    ->findOrFail($this->selectedPayrollId);

                // Gunakan safe navigation atau fallback
                $periodKode = $payroll->payroll_period->kode ?? 'N/A';
                $memberName = $payroll->officer->member->churchPerson->full_name ?? 'N/A';
                if (!$payroll) {
                    throw new \Exception("Payroll tidak ditemukan");
                }
                $refBudgetPostId = $payroll->items->first()->ref_budget_post_id;

                // Buat transaksi pembayaran
                $transaction = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $payroll->fiscal_year_id,
                    'tanggal' => now(),
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $refBudgetPostId,
                    'nominal' => $nominal,
                    'keterangan' => "Pembayaran gaji {$periodKode}: {$memberName}",
                    'user_id' => Auth::id(),
                ]);

                // Bayar tiap item sesuai proporsi (cicil)
                $remaining = $nominal;
                foreach ($payroll->items as $item) {
                    if ($remaining <= 0) break;

                    $due = $item->nominal_snapshot - ($item->nominal_bayar ?? 0);
                    $toPay = min($due, $remaining);

                    if ($toPay > 0) {
                        $item->increment('nominal_bayar', $toPay);
                        $remaining -= $toPay;
                    }
                }

                // Update status payroll
                $totalPaid = $payroll->items->sum('nominal_bayar');
                $totalNominal = $payroll->items->sum('nominal_snapshot');
                // $payroll->update([
                //     'status' => $totalPaid >= $totalNominal ? 'paid' : ($totalPaid > 0 ? 'cicil' : 'draft')
                // ]);
                $payroll->update([
                    'status_bayar' => $totalPaid >= $totalNominal ? 'lunas' : 'cicil',
                    'status' => $totalPaid >= $totalNominal ? 'paid' : 'draft',
                ]);
                OfficerPayrollPayment::create([
                    'uuid' => (string) Str::uuid(),
                    'officer_payroll_id' => $payroll->id,
                    'transaction_id' => $transaction->id,
                    'nominal' => $nominal,
                    'tanggal_bayar' => now(),
                    'keterangan' => $this->catatan_bayar,
                ]);
            });
            $this->dispatch('notify', message: 'Pembayaran gaji berhasil dicatat.', type: 'success');
            $this->isPaymentModalOpen = false;
            $this->reset(['nominal_bayar', 'catatan_bayar']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = implode(' | ', $e->validator->errors()->all());
            $this->dispatch('notify', message: "Validasi gagal: $errors", type: 'error');
        } catch (\Exception $e) {
            Log::error("savePayment error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->dispatch('notify', message: 'Terjadi kesalahan saat menyimpan pembayaran gaji.', type: 'error');
        }
    }

    public function render()
    {
        $payrolls = OfficerPayroll::with([
            'officer.member.churchPerson',
            'officer.position'
        ])
            ->whereHas('period', function ($q) {
                $q->whereMonth('tanggal_mulai', $this->bulan)
                    ->whereYear('tanggal_mulai', $this->tahun);
            })
            ->get();

        return view('livewire.finance.payroll-manager', [
            'payrolls' => $payrolls,
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}
