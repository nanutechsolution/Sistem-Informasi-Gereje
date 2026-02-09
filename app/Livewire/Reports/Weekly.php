<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\ActivitySchedule;
use App\Models\Member;
use App\Models\RefAccount;
use App\Models\OpeningBalance;
use App\Models\FiscalYear;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Weekly extends Component
{
    public $startDate, $endDate; // Periode Laporan Keuangan (Pekan Lalu)
    public $nextStartDate, $nextEndDate; // Periode Agenda (Pekan Depan)

    public function mount()
    {
        // Keuangan: Senin - Minggu kemarin
        $this->startDate = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');

        // Agenda: Senin - Minggu besok
        $this->nextStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->nextEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');
    }

    public function render()
    {
        $activeYear = FiscalYear::active();
        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')->first() ?: RefAccount::first();

        // 1. DATA KEUANGAN (PEKAN LALU)
        $saldoAwal = $this->calculateOpeningBalance($kasUmum, $activeYear);
        
        $pemasukan = Transaction::with('budgetPost')
            ->where('ref_account_id', $kasUmum->id)
            ->where('jenis', 'masuk')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->select('ref_budget_post_id', DB::raw('sum(nominal) as total'))
            ->groupBy('ref_budget_post_id')->get();

        $pengeluaran = Transaction::with('budgetPost')
            ->where('ref_account_id', $kasUmum->id)
            ->where('jenis', 'keluar')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->select('ref_budget_post_id', DB::raw('sum(nominal) as total'))
            ->groupBy('ref_budget_post_id')->get();

        // 2. AGENDA PELAYANAN (PEKAN INI/DEPAN)
        $schedules = ActivitySchedule::with(['type', 'servants.member', 'family'])
            ->whereBetween('tanggal', [$this->nextStartDate, $this->nextEndDate])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // 3. JEMAAT ULANG TAHUN
        $birthdays = Member::whereRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') BETWEEN ? AND ?", [
            Carbon::parse($this->nextStartDate)->format('m-d'),
            Carbon::parse($this->nextEndDate)->format('m-d')
        ])->get();

        return view('livewire.reports.weekly', [
            'saldoAwal' => $saldoAwal,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'schedules' => $schedules,
            'birthdays' => $birthdays,
            'totalMasuk' => $pemasukan->sum('total'),
            'totalKeluar' => $pengeluaran->sum('total'),
        ]);
    }

    private function calculateOpeningBalance($account, $year)
    {
        if (!$account || !$year) return 0;
        $base = OpeningBalance::where('fiscal_year_id', $year->id)->where('ref_account_id', $account->id)->sum('nominal');
        $prevMutasi = Transaction::where('ref_account_id', $account->id)->where('tanggal', '<', $this->startDate);
        return $base + $prevMutasi->where('jenis', 'masuk')->sum('nominal') - $prevMutasi->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');
    }
}