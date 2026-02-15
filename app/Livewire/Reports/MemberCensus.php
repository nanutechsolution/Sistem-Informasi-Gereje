<?php

namespace App\Livewire\Reports;

use App\Models\Member;
use App\Models\Family;
use App\Models\RefWilayah;
use App\Models\FiscalYear;
use App\Models\SacramentRecord;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MemberCensus extends Component
{
    public $yearFilter;

    public function mount()
    {
        $activeYear = FiscalYear::where('is_active', 1)->first();
        $this->yearFilter = $activeYear ? $activeYear->id : FiscalYear::latest('tahun')->first()?->id;
    }

    public function render()
    {
        $selectedYear = FiscalYear::find($this->yearFilter);
        $yearValue = $selectedYear ? $selectedYear->tahun : date('Y');

        // 1. Statistik Dasar (Hanya Jemaat Aktif)
        $totalKK = Family::where('status', 'aktif')->count();
        $baseQuery = Member::where('status_keanggotaan', 'aktif');

        $totalJiwaAktif = $baseQuery->count();

        $genderData = Member::where('status_keanggotaan', 'aktif')
            ->join('church_people', 'members.church_people_id', '=', 'church_people.id')
            ->select('church_people.gender', DB::raw('count(*) as total'))
            ->groupBy('church_people.gender')
            ->pluck('total', 'gender');

        // 2. Statistik Mutasi (Berdasarkan Tahun yang Dipilih)
        $mutasiMeninggal = Member::where('status_keanggotaan', 'meninggal')
            ->whereYear('tanggal_meninggal', $yearValue)
            ->count();

        $mutasiPindah = Member::where('status_keanggotaan', 'pindah')
            ->whereHas('events', function ($q) use ($yearValue) {
                $q->whereHas('eventType', fn($e) => $e->whereIn('kode', ['MUTASI_KELUAR', 'PINDAH']))
                    ->whereYear('tanggal', $yearValue);
            })->count();

        // 3. Statistik Wilayah (Jiwa Aktif)
        $wilayahStats = RefWilayah::orderBy('nama')->get()->map(function ($w) {
            $w->jiwa_count = Member::where('status_keanggotaan', 'aktif')
                ->whereHas('family', fn($q) => $q->where('wilayah_id', $w->id))
                ->count();
            return $w;
        });

        // 4. Kelompok Usia (Jiwa Aktif)
        $usiaData = [
            'Anak (0-12)' => Member::where('status_keanggotaan', 'aktif')
                ->whereHas('churchPeople', fn($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) <= 12'))->count(),
            'Remaja (13-17)' => Member::where('status_keanggotaan', 'aktif')
                ->whereHas('churchPeople', fn($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 17'))->count(),
            'Pemuda (18-35)' => Member::where('status_keanggotaan', 'aktif')
                ->whereHas('churchPeople', fn($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35'))->count(),
            'Dewasa (36-59)' => Member::where('status_keanggotaan', 'aktif')
                ->whereHas('churchPeople', fn($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 59'))->count(),
            'Lansia (60+)' => Member::where('status_keanggotaan', 'aktif')
                ->whereHas('churchPeople', fn($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60'))->count(),
        ];

        // 5. Status Sakramen (Berdasarkan Arsip Sakramen)
        $sacramentData = [
            'Baptis' => SacramentRecord::whereHas('type', fn($q) => $q->where('kode', 'BPT'))->count(),
            'Sidi' => SacramentRecord::whereHas('type', fn($q) => $q->where('kode', 'SDI'))->count(),
            'Nikah' => SacramentRecord::whereHas('type', fn($q) => $q->where('kode', 'NKH'))->count(),
        ];

        return view('livewire.reports.member-census', [
            'stats' => [
                'kk' => $totalKK,
                'jiwa' => $totalJiwaAktif,
                'l' => $genderData['L'] ?? 0,
                'p' => $genderData['P'] ?? 0,
                'meninggal' => $mutasiMeninggal,
                'pindah' => $mutasiPindah
            ],
            'wilayahStats' => $wilayahStats,
            'usiaData' => $usiaData,
            'sacramentData' => $sacramentData,
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'currentYearLabel' => $yearValue
        ]);
    }
}
