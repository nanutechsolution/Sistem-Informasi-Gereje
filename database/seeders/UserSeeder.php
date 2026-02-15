<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gereja.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('super_admin');
    }
}
