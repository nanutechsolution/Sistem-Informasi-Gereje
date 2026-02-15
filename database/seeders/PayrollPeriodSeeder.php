<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollPeriod;
use Carbon\Carbon;

class PayrollPeriodSeeder extends Seeder
{
    public function run()
    {
        $months = [
            ['2026-01', '2026-01-01', '2026-01-31'],
            ['2026-02', '2026-02-01', '2026-02-28'],
            ['2026-03', '2026-03-01', '2026-03-31'],
        ];

        foreach ($months as $m) {
            PayrollPeriod::updateOrCreate(
                ['kode' => $m[0]],
                [
                    'tanggal_mulai' => $m[1],
                    'tanggal_selesai' => $m[2],
                    'fiscal_year_id' => 1,
                    'status' => 'draft'
                ]
            );
        }
    }
}
