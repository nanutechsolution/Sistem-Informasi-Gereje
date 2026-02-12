<?php

namespace Database\Seeders;

use App\Models\RefDiakoniaType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefDiakoniaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['nama' => 'Baptis Anak', 'kode' => 'BPT-A'],
            ['nama' => 'Baptis Dewasa', 'kode' => 'BPT-D'],
            ['nama' => 'Sidi (Pengakuan Iman)', 'kode' => 'SDI'],
            ['nama' => 'Pernikahan Kudus', 'kode' => 'NKH'],
        ];

        foreach ($types as $type) {
            RefDiakoniaType::updateOrCreate(['nama' => $type['nama']]);
        }
    }
}
