<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Super Admin
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            // ChurchPeopleSeeder::class,
            EventTypeSeeder::class,
            RefWilayahSeeder::class,
            RefPekerjaanSeeder::class,
            RefHubunganKeluargaSeeder::class,
            FinanceSeeder::class,
            PositionSeeder::class,
            ConstructionSeeder::class,
            RefSalaryComponentSeeder::class,
            SacramentTypeSeeder::class,
            FamilySeeder::class,
            PayrollPeriodSeeder::class,
            ChurchSettingSeeder::class,
            ActivityTypeSeeder::class,
            RefDiakoniaTypeSeeder::class,
            DueTypeSeeder::class,
            RefUnitSeeder::class,
            // ChurchPeopleSeeder::class,
            // KelompokMajelisSeeder::class,
            // FullDeploymentSeeder::class,
            // ServiceGroupSeeder::class,
            // DiakoniaRequestSeeder::class,
            // DiakoniaRequestItemSeeder::class
        ]);
    }
}
