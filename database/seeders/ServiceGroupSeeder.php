<?php

namespace Database\Seeders;

use App\Models\ServiceGroup;
use App\Models\ChurchOfficer;
use App\Models\RefWilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceGroupSeeder extends Seeder
{
    /**
     * Membuat 4 Kelompok Pelayanan dengan role default (PF & Pendamping).
     */
    public function run(): void
    {
        $officers = ChurchOfficer::active()->get();

        if ($officers->count() < 20) {
            $this->command->warn("Peringatan: Jumlah pegawai aktif hanya {$officers->count()}.");
        }

        // Acak pegawai
        $shuffledOfficers = $officers->shuffle();
        $groupsChunks = $shuffledOfficers->chunk(5);
        $wilayahs = RefWilayah::all();

        $counter = 1;
        foreach ($groupsChunks as $chunk) {
            if ($counter > 4) break;

            $group = ServiceGroup::create([
                'uuid' => (string) Str::uuid(),
                'nama_kelompok' => "Kelompok Pelayanan $counter",
                'ref_wilayah_id' => $wilayahs->isNotEmpty() ? $wilayahs->random()->id : null,
                'is_active' => true,
            ]);

            // UPDATE LOGIC: Set Peran Default
            // Orang pertama di list jadi 'Pembaca Firman', sisanya 'Pendamping'
            $isFirst = true;
            foreach ($chunk as $officer) {
                $peran = $isFirst ? 'Pembaca Firman' : 'Pendamping';
                
                $group->officers()->attach($officer->id, [
                    'peran_default' => $peran
                ]);
                
                $isFirst = false;
            }

            $this->command->info("Kelompok $counter: {$chunk->first()->member->nama} set sebagai PF Default.");
            $counter++;
        }
    }
}