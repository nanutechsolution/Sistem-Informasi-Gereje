<?php

namespace Database\Seeders;

use App\Models\RefEventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            // Kategori: ROHANI (Sakramen & Ibadah)
            ['nama' => 'Baptis Kudus', 'kategori' => 'rohani'],
            ['nama' => 'Sidi (Pengakuan Percaya)', 'kategori' => 'rohani'],
            ['nama' => 'Pernikahan Gerejawi', 'kategori' => 'rohani'],
            ['nama' => 'Peneguhan Pejabat (Majelis)', 'kategori' => 'rohani'],
            
            // Kategori: MUTASI (Perpindahan)
            ['nama' => 'Atestasi Masuk', 'kategori' => 'mutasi'],
            ['nama' => 'Atestasi Keluar', 'kategori' => 'mutasi'],
            
            // Kategori: SIPIL / LAINNYA
            ['nama' => 'Meninggal Dunia', 'kategori' => 'sipil'],
            ['nama' => 'Lahir Baru', 'kategori' => 'sipil'], // Jika ingin mencatat selain tgl lahir di KTP
        ];

        foreach ($events as $event) {
            RefEventType::firstOrCreate(
                ['nama' => $event['nama']], // Cek agar tidak duplikat
                ['kategori' => $event['kategori']]
            );
        }
    }
}