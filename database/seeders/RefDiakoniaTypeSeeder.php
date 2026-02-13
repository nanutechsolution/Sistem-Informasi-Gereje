<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\RefDiakoniaType;

class RefDiakoniaTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Sakit',
            'Duka',
            'Pendidikan',
            'Bencana',
            'Sembako',
            'Modal Usaha',
            'Santunan Lansia',
            'Yatim Piatu',
            'Lain-lain'
        ];

        foreach ($types as $type) {
            RefDiakoniaType::create([
                'uuid' => Str::uuid(),
                'nama' => $type,
                'is_active' => true,
            ]);
        }
    }
}
