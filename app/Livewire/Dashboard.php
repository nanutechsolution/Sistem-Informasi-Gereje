<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefAccount;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\ActivitySchedule;
use App\Models\Letter;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $data = [
            // Data Global (Semua User Lihat)
            'birthdays' => $this->getBirthdays($today),
            'upcomingGeneralSchedules' => $this->getGeneralSchedules($today),
            'myNextSchedule' => $this->getMyNextSchedule($user, $today),
        ];

        // 1. DATA KHUSUS KEUANGAN (Bendahara & Admin)
        if ($user->can('manage_finance')) {
            $data['financial'] = $this->getFinancialData();
            $data['pendingPksCount'] = ActivitySchedule::where('ref_activity_type_id', 2) // PKS
                ->where('status_setoran', 'pending')
                ->where('nominal_persembahan', '>', 0)
                ->count();
        }

        // 2. DATA KHUSUS SEKRETARIAT (Sekretaris & Admin)
        if ($user->can('manage_database')) {
            $data['stats'] = [
                'kk' => Family::count(),
                'jiwa' => Member::count(),
                'laki' => Member::where('jenis_kelamin', 'L')->count(),
                'perempuan' => Member::where('jenis_kelamin', 'P')->count(),
                'letters_this_month' => Letter::whereMonth('tanggal_cetak', $today->month)
                    ->whereYear('tanggal_cetak', $today->year)->count(),
            ];
        }

        return view('livewire.dashboard', $data);
    }

    private function getFinancialData()
    {
        $activeYear = FiscalYear::active();
        
        $accounts = RefAccount::where('is_active', true)->get()->map(function($acc) use ($activeYear) {
            $saldoAwal = $activeYear ? OpeningBalance::where('fiscal_year_id', $activeYear->id)
                ->where('ref_account_id', $acc->id)->sum('nominal') : 0;

            $masuk = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'keluar')->sum('nominal');
            
            // Pindah buku (keluar dari sini)
            $transferKeluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'pindah_buku')->sum('nominal');
            
            // Pindah buku (masuk ke sini - dicatat sebagai 'masuk' di trx tujuan, jadi sudah tercover di $masuk)

            $acc->saldo_akhir = $saldoAwal + $masuk - $keluar - $transferKeluar;
            return $acc;
        });

        return [
            'accounts' => $accounts,
            'total_saldo' => $accounts->sum('saldo_akhir'),
        ];
    }

    private function getBirthdays($today)
    {
        $nextWeek = (clone $today)->addDays(7);
        return Member::with('family.refWilayah')
            ->whereRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') BETWEEN ? AND ?", [$today->format('m-d'), $nextWeek->format('m-d')])
            ->orderByRaw("DATE_FORMAT(tanggal_lahir, '%m-%d') ASC")
            ->limit(6)->get();
    }

    private function getGeneralSchedules($today)
    {
        return ActivitySchedule::with(['type', 'wilayah'])
            ->where('tanggal', '>=', $today)
            ->where('tanggal', '<=', (clone $today)->addDays(14)) // 2 Minggu ke depan
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();
    }

    private function getMyNextSchedule($user, $today)
    {
        // Jika user terhubung ke member, cari jadwal dia
        if ($user->member_id) {
            return ActivitySchedule::with(['type', 'family'])
                ->whereHas('servants', function($q) use ($user) {
                    $q->where('member_id', $user->member_id);
                })
                ->where('tanggal', '>=', $today)
                ->orderBy('tanggal')
                ->orderBy('jam_mulai')
                ->first();
        }
        return null;
    }
}