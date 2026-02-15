<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberChurchPersonSeeder extends Seeder
{
    public function run(): void
    {
        $members = DB::table('members')->get();

        foreach ($members as $member) {

            // Skip jika sudah ada church_person_id
            if ($member->church_person_id) {
                continue;
            }

            // Cari berdasarkan NIK dulu
            $person = null;

            if ($member->nik) {
                $person = DB::table('church_people')
                    ->where('nik', $member->nik)
                    ->first();
            }

            // Jika tidak ketemu dan NIK kosong, cari berdasarkan nama + tanggal_lahir
            if (!$person) {
                $person = DB::table('church_people')
                    ->where('nama', $member->nama)
                    ->where('tanggal_lahir', $member->tanggal_lahir)
                    ->first();
            }

            if ($person) {
                DB::table('members')
                    ->where('id', $member->id)
                    ->update([
                        'church_person_id' => $person->id
                    ]);
            }
        }

        $this->command->info('Member → Church People berhasil di-link!');
    }
}
