<?php

namespace Database\Seeders;

use App\Models\ActivitySchedule;
use App\Models\ActivityServant;
use App\Models\RefActivityType;
use App\Models\Member;
use App\Models\RefWilayah;
use App\Models\Family;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Menjalankan seeding untuk manajemen jadwal dan pelayan.
     * Dirancang untuk mendukung simulasi agenda jemaat yang padat.
     */
    public function run(): void
    {
        // 1. Buat Master Jenis Kegiatan
        $types = [
            ['nama' => 'Ibadah Minggu', 'warna' => '#1e3a8a'],
            ['nama' => 'PKS (Ibadah Rumah Tangga)', 'warna' => '#d97706'],
            ['nama' => 'Rapat Majelis Jemaat', 'warna' => '#be123c'],
            ['nama' => 'Katekisasi', 'warna' => '#059669'],
            ['nama' => 'Ibadah Pemuda', 'warna' => '#7c3aed'],
            ['nama' => 'Ibadah Sekolah Minggu', 'warna' => '#ec4899'],
            ['nama' => 'Penghiburan / Kedukaan', 'warna' => '#4b5563'],
        ];

        foreach ($types as $t) {
            RefActivityType::firstOrCreate(
                ['nama' => $t['nama']],
                ['uuid' => Str::uuid(), 'warna_label' => $t['warna']]
            );
        }

        // 2. Ambil Data Pendukung
        $typeMinggu = RefActivityType::where('nama', 'Ibadah Minggu')->first();
        $typePKS = RefActivityType::where('nama', 'PKS (Ibadah Rumah Tangga)')->first();
        $typeRapat = RefActivityType::where('nama', 'Rapat Majelis Jemaat')->first();

        $members = Member::all();
        $families = Family::all();

        if ($members->isEmpty()) {
            return; // Pastikan MemberSeeder sudah dijalankan
        }
        // 3. Simulasi Ibadah Minggu (4 Minggu ke Depan)
        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::now()->next(Carbon::SUNDAY)->addWeeks($i);

            $service = ActivitySchedule::create([
                'uuid' => Str::uuid(),
                'ref_activity_type_id' => $typeMinggu->id,
                'tanggal' => $date->format('Y-m-d'),
                'jam_mulai' => '08:00:00',
                'tema' => 'Tema Minggu Ke-' . ($i + 1),
                'lokasi_manual' => 'Gedung Gereja Pusat',
                'status' => 'rencana',
            ]);

            // Penugasan Pelayan Utama
            $roles = [
                'Pengkhotbah' => $members->random(),
                'Liturgos' => $members->random(),
                'Pemusik 1' => $members->random(),
                'Pemusik 2' => $members->random(),
                'Kolektan 1' => $members->random(),
                'Kolektan 2' => $members->random(),
                'Operator Slide' => $members->random(),
            ];

            foreach ($roles as $role => $member) {
                ActivityServant::create([
                    'activity_schedule_id' => $service->id,
                    'member_id' => $member->id,
                    'peran' => $role,
                ]);
            }
        }

        // 4. Simulasi PKS (Ibadah Rumah Tangga) di setiap Wilayah
        if ($families->isNotEmpty()) {
            foreach ($families as $index => $family) {
                $pks = ActivitySchedule::create([
                    'uuid' => Str::uuid(),
                    'ref_activity_type_id' => $typePKS->id,
                    'ref_wilayah_id' => $family->wilayah_id,
                    'family_id' => $family->id,
                    'tanggal' => Carbon::now()->addDays($index + 1)->format('Y-m-d'),
                    'jam_mulai' => '18:00:00',
                    'tema' => 'Ibadah Syukur Keluarga',
                    'status' => 'rencana',
                ]);

                // Pelayan PKS (Lebih sederhana)
                ActivityServant::create([
                    'activity_schedule_id' => $pks->id,
                    'member_id' => $members->random()->id,
                    'peran' => 'Pelayan Firman',
                ]);
            }
        }

        // 5. Simulasi Rapat Bulanan
        ActivitySchedule::create([
            'uuid' => Str::uuid(),
            'ref_activity_type_id' => $typeRapat->id,
            'tanggal' => Carbon::now()->startOfMonth()->addDays(14)->format('Y-m-d'),
            'jam_mulai' => '10:00:00',
            'tema' => 'Evaluasi Pelayanan Triwulan',
            'lokasi_manual' => 'Ruang Konsistori',
            'status' => 'rencana',
        ]);
    }
}
