<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\RefPosition;
use App\Models\ChurchOfficer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil/Buat Jabatan
        $posPdt = RefPosition::where('nama', 'Pendeta')->first();
        $posVic = RefPosition::where('nama', 'Vicaris')->first();
        $posKoster = RefPosition::where('nama', 'Koster Pusat')->first();

        // 2. Mapping Data dari Foto RAPB
        $staffData = [
            [
                'nama' => 'Pdt. Alponia Malo, S.Th',
                'role' => $posPdt->id,
                'status' => 'organik',
                'lokasi' => 'pusat',
                'gaji' => 4860500,
                'pensiun' => 488000,
                'rumah' => 250000
            ],
            [
                'nama' => 'Vicaris',
                'role' => $posVic->id,
                'status' => 'vicaris',
                'lokasi' => 'pusat',
                'gaji' => 1500000,
                'pensiun' => 165000,
                'rumah' => 0
            ]
        ];

        foreach ($staffData as $data) {
            $member = Member::where('nama', 'like', "%{$data['nama']}%")->first();
            if ($member) {
                ChurchOfficer::create([
                    'uuid' => Str::uuid(),
                    'member_id' => $member->id,
                    'ref_position_id' => $data['role'],
                    'status_kepegawaian' => $data['status'],
                    'lokasi_tugas' => $data['lokasi'],
                    'gaji_pokok' => $data['gaji'],
                    'iuran_pensiun' => $data['pensiun'],
                    'tunjangan_perumahan' => $data['rumah'],
                    'is_active' => true,
                ]);
            }
        }
    }
}