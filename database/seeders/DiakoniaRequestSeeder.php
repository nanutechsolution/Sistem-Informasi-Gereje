<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\DiakoniaRequest;
use App\Models\Member;
use App\Models\RefDiakoniaType;
use App\Models\FiscalYear;

class DiakoniaRequestSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();
        $types = RefDiakoniaType::all();
        $fiscalYear = FiscalYear::first();

        if ($members->isEmpty() || $types->isEmpty() || !$fiscalYear) {
            $this->command->warn('Seeder DiakoniaRequest gagal: data master belum ada.');
            return;
        }

        foreach ($members->take(5) as $member) {

            DiakoniaRequest::create([
                'uuid' => Str::uuid(),
                'member_id' => $member->id,
                'ref_diakonia_type_id' => $types->random()->id,
                'fiscal_year_id' => $fiscalYear->id,
                'nominal' => 0, // akan dihitung dari item
                'tanggal_pemberian' => now(),
                'status' => 'draft',
            ]);
        }
    }
}