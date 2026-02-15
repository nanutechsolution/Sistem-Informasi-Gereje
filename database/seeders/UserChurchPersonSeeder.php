<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserChurchPersonSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {

            // Skip jika sudah punya church_person_id
            if ($user->church_person_id) {
                continue;
            }

            // Kalau user tidak punya member_id, skip
            if (!$user->member_id) {
                continue;
            }

            // Ambil church_person_id dari member
            $member = DB::table('members')
                ->where('id', $user->member_id)
                ->first();

            if ($member && $member->church_person_id) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'church_person_id' => $member->church_person_id
                    ]);
            }
        }

        $this->command->info('Users → Church People berhasil di-link!');
    }
}
