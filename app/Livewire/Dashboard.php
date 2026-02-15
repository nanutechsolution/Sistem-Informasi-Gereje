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
        
        // Data Dasar (Bisa dilihat jika punya permission view_dashboard)
        $data = [
            'birthdays' => $this->getBirthdays($today),
            'upcomingGeneralSchedules' => $this->getGeneralSchedules($today),
            'myNextSchedule' => $this->getMyNextSchedule($user, $today),
        ];

        // 1. STATISTIK DATABASE (manage_database)
        if ($user->can('manage_database')) {
            $data['stats'] = [
                'kk' => Family::count(),
                'jiwa' => Member::active()->count(),
                'laki' => Member::active()->whereHas('churchPeople', fn($q) => $q->where('gender', 'L'))->count(),
                'perempuan' => Member::active()->whereHas('churchPeople', fn($q) => $q->where('gender', 'P'))->count(),
                'letters_this_month' => Letter::whereMonth('tanggal_cetak', $today->month)
                    ->whereYear('tanggal_cetak', $today->year)->count(),
            ];
        }

        // 2. RINGKASAN KEUANGAN (manage_finance)
        if ($user->can('manage_finance')) {
            $data['financial'] = $this->getFinancialData();
            
            // Hitung setoran PKS yang masih menggantung
            $data['pendingPksCount'] = ActivitySchedule::whereHas('type', fn($q) => $q->where('nama', 'like', '%PKS%'))
                ->where('status_setoran', 'pending')
                ->where('nominal_persembahan', '>', 0)
                ->count();
        }

        return view('livewire.dashboard', $data);
    }

    private function getFinancialData()
    {
        $activeYear = FiscalYear::where('is_active', true)->first();
        
        $accounts = RefAccount::where('is_active', true)->get()->map(function($acc) use ($activeYear) {
            $saldoAwal = $activeYear ? OpeningBalance::where('fiscal_year_id', $activeYear->id)
                ->where('ref_account_id', $acc->id)->sum('nominal') : 0;

            $masuk = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'keluar')->sum('nominal');
            
            // Pindah buku (keluar dari sini)
            $transferKeluar = Transaction::where('ref_account_id', $acc->id)->where('jenis', 'pindah_buku')->sum('nominal');
            
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
        // Mengambil data dari relasi churchPeople
        return Member::active()
            ->with(['churchPeople', 'family.wilayah'])
            ->whereHas('churchPeople', function($q) use ($today, $nextWeek) {
                $q->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') BETWEEN ? AND ?", [
                    $today->format('m-d'), 
                    $nextWeek->format('m-d')
                ]);
            })
            ->get()
            ->sortBy(fn($m) => Carbon::parse($m->churchPeople->date_of_birth)->format('m-d'))
            ->take(6);
    }

    private function getGeneralSchedules($today)
    {
        return ActivitySchedule::with(['type', 'wilayah'])
            ->where('tanggal', '>=', $today)
            ->where('tanggal', '<=', (clone $today)->addDays(14))
            ->whereHas('type', fn($q) => $q->where('nama', 'not like', '%PKS%')) // Kecuali PKS di agenda umum
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();
    }

    private function getMyNextSchedule($user, $today)
    {
        // Gunakan helper member_id dari model User
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