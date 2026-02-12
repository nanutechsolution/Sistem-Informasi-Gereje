<?php

namespace App\Livewire\Reports;

use App\Models\Member;
use App\Models\Family;
use App\Models\RefWilayah;
use App\Models\FiscalYear;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MemberCensus extends Component
{
    public $yearFilter;

    public function mount()
    {
        // Mengambil tahun fiskal yang sedang aktif sebagai default
        $activeYear = FiscalYear::where('is_active', 1)->first();
        $this->yearFilter = $activeYear ? $activeYear->tahun : date('Y');
    }

    public function render()
    {
        // 1. Statistik Dasar (Jemaat Aktif)
        $totalKK = Family::count();
        $totalJiwaAktif = Member::where('status_keanggotaan', 'aktif')->count();
        
        $genderData = Member::where('status_keanggotaan', 'aktif')
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        // 2. Statistik Mutasi Berdasarkan Filter Tahun Fiskal
        $mutasiMeninggal = Member::where('status_keanggotaan', 'meninggal')
            ->whereYear('tanggal_meninggal', $this->yearFilter)->count();
        
        $mutasiPindah = Member::where('status_keanggotaan', 'pindah')
            ->whereHas('events', function($q) {
                $q->whereHas('eventType', fn($e) => $e->where('kode', 'MUTASI_KELUAR'))
                  ->whereYear('tanggal', $this->yearFilter);
            })->count();

        // 3. Statistik Wilayah (Jiwa Aktif)
        $wilayahStats = RefWilayah::orderBy('nama')->get()->map(function($w) {
            $w->jiwa_count = Member::where('status_keanggotaan', 'aktif')
                ->whereHas('family', fn($q) => $q->where('wilayah_id', $w->id))
                ->count();
            return $w;
        });

        // 4. Kelompok Usia (Jiwa Aktif)
        $usiaData = [
            'Anak (0-12)' => Member::where('status_keanggotaan', 'aktif')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 12')->count(),
            'Remaja (13-17)' => Member::where('status_keanggotaan', 'aktif')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 13 AND 17')->count(),
            'Pemuda (18-35)' => Member::where('status_keanggotaan', 'aktif')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 35')->count(),
            'Dewasa (36-59)' => Member::where('status_keanggotaan', 'aktif')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 36 AND 59')->count(),
            'Lansia (60+)' => Member::where('status_keanggotaan', 'aktif')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 60')->count(),
        ];

        // 5. Status Sakramen (Jiwa Aktif Berdasarkan Riwayat Peristiwa)
        $sacramentData = [
            'Baptis' => Member::where('status_keanggotaan', 'aktif')->whereHas('events', fn($q) => $q->whereHas('eventType', fn($e) => $e->where('kode', 'BAPTIS')))->count(),
            'Sidi' => Member::where('status_keanggotaan', 'aktif')->whereHas('events', fn($q) => $q->whereHas('eventType', fn($e) => $e->where('kode', 'SIDI')))->count(),
            'Nikah' => Member::where('status_keanggotaan', 'aktif')->whereHas('events', fn($q) => $q->whereHas('eventType', fn($e) => $e->where('kode', 'NIKAH')->orWhere('kode', 'PENEGUHAN')))->count(),
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
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get()
        ]);
    }
}