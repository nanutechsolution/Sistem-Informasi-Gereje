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
        User::create([
            'name' => 'Administrator GKS',
            'email' => 'admin@gks.id',
            'role' => 'admin',
            'password' => Hash::make('password'), // Password default: password
        ]);
        $this->call([
            EventTypeSeeder::class,
            RefWilayahSeeder::class,
            RefPekerjaanSeeder::class,
            RefHubunganKeluargaSeeder::class,
            FinanceSeeder::class,
            PositionSeeder::class,
            ActivityTypeSeeder::class,
            ConstructionSeeder::class,
            DueTypeSeeder::class,
            RoleSeeder::class,
            FullDeploymentSeeder::class,
            ServiceGroupSeeder::class,
            ChurchSettingSeeder::class,
            SacramentTypeSeeder::class,
            RefDiakoniaTypeSeeder::class,
            RefUnitSeeder::class,
            // DiakoniaRequestSeeder::class,
            // DiakoniaRequestItemSeeder::class
        ]);
    }
}
