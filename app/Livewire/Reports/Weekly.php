<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\RefAccount;
use App\Models\ActivitySchedule;
use App\Models\Member; 
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Weekly extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $start = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $end   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        $this->startDate = $start->format('Y-m-d');
        $this->endDate = $end->format('Y-m-d');
    }

    public function render()
    {
        $activeYear = FiscalYear::active();

        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')
            ->orWhere('jenis', 'kas_tunai')
            ->orderBy('id', 'asc')
            ->first();

        $kasBangun = RefAccount::where('nama', 'like', '%Pembangunan%')->first();

        // 1. SALDO AWAL
        $saldoAwalUmum = $this->calculateOpeningBalance($kasUmum, $activeYear);
        $saldoAwalBangun = $this->calculateOpeningBalance($kasBangun, $activeYear);

        // 2. DATA HIGHLIGHTS KEUANGAN
        $detailPKS = Transaction::whereHas('budgetPost', fn($q) => $q->where('kode', 'like', '1.2%'))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        $detailLelang = Transaction::whereHas('budgetPost', fn($q) => $q->whereIn('kode', ['1.11', '1.21.1']))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        $totalASM = Transaction::whereHas('budgetPost', fn($q) => $q->where('kode', '1.18'))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])->sum('nominal');

        $startMingguLalu = Carbon::now()->subWeek()->startOfWeek(); // Senin minggu lalu
        $endMingguLalu   = Carbon::now()->subWeek()->endOfWeek();   // Minggu minggu lalu

        $totalMingguLalu = Transaction::where('jenis', 'masuk')
            ->where('ref_account_id', $kasUmum->id ?? 0)
            ->whereBetween('tanggal', [$startMingguLalu->startOfDay(), $endMingguLalu->endOfDay()])
            // ->get();
            ->sum('nominal');
        // $totalMingguLalu = Transaction::where('jenis', 'masuk')
        //     ->where('ref_account_id', $kasUmum->id ?? 0)
        //     ->whereBetween('tanggal', [
        //         Carbon::parse($this->startDate)->subDays(7),
        //         Carbon::parse($this->endDate)->subDays(7)
        //     ])
        //     ->sum('nominal');

        // 3. JADWAL PELAYANAN (Pekan Depan / Periode Ini)
        $schedules = ActivitySchedule::with(['type', 'family.refWilayah', 'servants.member'])
            ->whereBetween('tanggal', [$this->startDate, Carbon::parse($this->endDate)->addDays(7)]) // Ambil rentang agak lebar untuk agenda
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // 4. ULANG TAHUN JEMAAT (Dalam Periode Ini)
        $birthdays = Member::whereRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') BETWEEN ? AND ?", [
            Carbon::parse($this->startDate)->format('m-d'),
            Carbon::parse($this->endDate)->format('m-d')
        ])->orderByRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') ASC")->get();

        // 5. REKAPITULASI TABEL
        $pemasukanUmum = $this->getSummary($kasUmum, 'masuk');
        $pengeluaranUmum = $this->getSummary($kasUmum, 'keluar');
        $pemasukanBangun = $this->getSummary($kasBangun, 'masuk');
        $pengeluaranBangun = $this->getSummary($kasBangun, 'keluar');

        return view('livewire.reports.weekly', [
            'saldoAwalUmum' => $saldoAwalUmum,
            'saldoAwalBangun' => $saldoAwalBangun,

            'detailPKS' => $detailPKS,
            'detailLelang' => $detailLelang,
            'totalASM' => $totalASM,
            'totalMingguLalu' => $totalMingguLalu,

            'schedules' => $schedules,
            'birthdays' => $birthdays, // Data Ulang Tahun

            'pemasukanUmum' => $pemasukanUmum,
            'pengeluaranUmum' => $pengeluaranUmum,
            'totalMasukUmum' => $pemasukanUmum->sum('total'),
            'totalKeluarUmum' => $pengeluaranUmum->sum('total'),

            'pemasukanBangun' => $pemasukanBangun,
            'pengeluaranBangun' => $pengeluaranBangun,
            'totalMasukBangun' => $pemasukanBangun->sum('total'),
            'totalKeluarBangun' => $pengeluaranBangun->sum('total'),
        ]);
    }

    private function calculateOpeningBalance($account, $activeYear)
    {
        if (!$account || !$activeYear) return 0;

        $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)
            ->where('ref_account_id', $account->id)->sum('nominal');

        $mutasi = Transaction::where('ref_account_id', $account->id)
            ->where('fiscal_year_id', $activeYear->id)
            ->where('tanggal', '<', $this->startDate);

        $masuk = (clone $mutasi)->where('jenis', 'masuk')->sum('nominal');
        $keluar = (clone $mutasi)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');

        return $base + $masuk - $keluar;
    }

    private function getSummary($account, $jenis)
    {
        if (!$account) return collect();

        return Transaction::with('budgetPost')
            ->where('ref_account_id', $account->id)
            ->where('jenis', $jenis)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->get()
            ->groupBy('ref_budget_post_id'); // kumpulkan per budgetPost
    }
}
