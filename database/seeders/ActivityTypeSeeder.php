<?php

namespace Database\Seeders;

use App\Models\RefActivityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['nama' => 'Ibadah Minggu', 'warna' => '#1e3a8a'], // Biru Tua
            ['nama' => 'PKS (Ibadah Rumah Tangga)', 'warna' => '#d97706'], // Amber/Oranye
            ['nama' => 'Rapat Majelis Jemaat', 'warna' => '#be123c'], // Merah Rose
            ['nama' => 'Katekisasi', 'warna' => '#059669'], // Hijau Emerald
            ['nama' => 'Ibadah Pemuda', 'warna' => '#7c3aed'], // Ungu Violet
            ['nama' => 'Ibadah Sekolah Minggu', 'warna' => '#ec4899'], // Pink
            ['nama' => 'Penghiburan / Kedukaan', 'warna' => '#4b5563'], // Abu-abu
            ['nama' => 'Ibadah Kaum Bapak', 'warna' => '#1d4ed8'], // Biru
            ['nama' => 'Ibadah Kaum Ibu', 'warna' => '#db2777'], // Pink Tua
            ['nama' => 'Sidang Jemaat', 'warna' => '#0f172a'], // Hitam/Slate
        ];

        foreach ($types as $t) {
            RefActivityType::updateOrCreate(
                ['nama' => $t['nama']], // Cek agar tidak duplikat
                [
                    'uuid' => (string) Str::uuid(),
                    'warna_label' => $t['warna']
                ]
            );
        }
    }
}