<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefAccount;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\ActivitySchedule;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK JEMAAT
        $totalKK = Family::count();
        $totalJiwa = Member::count();
        $totalLaki = Member::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Member::where('jenis_kelamin', 'P')->count();

        // 2. KEUANGAN REAL-TIME
        $activeYear = FiscalYear::active();
        
        $accounts = RefAccount::where('is_active', true)->get()->map(function($acc) use ($activeYear) {
            // Saldo Awal Tahun
            $saldoAwal = 0;
            if ($activeYear) {
                $saldoAwal = OpeningBalance::where('fiscal_year_id', $activeYear->id)
                    ->where('ref_account_id', $acc->id)
                    ->sum('nominal');
            }

            // Mutasi Berjalan
            $masuk = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'keluar')->sum('nominal');
            
            // Transfer
            // Masuk via transfer belum dihitung otomatis di 'masuk' jika pakai logic jurnal ganda terpisah
            // Tapi di logic create kita, transfer membuat trx 'masuk' di akun tujuan. Jadi aman.
            
            $acc->saldo_akhir = $saldoAwal + $masuk - $keluar;
            return $acc;
        });

        $totalUang = $accounts->sum('saldo_akhir');

        // 3. AGENDA PELAYANAN (7 Hari Kedepan)
        $schedules = ActivitySchedule::with(['type', 'family', 'wilayah'])
            ->where('tanggal', '>=', Carbon::today())
            ->where('tanggal', '<=', Carbon::today()->addDays(7))
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        // 4. ULANG TAHUN MINGGU INI
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);
        $birthdays = Member::whereRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') BETWEEN ? AND ?", [$today->format('m-d'), $nextWeek->format('m-d')])
            ->orderByRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') ASC")
            ->limit(6)->get();

        return view('livewire.dashboard', [
            'totalKK' => $totalKK,
            'totalJiwa' => $totalJiwa,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'accounts' => $accounts,
            'totalUang' => $totalUang,
            'schedules' => $schedules,
            'birthdays' => $birthdays
        ]);
    }
}