<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Family;
use App\Models\ServiceGroup;
use App\Models\ChurchOfficer;
use App\Models\RefPosition;
use App\Models\RefWilayah;
use App\Models\RefHubunganKeluarga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class KelompokMajelisSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Jabatan Master Tersedia
        $posPdt = RefPosition::firstOrCreate(['nama' => 'Pendeta'], ['uuid' => Str::uuid(), 'is_paid' => true]);
        $posVic = RefPosition::firstOrCreate(['nama' => 'Vicaris'], ['uuid' => Str::uuid(), 'is_paid' => true]);
        $posMjs = RefPosition::firstOrCreate(['nama' => 'Majelis Jemaat'], ['uuid' => Str::uuid(), 'is_paid' => false]);
        $wilayah1 = RefWilayah::firstOrCreate(['nama' => 'Wilayah 1'], ['uuid' => Str::uuid()]);
        $hubKK = RefHubunganKeluarga::where('nama', 'Kepala Keluarga')->first();

        // 2. Definisi Data dari Gambar
        $dataKelompok = [
            'Kelompok 1' => [
                ['nama' => 'Yohanis Umbu Lele', 'jabatan' => $posMjs],
                ['nama' => 'Margaretah Lende', 'jabatan' => $posMjs],
                ['nama' => 'Margaretha Dairo Loru', 'jabatan' => $posMjs],
                ['nama' => 'Selfiana Malo', 'jabatan' => $posMjs],
                ['nama' => 'Darius Ama Kii', 'jabatan' => $posMjs],
            ],
            'Kelompok 2' => [
                ['nama' => 'Pdt. Alponia Malo, S.Th', 'jabatan' => $posPdt],
                ['nama' => 'Nikodemus D. Wella', 'jabatan' => $posMjs],
                ['nama' => 'Mardiana Malo', 'jabatan' => $posMjs],
                ['nama' => 'Afrance Nata', 'jabatan' => $posMjs],
                ['nama' => 'Yuliana Bolu', 'jabatan' => $posMjs],
            ],
            'Kelompok 3' => [
                ['nama' => 'Ningsi R. D. Mosa', 'jabatan' => $posMjs],
                ['nama' => 'Melkianus R. Bulu', 'jabatan' => $posMjs],
                ['nama' => 'Damianus Wunda Deke', 'jabatan' => $posMjs],
                ['nama' => 'Vikaris', 'jabatan' => $posVic],
                ['nama' => 'Noviana T. Ina', 'jabatan' => $posMjs],
            ],
            'Kelompok 4' => [
                ['nama' => 'Benyamin T. Dona', 'jabatan' => $posMjs],
                ['nama' => 'Meriana D. Milla', 'jabatan' => $posMjs],
                ['nama' => 'Yuliana Bulu', 'jabatan' => $posMjs],
                ['nama' => 'Andi Nono', 'jabatan' => $posMjs],
                ['nama' => 'Crhistina Bulu', 'jabatan' => $posMjs],
            ],
        ];

        // 3. Eksekusi Pembuatan Data
        foreach ($dataKelompok as $namaGrup => $personils) {
            // Buat Kelompok Pelayanan
            $group = ServiceGroup::firstOrCreate(
                ['nama_kelompok' => $namaGrup],
                ['uuid' => Str::uuid(), 'is_active' => true]
            );

            foreach ($personils as $index => $p) {
                // A. Buat Kartu Keluarga Dummy (Jika belum ada)
                $family = Family::firstOrCreate(
                    ['kepala_keluarga' => $p['nama']],
                    [
                        'uuid' => Str::uuid(),
                        'nomor_kk' => fake()->unique()->numerify('5309##########'),
                        'wilayah_id' => $wilayah1->id,
                        'alamat' => 'Domisili Reda Pada',
                        'status' => 'aktif'
                    ]
                );

                // B. Buat Data Jemaat (Member)
                $member = Member::firstOrCreate(
                    ['nama' => $p['nama']],
                    [
                        'uuid' => Str::uuid(),
                        'family_id' => $family->id,
                        'nik' => fake()->unique()->numerify('5309##########'),
                        'jenis_kelamin' => (Str::contains(strtolower($p['nama']), ['yuliana', 'margaretha', 'ningsi', 'meriana', 'selfiana', 'crhistina'])) ? 'P' : 'L',
                        'hubungan_keluarga_id' => $hubKK->id,
                    ]
                );

                // C. Buat Akun Login (User)
                // Email format: nama.kelompok@gks.id
                $email = Str::slug($p['nama']) . '@gks.id';
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $p['nama'],
                        'password' => Hash::make('password'),
                        'role' => ($p['jabatan']->id == $posPdt->id) ? 'pendeta' : 'majelis',
                        'member_id' => $member->id
                    ]
                );

                // D. Buat Data Pejabat (Church Officer)
                $officer = ChurchOfficer::firstOrCreate(
                    ['member_id' => $member->id],
                    [
                        'uuid' => Str::uuid(),
                        'ref_position_id' => $p['jabatan']->id,
                        'status_kepegawaian' => ($p['jabatan']->id == $posPdt->id) ? 'organik' : 'majelis',
                        'is_active' => true,
                        'tanggal_mulai' => '2026-01-01'
                    ]
                );

                // E. Masukkan ke Kelompok (Pivot)
                // Orang pertama di list gambar dianggap 'Ketua Tim / PF'
                $peran = ($index === 0) ? 'Pembaca Firman' : 'Pendamping';
                $group->officers()->syncWithoutDetaching([
                    $officer->id => ['peran_default' => $peran]
                ]);
            }
        }
    }
}