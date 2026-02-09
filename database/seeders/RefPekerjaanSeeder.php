<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class RefPekerjaanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Belum / Tidak Bekerja',
            'Pelajar / Mahasiswa',
            'Petani',
            'Peternak',
            'Nelayan',
            'PNS / ASN',
            'TNI / POLRI',
            'Guru / Dosen',
            'Tenaga Kesehatan',
            'Wiraswasta',
            'Pedagang',
            'Karyawan Swasta',
            'Buruh',
            'Pekerja Lepas',
            'Pendeta / Hamba Tuhan',
            'Pensiunan',
            'Ibu Rumah Tangga',
            'Lainnya',
        ];

        foreach ($data as $index => $nama) {
            FacadesDB::table('ref_pekerjaans')->insert([
                'uuid' => Str::uuid(),
                'nama' => $nama,
                'created_at' => now(),
            ]);
        }
    }
}
