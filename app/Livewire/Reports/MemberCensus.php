<?php

namespace App\Livewire\Reports;

use App\Models\Member;
use App\Models\Family;
use App\Models\RefWilayah;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MemberCensus extends Component
{
    public function render()
    {
        // 1. Statistik Dasar
        $totalKK = Family::count();
        $totalJiwa = Member::count();
        $genderData = Member::select('jenis_kelamin', DB::raw('count(*) as total'))->groupBy('jenis_kelamin')->pluck('total', 'jenis_kelamin');

        // 2. Statistik Wilayah
        $wilayahStats = RefWilayah::withCount('families')->get()->map(function($w) {
            $w->jiwa_count = Member::whereHas('family', fn($q) => $q->where('wilayah_id', $w->id))->count();
            return $w;
        });

        // 3. Statistik Umur (Kelompok Usia)
        $usiaData = [
            'Anak (0-12)' => Member::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 12')->count(),
            'Remaja (13-17)' => Member::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 13 AND 17')->count(),
            'Pemuda (18-35)' => Member::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 35')->count(),
            'Dewasa (36-59)' => Member::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 36 AND 59')->count(),
            'Lansia (60+)' => Member::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 60')->count(),
        ];

        // 4. Status Gerejawi
        $sacramentData = [
            'Sudah Baptis' => Member::where('status_baptis', 'Sudah')->count(),
            'Sudah Sidi' => Member::where('status_sidi', 'Sudah')->count(),
            'Sudah Nikah' => Member::where('status_nikah', 'Sudah')->count(),
        ];

        return view('livewire.reports.member-census', [
            'stats' => ['kk' => $totalKK, 'jiwa' => $totalJiwa, 'l' => $genderData['L'] ?? 0, 'p' => $genderData['P'] ?? 0],
            'wilayahStats' => $wilayahStats,
            'usiaData' => $usiaData,
            'sacramentData' => $sacramentData
        ]);
    }
}