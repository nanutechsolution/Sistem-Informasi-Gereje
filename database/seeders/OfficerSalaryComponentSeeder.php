<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficerSalaryComponent;
use App\Models\ChurchOfficer;
use App\Models\RefBudgetPost;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OfficerSalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $officers = ChurchOfficer::take(5)->get();
        $budgetPosts = RefBudgetPost::take(5)->get();

        foreach ($officers as $officer) {
            // Contoh komponen tetap
            OfficerSalaryComponent::create([
                'church_officer_id' => $officer->id,
                'ref_budget_post_id' => $budgetPosts->random()->id ?? null,
                'nama_komponen' => 'Gaji Pokok',
                'jenis' => 'penerimaan',
                'nominal' => 5000000,
                'is_fixed' => true,
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_berakhir' => null,
                'is_active' => true,
            ]);

            // Contoh tunjangan variabel
            OfficerSalaryComponent::create([
                'church_officer_id' => $officer->id,
                'ref_budget_post_id' => $budgetPosts->random()->id ?? null,
                'nama_komponen' => 'Tunjangan Transport',
                'jenis' => 'penerimaan',
                'nominal' => 500000,
                'is_fixed' => false,
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_berakhir' => null,
                'is_active' => true,
            ]);

            // Contoh potongan
            OfficerSalaryComponent::create([
                'church_officer_id' => $officer->id,
                'ref_budget_post_id' => $budgetPosts->random()->id ?? null,
                'nama_komponen' => 'Iuran Pensiun',
                'jenis' => 'potongan',
                'nominal' => 250000,
                'is_fixed' => true,
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_berakhir' => null,
                'is_active' => true,
            ]);
        }
    }
}
