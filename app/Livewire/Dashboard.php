<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefAccount;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // --- 1. DATA JEMAAT ---
        $totalKK = Family::count();
        $totalJiwa = Member::count();
        $totalLaki = Member::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Member::where('jenis_kelamin', 'P')->count();

        // Ulang Tahun
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);
        $birthdays = Member::whereRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') BETWEEN ? AND ?", [$today->format('m-d'), $nextWeek->format('m-d')])
            ->orderByRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') ASC")
            ->limit(5)->get();

        // --- 2. DATA KEUANGAN (REAL-TIME SALDO) ---
        $activeYear = FiscalYear::active();
        
        $accounts = RefAccount::where('is_active', true)->get()->map(function($acc) use ($activeYear) {
            // A. Saldo Awal Tahun Ini
            $saldoAwal = 0;
            if ($activeYear) {
                $saldoAwal = OpeningBalance::where('fiscal_year_id', $activeYear->id)
                    ->where('ref_account_id', $acc->id)
                    ->sum('nominal');
            }

            // B. Mutasi Berjalan
            $masuk = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'keluar')->sum('nominal');
            $transferKeluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'pindah_buku')->sum('nominal');

            // Hitung Saldo Akhir
            $acc->saldo_akhir = $saldoAwal + $masuk - $keluar - $transferKeluar;
            return $acc;
        });

        $totalUang = $accounts->sum('saldo_akhir');

        return view('livewire.dashboard', [
            'totalKK' => $totalKK,
            'totalJiwa' => $totalJiwa,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'birthdays' => $birthdays,
            'accounts' => $accounts,
            'totalUang' => $totalUang
        ]);
    }
}
