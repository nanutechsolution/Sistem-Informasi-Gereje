<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\RefAccount;
use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
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
        $this->startDate = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
    }

    public function render()
    {
        $activeYear = FiscalYear::where('is_active', true)->first();

        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')->first();
        $kasBangun = RefAccount::where('nama', 'like', '%Pembangunan%')->first();

        // 1. SALDO AWAL
        $saldoAwalUmum = $this->calculateOpeningBalance($kasUmum, $activeYear);
        $saldoAwalBangun = $this->calculateOpeningBalance($kasBangun, $activeYear);

        // 2. DATA HIGHLIGHTS KEUANGAN (Mimbar)
        // Ambil rincian PKS dari ActivitySchedule agar bisa akses relasi Family & Servants tanpa error relasi di Transaction
        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();
        $detailPKS = ActivitySchedule::with(['family.members.churchPeople', 'servants.member.churchPeople'])
            ->where('ref_activity_type_id', $pksType?->id)
            ->where('nominal_persembahan', '>', 0)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->get();

        $detailLelang = Transaction::whereHas('budgetPost', fn($q) => $q->whereIn('kode', ['1.11', '1.21.1']))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->get();

        $totalASM = Transaction::whereHas('budgetPost', fn($q) => $q->where('kode', '1.18'))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->sum('nominal');

        $startMingguLalu = Carbon::parse($this->startDate)->subWeek();
        $endMingguLalu = Carbon::parse($this->endDate)->subWeek();

        $totalMingguLalu = Transaction::where('jenis', 'masuk')
            ->where('ref_account_id', $kasUmum->id ?? 0)
            ->whereBetween('tanggal', [$startMingguLalu, $endMingguLalu])
            ->sum('nominal');

        // 3. JADWAL PELAYANAN (Pekan Ini)
        // Ditambahkan eager loading 'family.members.churchPeople' untuk menampilkan nama Tuan Rumah
        $schedules = ActivitySchedule::with(['type', 'family.wilayah', 'family.members.churchPeople', 'servants.member.churchPeople'])
            ->whereBetween('tanggal', [$this->startDate, Carbon::parse($this->endDate)->addDays(7)])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // 4. ULANG TAHUN JEMAAT (Akses churchPeople)
        $birthdays = Member::active()
            ->with('churchPeople')
            ->whereHas('churchPeople', function($q) {
                $q->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') BETWEEN ? AND ?", [
                    Carbon::parse($this->startDate)->format('m-d'),
                    Carbon::parse($this->endDate)->format('m-d')
                ]);
            })
            ->get()
            ->sortBy(fn($m) => Carbon::parse($m->churchPeople->date_of_birth)->format('m-d'));

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
            'birthdays' => $birthdays,
            'pemasukanUmum' => $pemasukanUmum,
            'pengeluaranUmum' => $pengeluaranUmum,
            'pemasukanBangun' => $pemasukanBangun,
            'pengeluaranBangun' => $pengeluaranBangun,
            'totalMasukUmum' => $pemasukanUmum->sum(fn($group) => $group->sum('nominal')),
            'totalKeluarUmum' => $pengeluaranUmum->sum(fn($group) => $group->sum('nominal')),
            'totalMasukBangun' => $pemasukanBangun->sum(fn($group) => $group->sum('nominal')),
            'totalKeluarBangun' => $pengeluaranBangun->sum(fn($group) => $group->sum('nominal')),
        ]);
    }

    private function calculateOpeningBalance($account, $activeYear)
    {
        if (!$account || !$activeYear) return 0;

        $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)
            ->where('ref_account_id', $account->id)->sum('nominal');

        $mutasiBefore = Transaction::where('ref_account_id', $account->id)
            ->where('fiscal_year_id', $activeYear->id)
            ->where('tanggal', '<', $this->startDate);

        $masuk = (clone $mutasiBefore)->where('jenis', 'masuk')->sum('nominal');
        $keluar = (clone $mutasiBefore)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');

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
            ->groupBy('ref_budget_post_id');
    }
}