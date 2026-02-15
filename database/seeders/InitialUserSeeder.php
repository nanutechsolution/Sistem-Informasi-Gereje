<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Church Person
        $personId = DB::table('church_people')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Super Admin',
            'nik' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'jenis_kelamin' => 'L',
            'no_hp' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Buat User Login
        DB::table('users')->insert([
            'church_person_id' => $personId,
            'email' => 'admin@gereja.local',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info('Super Admin berhasil dibuat!');
    }
}
