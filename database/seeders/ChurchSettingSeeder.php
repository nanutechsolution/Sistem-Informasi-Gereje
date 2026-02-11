<?php

namespace Database\Seeders;

use App\Models\ChurchSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChurchSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data lama & reset auto increment
        DB::table('church_settings')->truncate();

        // Aktifkan kembali foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        ChurchSetting::create([
            'uuid' => (string) Str::uuid(),

            'nama_gereja' => 'GEREJA KRISTEN SUMBA',
            'nama_jemaat' => 'JEMAAT REDA PADA',

            'deskripsi_singkat' =>
                'Melayani dengan integritas melalui digitalisasi sistem informasi jemaat yang transparan, akuntabel dan profesional.',

            'alamat' =>
                'Jl. Lolo Ole, Kec. Kota Tambolaka, Sumba Barat Daya, NTT',

            'email' => 'jemaat@gksredapada.or.id',
            'telepon' => '+62 812-3456-7890',

            'warna_utama' => '#1e3a8a',
            'warna_aksen' => '#d97706',

            'logo_path' => null,

            'visi' =>
                'Menjadi Gereja yang Mandiri, Misioner dan Terbuka dalam pelayanan berbasis Firman Tuhan dan teknologi.',

            'misi' => json_encode([
                'Meningkatkan kualitas iman jemaat melalui ibadah dan pemuridan.',
                'Membangun sistem administrasi dan keuangan yang transparan.',
                'Mengembangkan pelayanan digital untuk menjangkau generasi muda.',
                'Memberdayakan jemaat dalam pelayanan sosial dan misi.'
            ]),

            'sejarah_singkat' =>
                'Gereja Kristen Sumba Jemaat Reda Pada berdiri sebagai bagian dari pelayanan GKS di wilayah Sumba Barat Daya. Seiring perkembangan zaman, gereja terus berbenah dalam pelayanan rohani maupun administrasi berbasis teknologi informasi.',

            'facebook' => 'https://facebook.com/gksredapada',
            'instagram' => 'https://instagram.com/gksredapada',
            'youtube' => 'https://youtube.com/@gksredapada',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
