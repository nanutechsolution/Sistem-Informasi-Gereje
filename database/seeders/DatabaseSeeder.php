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
        // User::factory(10)->create();

        // 1. Buat Akun Super Admin
        User::create([
            'name' => 'Administrator GKS',
            'email' => 'admin@gks.id',
            'role' => 'admin',
            'password' => Hash::make('password'), // Password default: password
        ]);

        // 2. Buat Akun Pendeta
        User::create([
            'name' => 'Pendeta Jemaat',
            'email' => 'pendeta@gks.id',
            'role' => 'pendeta',
            'password' => Hash::make('password'),
        ]);

        // 3. Buat Akun Majelis (Contoh)
        User::create([
            'name' => 'Majelis Jemaat',
            'email' => 'majelis@gks.id',
            'role' => 'majelis',
            'password' => Hash::make('password'),
        ]);

        // 4. Buat Akun Operator (Untuk input data harian)
        User::create([
            'name' => 'Operator Multimedia',
            'email' => 'operator@gks.id',
            'role' => 'operator',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            EventTypeSeeder::class,
            RefWilayahSeeder::class,
            RefPekerjaanSeeder::class,
            RefHubunganKeluargaSeeder::class,
            FinanceSeeder::class,
            // AuctionSeeder::class,
            PositionSeeder::class,
            FamilySeeder::class,
        ]);
    }
}
