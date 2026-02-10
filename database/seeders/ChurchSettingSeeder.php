<?php

namespace Database\Seeders;

use App\Models\ChurchSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChurchSettingSeeder extends Seeder
{
    public function run(): void
    {
        ChurchSetting::create([
            'uuid' => Str::uuid(),
            'nama_gereja' => 'GEREJA KRISTEN SUMBA',
            'nama_jemaat' => 'JEMAAT REDA PADA',
            'deskripsi_singkat' => 'Melayani dengan integritas melalui digitalisasi sistem informasi jemaat yang transparan dan akuntabel.',
            'alamat' => 'Jl. Lolo Ole, Kec. Kota Tambolaka, Sumba Barat Daya, NTT',
            'email' => 'jemaat@gksredapada.or.id',
            'telepon' => '+62 812-3456-7890',
            'warna_utama' => '#1e3a8a', // Biru GKS
            'warna_aksen' => '#d97706', // Emas/Amber
            'visi' => 'Menjadi Gereja yang Mandiri, Misioner dan Terbuka.',
            'misi' => [
                'Meningkatkan kualitas iman jemaat melalui Ibadah PKS.',
                'Membangun transparansi keuangan berbasis teknologi.',
                'Memberdayakan potensi pemuda dan kaum bapak/ibu.'
            ],
            'facebook' => 'GKSRedaPada',
            'instagram' => '@gksredapada',
            'youtube' => 'GKS Reda Pada Official'
        ]);
    }
}