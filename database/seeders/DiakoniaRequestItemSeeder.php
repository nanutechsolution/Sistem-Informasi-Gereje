<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiakoniaRequest;
use App\Models\DiakoniaRequestItem;

class DiakoniaRequestItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua request yang ada
        $requests = DiakoniaRequest::all();

        foreach ($requests as $request) {

            // Item 1 - Uang
            DiakoniaRequestItem::create([
                'diakonia_request_id' => $request->id,
                'nama_item' => 'Uang Tunai',
                'nominal' => rand(100000, 500000),
            ]);

            // Item 2 - Beras
            DiakoniaRequestItem::create([
                'diakonia_request_id' => $request->id,
                'nama_item' => 'Beras',
                'qty' => 10,
                'satuan' => 'kg',
            ]);
        }
    }
}