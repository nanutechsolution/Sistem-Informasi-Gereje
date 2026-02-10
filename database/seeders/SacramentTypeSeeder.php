<?php

namespace Database\Seeders;

use App\Models\RefSacramentType;
use Illuminate\Database\Seeder;

class SacramentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nama' => 'Baptis Anak', 'kode' => 'BPT-A'],
            ['nama' => 'Baptis Dewasa', 'kode' => 'BPT-D'],
            ['nama' => 'Sidi (Pengakuan Iman)', 'kode' => 'SDI'],
            ['nama' => 'Pernikahan Kudus', 'kode' => 'NKH'],
        ];

        foreach ($types as $type) {
            RefSacramentType::updateOrCreate(['kode' => $type['kode']], $type);
        }
    }
}