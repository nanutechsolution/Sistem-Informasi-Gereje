<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['nama' => 'Kg', 'is_active' => true],
            ['nama' => 'Liter', 'is_active' => true],
            ['nama' => 'Pcs', 'is_active' => true],
            ['nama' => 'Bungkus', 'is_active' => true],
            ['nama' => 'Dus', 'is_active' => true],
            ['nama' => 'Box', 'is_active' => true],
            ['nama' => 'Karung', 'is_active' => true],
            ['nama' => 'Meter', 'is_active' => true],
            ['nama' => 'Paket', 'is_active' => true],
            ['nama' => 'Rupiah', 'is_active' => true],
        ];

        foreach ($units as $unit) {
            DB::table('ref_units')->updateOrInsert(
                ['nama' => $unit['nama']],
                [
                    'is_active' => $unit['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}