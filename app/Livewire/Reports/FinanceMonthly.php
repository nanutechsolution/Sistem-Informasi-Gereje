<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\OpeningBalance;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceMonthly extends Component
{
    public $bulan, $tahun;

    public function mount()
    {
        $this->bulan = date('n');
        $this->tahun = date('Y');
    }

    public function render()
    {
        $activeYear = FiscalYear::active();
        $startDate = Carbon::create($this->tahun, $this->bulan, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        // 1. Ambil Akun Utama
        $kasUmum = RefAccount::where('nama', 'like', '%Kas%')->first();
        $kasBangun = RefAccount::where('nama', 'like', '%Pembangunan%')->first();
        // 2. Hitung Saldo Awal (Sebelum bulan ini)
        $saldoAwalUmum = $this->calculateSaldo($kasUmum, $startDate, $activeYear);
        $saldoAwalBangun = $this->calculateSaldo($kasBangun, $startDate, $activeYear);

        // 3. Rekapitulasi Mutasi Bulan Ini
        $mutasiUmum = $this->getMonthlySummary($kasUmum, $startDate, $endDate);
        $mutasiBangun = $this->getMonthlySummary($kasBangun, $startDate, $endDate);

        return view('livewire.reports.finance-monthly', [
            'startDate' => $startDate,
            'kasUmum' => $kasUmum,
            'kasBangun' => $kasBangun,
            'saldoAwalUmum' => $saldoAwalUmum,
            'saldoAwalBangun' => $saldoAwalBangun,
            'mutasiUmum' => $mutasiUmum,
            'mutasiBangun' => $mutasiBangun,
        ]);
    }

    private function calculateSaldo($account, $date, $activeYear)
    {
        if (!$account || !$activeYear) return 0;
        $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)->where('ref_account_id', $account->id)->sum('nominal');
        $trx = Transaction::where('ref_account_id', $account->id)->where('tanggal', '<', $date->format('Y-m-d'));
        return $base + (clone $trx)->where('jenis', 'masuk')->sum('nominal') - (clone $trx)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');
    }

    private function getMonthlySummary($account, $start, $end)
    {
        if (!$account) return collect();
        return Transaction::with('budgetPost')
            ->where('ref_account_id', $account->id)
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->select('jenis', 'ref_budget_post_id', DB::raw('SUM(nominal) as total'))
            ->groupBy('jenis', 'ref_budget_post_id')
            ->get();
    }
}
