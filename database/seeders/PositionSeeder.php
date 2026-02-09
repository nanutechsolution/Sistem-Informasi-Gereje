<?php

namespace Database\Seeders;

use App\Models\RefPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    /**
     * Jalankan database seeds untuk data jabatan/posisi pelayanan.
     */
    public function run(): void
    {
        $positions = [
            // Pejabat Rohani & Organik GKS
            ['nama' => 'Pendeta', 'singkatan' => 'Pdt', 'is_paid' => true, 'urutan' => 1],
            ['nama' => 'Vicaris', 'singkatan' => 'Vic', 'is_paid' => true, 'urutan' => 2],
            ['nama' => 'Penatua', 'singkatan' => 'Pnt', 'is_paid' => false, 'urutan' => 3],
            ['nama' => 'Diaken', 'singkatan' => 'Dk', 'is_paid' => false, 'urutan' => 4],
            
            // Personil / Karyawan Jemaat (Sesuai rincian Dokumen RAPB 2026)
            ['nama' => 'Sekretaris Jemaat', 'singkatan' => 'Sekjems', 'is_paid' => true, 'urutan' => 5],
            ['nama' => 'Bendahara Jemaat', 'singkatan' => 'Benjems', 'is_paid' => true, 'urutan' => 6],
            ['nama' => 'Bendahara Cabang', 'singkatan' => 'Bencab', 'is_paid' => true, 'urutan' => 7],
            ['nama' => 'Koster Pusat', 'singkatan' => 'Koster', 'is_paid' => true, 'urutan' => 8],
            ['nama' => 'Koster Cabang', 'singkatan' => 'Koster', 'is_paid' => true, 'urutan' => 9],
            ['nama' => 'Guru Sekolah Minggu Pusat', 'singkatan' => 'GSM', 'is_paid' => true, 'urutan' => 10],
            ['nama' => 'Guru Sekolah Minggu Cabang', 'singkatan' => 'GSM', 'is_paid' => true, 'urutan' => 11],
            ['nama' => 'Pemusik', 'singkatan' => 'Musisi', 'is_paid' => true, 'urutan' => 12],
            ['nama' => 'Operator Multimedia', 'singkatan' => 'IT', 'is_paid' => true, 'urutan' => 13],
            
            // Staf Lainnya
            ['nama' => 'Pengerja', 'singkatan' => 'Pjr', 'is_paid' => true, 'urutan' => 14],
        ];

        foreach ($positions as $p) {
            RefPosition::updateOrCreate(
                ['nama' => $p['nama']], // Unik berdasarkan nama
                [
                    'uuid' => (string) Str::uuid(),
                    'singkatan' => $p['singkatan'],
                    'is_paid' => $p['is_paid'],
                    'urutan' => $p['urutan'],
                ]
            );
        }
    }
}