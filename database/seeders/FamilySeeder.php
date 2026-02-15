<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;
use App\Models\RefWilayah;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // 1. Buat Master Data Wilayah
            $namaWilayah = [
                'Wilayah 1 (Pusat)',
                'Wilayah 2 (Timur)',
                'Wilayah 3 (Barat)',
                'Wilayah 4 (Selatan)',
                'Wilayah 5 (Utara)',
            ];

            $listWilayahId = [];
            foreach ($namaWilayah as $nama) {
                $w = RefWilayah::firstOrCreate(['nama' => $nama]);
                $listWilayahId[] = $w->id;
                $this->command->info("Wilayah dibuat: $nama");
            }

            // 2. Buat Data Keluarga (KK) Dummy
            for ($i = 1; $i <= 20; $i++) {
                // Pilih wilayah secara acak
                $randomWilayahId = $listWilayahId[array_rand($listWilayahId)];
                
                // Generate Nomor KK Acak (16 Digit)
                // Format: Kode Daerah (5301) + Random
                $nomorKk = '5301' . str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);

                Family::firstOrCreate(
                    ['nomor_kk' => $nomorKk], // Cek biar tidak duplikat
                    [
                        'uuid' => Str::uuid(),
                        'wilayah_id' => $randomWilayahId,
                        'alamat' => "Jalan Misi No. $i, Tambolaka",
                        'status' => 'aktif',
                        'keterangan' => "Data Import Seeder #$i"
                    ]
                );
            }

            $this->command->info("Berhasil membuat 20 Data Keluarga.");
        });
    }
}