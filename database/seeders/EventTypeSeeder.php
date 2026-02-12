<?php

namespace Database\Seeders;

use App\Models\RefEventType;
use Illuminate\Database\Seeder;
class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $events = [

            // ROHANI
            ['kode' => 'BAPTIS', 'nama' => 'Baptis Kudus', 'kategori' => 'rohani'],
            ['kode' => 'SIDI', 'nama' => 'Sidi (Pengakuan Percaya)', 'kategori' => 'rohani'],
            ['kode' => 'NIKAH', 'nama' => 'Pernikahan Gerejawi', 'kategori' => 'rohani'],
            ['kode' => 'PENEGUHAN', 'nama' => 'Peneguhan Pejabat (Majelis)', 'kategori' => 'rohani'],

            // MUTASI
            ['kode' => 'MUTASI_MASUK', 'nama' => 'Atestasi Masuk', 'kategori' => 'mutasi'],
            ['kode' => 'MUTASI_KELUAR', 'nama' => 'Atestasi Keluar', 'kategori' => 'mutasi'],

            // SIPIL
            ['kode' => 'MENINGGAL', 'nama' => 'Meninggal Dunia', 'kategori' => 'sipil'],
            ['kode' => 'LAHIR', 'nama' => 'Lahir Baru', 'kategori' => 'sipil'],
        ];

        foreach ($events as $event) {
            RefEventType::updateOrCreate(
                ['kode' => $event['kode']], // 🔥 pakai kode sebagai kunci utama
                [
                    'nama' => $event['nama'],
                    'kategori' => $event['kategori'],
                ]
            );
        }
    }
}
