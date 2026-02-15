<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefSalaryComponent;
use Illuminate\Support\Str;

class RefSalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['kode' => 'GJ_POKOK', 'nama' => 'Gaji Pokok', 'jenis' => 'penerimaan', 'is_taxable' => 1],
            ['kode' => 'TJ_TRANSPORT', 'nama' => 'Tunjangan Transport', 'jenis' => 'penerimaan', 'is_taxable' => 0],
            ['kode' => 'TJ_PERUM', 'nama' => 'Tunjangan Perumahan', 'jenis' => 'penerimaan', 'is_taxable' => 1],
            ['kode' => 'IUR_PENSIUN', 'nama' => 'Iuran Pensiun', 'jenis' => 'potongan', 'is_taxable' => 0],
            ['kode' => 'PAJAK', 'nama' => 'Pajak Penghasilan', 'jenis' => 'potongan', 'is_taxable' => 0],
        ];

        foreach ($components as $c) {
            RefSalaryComponent::updateOrCreate(
                ['kode' => $c['kode']],
                [
                    'nama' => $c['nama'],
                    'jenis' => $c['jenis'],
                    'is_taxable' => $c['is_taxable'],
                ]
            );
        }
    }
}
