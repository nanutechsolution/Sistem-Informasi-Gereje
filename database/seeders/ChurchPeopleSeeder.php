<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChurchPeopleSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            [
                'nik' => '5301010101010001',
                'full_name' => 'Budi Santoso',
                'gender' => 'L',
                'place_of_birth' => 'Jakarta',
                'date_of_birth' => '1985-05-12',
                'phone' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
            [
                'nik' => '5301010101010002',
                'full_name' => 'Siti Aminah',
                'gender' => 'P',
                'place_of_birth' => 'Surabaya',
                'date_of_birth' => '1990-08-25',
                'phone' => '081234567891',
                'email' => 'siti.aminah@example.com',
                'address' => 'Jl. Mawar No. 5, Surabaya',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
            [
                'nik' => '5301010101010003',
                'full_name' => 'Yohanis Umbu Lele',
                'gender' => 'L',
                'place_of_birth' => 'Tambolaka',
                'date_of_birth' => '1978-03-15',
                'phone' => '081234567892',
                'email' => 'yohanis.umbu@example.com',
                'address' => 'Desa Lolo Ole, Sumba Barat Daya',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
            [
                'nik' => '5301010101010004',
                'full_name' => 'Maria Goretti',
                'gender' => 'P',
                'place_of_birth' => 'Kupang',
                'date_of_birth' => '1995-12-02',
                'phone' => '081234567893',
                'email' => 'maria.goretti@example.com',
                'address' => 'Oebobo, Kupang',
                'is_baptized' => 1,
                'is_sidi' => 0,
            ],
            [
                'nik' => '5301010101010005',
                'full_name' => 'Darius Ama Kii',
                'gender' => 'L',
                'place_of_birth' => 'Waingapu',
                'date_of_birth' => '1982-11-20',
                'phone' => '081234567894',
                'email' => 'darius.ama@example.com',
                'address' => 'Jl. Ahmad Yani No. 12, Waingapu',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
            [
                'nik' => '5301010101010006',
                'full_name' => 'Elisabeth Malo',
                'gender' => 'P',
                'place_of_birth' => 'Waitabula',
                'date_of_birth' => '2000-01-10',
                'phone' => '081234567895',
                'email' => 'elisabeth.malo@example.com',
                'address' => 'Reda Pada, Tambolaka',
                'is_baptized' => 1,
                'is_sidi' => 0,
            ],
            [
                'nik' => '5301010101010007',
                'full_name' => 'Ahmad Hidayat',
                'gender' => 'L',
                'place_of_birth' => 'Bandung',
                'date_of_birth' => '1988-07-07',
                'phone' => '081234567896',
                'email' => 'ahmad.hidayat@example.com',
                'address' => 'Jl. Asia Afrika, Bandung',
                'is_baptized' => 0,
                'is_sidi' => 0,
            ],
            [
                'nik' => '5301010101010008',
                'full_name' => 'Ni Luh Putu',
                'gender' => 'P',
                'place_of_birth' => 'Denpasar',
                'date_of_birth' => '1992-04-18',
                'phone' => '081234567897',
                'email' => 'niluh.putu@example.com',
                'address' => 'Sanur, Denpasar',
                'is_baptized' => 0,
                'is_sidi' => 0,
            ],
            [
                'nik' => '5301010101010009',
                'full_name' => 'Joko Widodo',
                'gender' => 'L',
                'place_of_birth' => 'Solo',
                'date_of_birth' => '1961-06-21',
                'phone' => '081234567898',
                'email' => 'joko.widodo@example.com',
                'address' => 'Manahan, Solo',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
            [
                'nik' => '5301010101010010',
                'full_name' => 'Margaretha Dairo Loru',
                'gender' => 'P',
                'place_of_birth' => 'Waikabubak',
                'date_of_birth' => '1980-09-30',
                'phone' => '081234567899',
                'email' => 'margaretha.dairo@example.com',
                'address' => 'Jl. Bhayangkara, Waikabubak',
                'is_baptized' => 1,
                'is_sidi' => 1,
            ],
        ];

        foreach ($people as $person) {
            DB::table('church_people')->updateOrInsert(
                ['nik' => $person['nik']],
                array_merge($person, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}