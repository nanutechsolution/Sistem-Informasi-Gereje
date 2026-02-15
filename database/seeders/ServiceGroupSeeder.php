<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChurchPeople;
use Illuminate\Support\Facades\DB;

class ServiceGroupSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Kita fokus ke Data Nama Orang saja dulu
            // Struktur array disederhanakan (tanpa jabatan)
            $dataPersonil = [
                'Kelompok 1' => [
                    'Yohanis Umbu Lele',
                    'Margaretah Lende',
                    'Margaretha Dairo Loru',
                    'Selfiana Malo',
                    'Darius Ama Kii',
                ],
                'Kelompok 2' => [
                    'Pdt. Alponia Malo, S.Th',
                    'Nikodemus D. Wella',
                    'Mardiana Malo',
                    'Afrance Nata',
                    'Yuliana Bolu',
                ],
                'Kelompok 3' => [
                    'Ningsi R. D. Mosa',
                    'Melkianus R. Bulu',
                    'Damianus Wunda Deke',
                    'Vikaris',
                    'Noviana T. Ina',
                ],
                'Kelompok 4' => [
                    'Benyamin T. Dona',
                    'Meriana D. Milla',
                    'Yuliana Bulu',
                    'Andi Nono',
                    'Crhistina Bulu',
                ],
            ];

            // Loop hanya untuk insert ke tabel church_people
            foreach ($dataPersonil as $kelompok => $listNama) {
                foreach ($listNama as $nama) {
                    // Gunakan firstOrCreate agar tidak duplikat jika seeder dijalankan berulang
                    ChurchPeople::firstOrCreate(
                        ['full_name' => $nama], // Cek berdasarkan nama
                        [
                            'nik' => null, // NIK bisa dilengkapi nanti
                            'gender' => 'L', // Default, bisa diedit nanti
                            'place_of_birth' => 'Sumba',
                            'date_of_birth' => '1980-01-01', // Tanggal lahir default
                            'address' => 'Data Migrasi ' . $kelompok, // Penanda asal data
                        ]
                    );
                    
                    $this->command->info("Input Orang: $nama ($kelompok)");
                }
            }
        });
    }
}