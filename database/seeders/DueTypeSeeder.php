<?php

namespace Database\Seeders;

use App\Models\RefDueType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DueTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // 1. Tipe UANG per Orang (Sidi)
            [
                'nama' => 'Iuran Tahunan Sidi',
                'target_level' => 'member',
                'unit_type' => 'money',
                'satuan_barang' => null,
            ],
            // 2. Tipe UANG per Keluarga (KK)
            [
                'nama' => 'Iuran Pembangunan (Tahunan)',
                'target_level' => 'family',
                'unit_type' => 'money',
                'satuan_barang' => null,
            ],
            // 3. Tipe BARANG per Keluarga (KK)
            [
                'nama' => 'Tanggungan Semen Pembangunan',
                'target_level' => 'family',
                'unit_type' => 'item',
                'satuan_barang' => 'Sack',
            ],
            // 4. Tipe BARANG per Keluarga (KK)
            [
                'nama' => 'Sumbangan Kursi Jemaat',
                'target_level' => 'family',
                'unit_type' => 'item',
                'satuan_barang' => 'Unit',
            ],
        ];

        foreach ($types as $t) {
            RefDueType::updateOrCreate(
                ['nama' => $t['nama']],
                [
                    'uuid' => (string) Str::uuid(),
                    'target_level' => $t['target_level'],
                    'unit_type' => $t['unit_type'],
                    'satuan_barang' => $t['satuan_barang'],
                    'is_active' => true,
                ]
            );
        }
    }
}