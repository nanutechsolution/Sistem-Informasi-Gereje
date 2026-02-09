<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class RefWilayahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Wilayah I',   'kode' => 'WIL-01', 'urutan' => 1],
            ['nama' => 'Wilayah II',  'kode' => 'WIL-02', 'urutan' => 2],
            ['nama' => 'Wilayah III', 'kode' => 'WIL-03', 'urutan' => 3],
            ['nama' => 'Wilayah IV',  'kode' => 'WIL-04', 'urutan' => 4],
            ['nama' => 'Wilayah V',   'kode' => 'WIL-05', 'urutan' => 5],
            // Cadangan jangka panjang
            ['nama' => 'Wilayah VI',  'kode' => 'WIL-06', 'urutan' => 6],
            ['nama' => 'Wilayah VII', 'kode' => 'WIL-07', 'urutan' => 7],
            ['nama' => 'Wilayah VIII','kode' => 'WIL-08', 'urutan' => 8],
        ];

        foreach ($data as $item) {
            FacadesDB::table('ref_wilayahs')->insert([
                'uuid' => Str::uuid(),
                'nama' => $item['nama'],
                'kode' => $item['kode'],
                'urutan' => $item['urutan'],
                'created_at' => now(),
            ]);
        }
    }
}
