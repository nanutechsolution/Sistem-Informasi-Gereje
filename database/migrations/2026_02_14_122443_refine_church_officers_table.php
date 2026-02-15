<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('church_officers', function (Blueprint $table) {

            // Drop foreign key dulu
            $table->dropForeign(['church_person_id']);

            // Baru drop kolom
            $table->dropColumn('church_person_id');

            // Tambah unique constraint jabatan aktif
            $table->unique(
                ['member_id', 'ref_position_id', 'is_active'],
                'unique_active_position_per_member'
            );
        });

        // Tambah check constraint (MySQL 8+)
        DB::statement("
        ALTER TABLE church_officers
        ADD CONSTRAINT check_tanggal_valid
        CHECK (
            tanggal_selesai IS NULL
            OR tanggal_selesai >= tanggal_mulai
        )
    ");
    }


    public function down(): void
    {
        Schema::table('church_officers', function (Blueprint $table) {

            // Kembalikan kolom
            $table->foreignId('church_person_id')
                ->nullable()
                ->constrained('church_people')
                ->cascadeOnDelete();

            $table->dropUnique('unique_active_position_per_member');

            DB::statement("
                ALTER TABLE church_officers
                DROP CHECK check_tanggal_valid
            ");
        });
    }
};
