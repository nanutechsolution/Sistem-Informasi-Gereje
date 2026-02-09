<?php

namespace Database\Seeders;

use App\Models\ChurchOfficer;
use App\Models\OfficerSalaryComponent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MigrateSalaryComponentsSeeder extends Seeder
{
    public function run(): void
    {
        $officers = ChurchOfficer::all();
        foreach ($officers as $off) {
            // 1. Migrasi Gaji Pokok
            if ($off->gaji_pokok > 0) {
                OfficerSalaryComponent::create([
                    'uuid' => Str::uuid(),
                    'church_officer_id' => $off->id,
                    'ref_budget_post_id' => $off->ref_budget_post_id,
                    'nama_komponen' => 'Gaji Pokok / Pemeliharaan',
                    'jenis' => 'penerimaan',
                    'nominal' => $off->gaji_pokok,
                    'is_active' => true,
                    'tanggal_mulai' => now(),
                ]);
            }

            // 2. Migrasi Tunjangan Perumahan
            if ($off->tunjangan_perumahan > 0) {
                OfficerSalaryComponent::create([
                    'uuid' => Str::uuid(),
                    'church_officer_id' => $off->id,
                    'ref_budget_post_id' => $off->ref_perumahan_post_id,
                    'nama_komponen' => 'Tunjangan Perumahan',
                    'jenis' => 'penerimaan',
                    'nominal' => $off->tunjangan_perumahan,
                    'is_active' => true,
                    'tanggal_mulai' => now(),
                ]);
            }

            // 3. Migrasi Iuran Pensiun (Sebagai Komponen Terpisah)
            // Sesuai keputusan: Pensiun dibayar gereja (bukan potongan THP), 
            // tapi kita catat sebagai komponen 'informasi' atau pengeluaran terpisah di payroll nanti.
            // Untuk saat ini kita masukkan sebagai 'penerimaan' khusus atau 'potongan' tergantung kebijakan teknis transfernya.
            // Jika uangnya ditransfer ke Pendeta dulu baru disetor -> Penerimaan.
            // Jika gereja langsung setor -> Tidak perlu masuk komponen gaji Pendeta (hanya beban pengeluaran gereja).

            // Asumsi: Sesuai dokumen RAPB, ini adalah pos terpisah. 
            // Kita skip input ke komponen gaji personil agar tidak merancukan THP, 
            // KECUALI jika itu potongan gaji murni.
        }
    }
}
