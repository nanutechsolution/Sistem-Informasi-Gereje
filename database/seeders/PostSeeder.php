<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first(); // Ambil user admin sebagai penulis

        $posts = [
            [
                'judul' => 'Persiapan Sidang Majelis Jemaat Triwulan I',
                'konten' => 'Diharapkan seluruh Majelis Jemaat dapat hadir dalam rapat koordinasi persiapan Sidang Triwulan I yang akan dilaksanakan pada akhir bulan ini.',
                'kategori' => 'Pengumuman',
                'gambar_fitur' => 'news/sidang.jpg',
            ],
            [
                'judul' => 'Menemukan Sukacita Dalam Kesederhanaan Melayani',
                'konten' => 'Melayani Tuhan bukan soal seberapa besar panggung yang kita miliki, melainkan seberapa tulus hati kita memberikan yang terbaik bagi kemuliaan-Nya.',
                'kategori' => 'Renungan',
                'gambar_fitur' => 'news/renungan.jpg',
            ],
            [
                'judul' => 'Laporan Singkat Pembangunan Gedung Serbaguna',
                'konten' => 'Proses pembangunan saat ini telah mencapai tahap pemasangan atap. Terima kasih atas dukungan doa dan dana dari seluruh jemaat.',
                'kategori' => 'Berita',
                'gambar_fitur' => 'news/progres.jpg',
            ],
        ];

        foreach ($posts as $p) {
            Post::create([
                'uuid' => (string) Str::uuid(),
                'judul' => $p['judul'],
                'konten' => $p['konten'],
                'kategori' => $p['kategori'],
                'gambar_fitur' => $p['gambar_fitur'],
                'user_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }
}