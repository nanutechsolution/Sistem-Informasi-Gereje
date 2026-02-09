<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class RefHubunganKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Kepala Keluarga', 'urutan' => 1],
            ['nama' => 'Istri',           'urutan' => 2],
            ['nama' => 'Suami',           'urutan' => 2],
            ['nama' => 'Anak',            'urutan' => 3],
            ['nama' => 'Orang Tua',       'urutan' => 4],
            ['nama' => 'Menantu',         'urutan' => 5],
            ['nama' => 'Cucu',            'urutan' => 6],
            ['nama' => 'Keluarga Lain',     'urutan' => 99],
        ];

        foreach ($data as $item) {
            FacadesDB::table('ref_hubungan_keluargas')->insert([
                'uuid' => Str::uuid(),
                'nama' => $item['nama'],
                'urutan' => $item['urutan'],
                'created_at' => now(),
            ]);
        }
    }
}
