<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Member;
use App\Models\RefWilayah;
use App\Models\RefHubunganKeluarga;
use App\Models\RefPekerjaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Data Master untuk Relasi
        $wilayahs = RefWilayah::all();
        $pekerjaans = RefPekerjaan::all();
        
        // Ambil ID Hubungan Keluarga secara spesifik
        $refKK = RefHubunganKeluarga::where('nama', 'Kepala Keluarga')->first()->id;
        $refIstri = RefHubunganKeluarga::where('nama', 'Istri')->first()->id;
        $refAnak = RefHubunganKeluarga::where('nama', 'Anak')->first()->id;

        // 2. Loop Pembuatan 10 Keluarga
        for ($i = 1; $i <= 10; $i++) {
            $namaBapak = fake('id_ID')->name('male');
            
            $family = Family::create([
                'uuid' => (string) Str::uuid(),
                'nomor_kk' => fake()->unique()->numerify('5309##########'),
                'kepala_keluarga' => $namaBapak,
                'wilayah_id' => $wilayahs->random()->id,
                'alamat' => fake('id_ID')->address(),
                'status' => 'aktif',
            ]);

            // 3. Tambah 5 Anggota Keluarga per KK
            
            // Member 1: Kepala Keluarga (Laki-laki)
            Member::create([
                'uuid' => (string) Str::uuid(),
                'family_id' => $family->id,
                'nama' => $namaBapak,
                'nik' => fake()->unique()->numerify('5309##########'),
                'jenis_kelamin' => 'L',
                'hubungan_keluarga_id' => $refKK,
                'pekerjaan_id' => $pekerjaans->random()->id,
                'tempat_lahir' => fake('id_ID')->city(),
                'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-35 years'),
                'status_baptis' => 'Sudah',
                'status_sidi' => 'Sudah',
                'status_nikah' => 'Sudah',
            ]);

            // Member 2: Istri (Perempuan)
            Member::create([
                'uuid' => (string) Str::uuid(),
                'family_id' => $family->id,
                'nama' => fake('id_ID')->name('female'),
                'nik' => fake()->unique()->numerify('5309##########'),
                'jenis_kelamin' => 'P',
                'hubungan_keluarga_id' => $refIstri,
                'pekerjaan_id' => $pekerjaans->random()->id,
                'tempat_lahir' => fake('id_ID')->city(),
                'tanggal_lahir' => fake()->dateTimeBetween('-55 years', '-30 years'),
                'status_baptis' => 'Sudah',
                'status_sidi' => 'Sudah',
                'status_nikah' => 'Sudah',
            ]);

            // Member 3, 4, 5: Anak
            for ($j = 1; $j <= 3; $j++) {
                $gender = fake()->randomElement(['L', 'P']);
                Member::create([
                    'uuid' => (string) Str::uuid(),
                    'family_id' => $family->id,
                    'nama' => fake('id_ID')->name($gender == 'L' ? 'male' : 'female'),
                    'nik' => fake()->unique()->numerify('5309##########'),
                    'jenis_kelamin' => $gender,
                    'hubungan_keluarga_id' => $refAnak,
                    'pekerjaan_id' => $pekerjaans->where('nama', 'Pelajar/Mahasiswa')->first()->id ?? $pekerjaans->random()->id,
                    'tempat_lahir' => fake('id_ID')->city(),
                    'tanggal_lahir' => fake()->dateTimeBetween('-25 years', '-2 years'),
                    'status_baptis' => fake()->randomElement(['Sudah', 'Belum']),
                    'status_sidi' => 'Belum',
                    'status_nikah' => 'Belum',
                ]);
            }
        }
    }
}