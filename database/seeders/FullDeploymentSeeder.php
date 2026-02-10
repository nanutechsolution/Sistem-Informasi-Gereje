<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Family;
use App\Models\Member;
use App\Models\RefWilayah;
use App\Models\RefPosition;
use App\Models\ChurchOfficer;
use App\Models\RefHubunganKeluarga;
use App\Models\RefPekerjaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class FullDeploymentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETUP MASTER DATA (Wilayah & Jabatan)
        $this->setupMasterData();

        // 2. BUAT 60 KARTU KELUARGA (KK) & ANGGOTA JEMAAT
        $this->command->info('Sedang membuat 60 KK dan Jemaat...');
        $allMembers = $this->createFamiliesAndMembers(10);

        // 3. ANGKAT PEJABAT (20 Orang) DARI JEMAAT YANG ADA
        // 1 Pendeta, 1 Vicaris, 18 Majelis/Staf
        $this->appointOfficers($allMembers);
    }

    private function setupMasterData()
    {
        // Pastikan Wilayah Ada
        for ($i = 1; $i <= 10; $i++) {
            // FIX: Tambahkan parameter kedua untuk values (uuid)
            RefWilayah::firstOrCreate(
                ['nama' => "Wilayah $i"],
                ['uuid' => (string) Str::uuid()]
            );
        }

        // Pastikan Pekerjaan Ada
        $jobs = ['Petani', 'PNS', 'Wiraswasta', 'Pelajar', 'Guru', 'TNI/Polri', 'Ibu Rumah Tangga'];
        foreach ($jobs as $job) {
            RefPekerjaan::firstOrCreate(
                ['nama' => $job],
                ['uuid' => (string) Str::uuid()]
            );
        }

        // Pastikan Hubungan Keluarga Ada
        $rels = ['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain'];
        foreach ($rels as $r) {
            RefHubunganKeluarga::firstOrCreate(
                ['nama' => $r],
                ['uuid' => (string) Str::uuid()]
            );
        }
    }

    private function createFamiliesAndMembers($count)
    {
        $wilayahs = RefWilayah::all();
        $pekerjaans = RefPekerjaan::all();
        $hubKK = RefHubunganKeluarga::where('nama', 'Kepala Keluarga')->first();
        $hubIstri = RefHubunganKeluarga::where('nama', 'Istri')->first();
        $hubAnak = RefHubunganKeluarga::where('nama', 'Anak')->first();

        $createdMembers = collect();

        for ($i = 1; $i <= $count; $i++) {
            $namaBapak = fake('id_ID')->name('male');

            // Buat KK
            $family = Family::create([
                'uuid' => (string) Str::uuid(), // FIX: Generate UUID manual
                'nomor_kk' => fake()->unique()->numerify('5301##########'),
                'kepala_keluarga' => $namaBapak,
                'wilayah_id' => $wilayahs->random()->id,
                'alamat' => fake('id_ID')->address(),
                'status' => 'aktif',
            ]);

            // Buat Bapak
            $bapak = Member::create([
                'uuid' => (string) Str::uuid(), // FIX: Generate UUID manual
                'family_id' => $family->id,
                'nama' => $namaBapak,
                'nik' => fake()->unique()->numerify('5301##########'),
                'jenis_kelamin' => 'L',
                'hubungan_keluarga_id' => $hubKK->id,
                'pekerjaan_id' => $pekerjaans->random()->id,
                'tempat_lahir' => fake('id_ID')->city(),
                'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-30 years'),
                'status_baptis' => 'Sudah',
                'status_sidi' => 'Sudah',
                'status_nikah' => 'Sudah',
            ]);
            $createdMembers->push($bapak);

            // Buat Ibu
            Member::create([
                'uuid' => (string) Str::uuid(), // FIX: Generate UUID manual
                'family_id' => $family->id,
                'nama' => fake('id_ID')->name('female'),
                'nik' => fake()->unique()->numerify('5301##########'),
                'jenis_kelamin' => 'P',
                'hubungan_keluarga_id' => $hubIstri->id,
                'pekerjaan_id' => $pekerjaans->random()->id,
                'tempat_lahir' => fake('id_ID')->city(),
                'tanggal_lahir' => fake()->dateTimeBetween('-55 years', '-25 years'),
                'status_baptis' => 'Sudah',
                'status_sidi' => 'Sudah',
                'status_nikah' => 'Sudah',
            ]);

            // Buat Anak (0-3 anak)
            $jumlahAnak = rand(0, 3);
            for ($j = 0; $j < $jumlahAnak; $j++) {
                Member::create([
                    'uuid' => (string) Str::uuid(), // FIX: Generate UUID manual
                    'family_id' => $family->id,
                    'nama' => fake('id_ID')->firstName(),
                    'nik' => fake()->unique()->numerify('5301##########'),
                    'jenis_kelamin' => fake()->randomElement(['L', 'P']),
                    'hubungan_keluarga_id' => $hubAnak->id,
                    'pekerjaan_id' => $pekerjaans->where('nama', 'Pelajar')->first()->id ?? null,
                    'tempat_lahir' => fake('id_ID')->city(),
                    'tanggal_lahir' => fake()->dateTimeBetween('-20 years', '-1 year'),
                    'status_baptis' => 'Sudah',
                    'status_sidi' => 'Belum',
                    'status_nikah' => 'Belum',
                ]);
            }
        }

        return $createdMembers;
    }

    private function appointOfficers($candidates)
    {
        $this->command->info('Mengangkat 20 Pejabat & Membuat Akun Login...');

        // Ambil Jabatan (Gunakan updateOrCreate untuk menghindari duplikat jika re-seed)
        $posPdt = RefPosition::updateOrCreate(['nama' => 'Pendeta'], ['uuid' => (string) Str::uuid(), 'singkatan' => 'Pdt', 'is_paid' => true]);
        $posVic = RefPosition::updateOrCreate(['nama' => 'Vicaris'], ['uuid' => (string) Str::uuid(), 'singkatan' => 'Vic', 'is_paid' => true]);
        $posSek = RefPosition::updateOrCreate(['nama' => 'Sekretaris Jemaat'], ['uuid' => (string) Str::uuid(), 'singkatan' => 'Sek', 'is_paid' => true]);
        $posBen = RefPosition::updateOrCreate(['nama' => 'Bendahara Jemaat'], ['uuid' => (string) Str::uuid(), 'singkatan' => 'Ben', 'is_paid' => true]);
        $posPnt = RefPosition::updateOrCreate(['nama' => 'Penatua'], ['uuid' => (string) Str::uuid(), 'singkatan' => 'Pnt', 'is_paid' => false]);

        // Acak urutan kandidat
        $shuffled = $candidates->shuffle();

        // 1. PENDETA (1 Orang)
        $this->createOfficerAccount($shuffled->pop(), $posPdt, 'pendeta@gks.id', 'pendeta', 'organik', 5000000);

        // 2. VICARIS (1 Orang)
        $this->createOfficerAccount($shuffled->pop(), $posVic, 'vic@gks.id', 'pendeta', 'vicaris', 1500000);

        // 3. SEKRETARIS (1 Orang)
        $this->createOfficerAccount($shuffled->pop(), $posSek, 'sekretaris@gks.id', 'sekretaris', 'non_organik', 750000);

        // 4. BENDAHARA (1 Orang)
        $this->createOfficerAccount($shuffled->pop(), $posBen, 'bendahara@gks.id', 'bendahara', 'non_organik', 750000);

        // 5. MAJELIS / STAFF LAIN (16 Orang)
        for ($i = 1; $i <= 10; $i++) {
            $majelis = $shuffled->pop();
            $email = "majelis{$i}@gks.id";
            $this->createOfficerAccount($majelis, $posPnt, $email, 'majelis', 'majelis', 0);
        }
    }

    private function createOfficerAccount($member, $position, $email, $role, $statusHr, $gaji)
    {
        // 1. Buat User Login
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $member->nama,
                'password' => Hash::make('password'),
                'role' => $role,
                'member_id' => $member->id, // Hubungkan User ke Member
            ]
        );

        // 2. Buat Data Pegawai
        ChurchOfficer::create([
            'uuid' => (string) Str::uuid(), // FIX: Generate UUID manual
            'member_id' => $member->id,
            'ref_position_id' => $position->id,
            'nip_gereja' => date('Y') . sprintf('%03d', rand(1, 999)),
            'status_kepegawaian' => $statusHr,
            'lokasi_tugas' => 'pusat',
            'nomor_sk' => 'SK-' . Str::upper(Str::random(5)) . '/2026',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => $statusHr == 'vicaris' ? '2026-06-01' : null,
            'is_active' => true,
        ]);

        $this->command->info("Created: {$position->nama} - Login: $email / password");
    }
}
