<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('church_officers', function (Blueprint $table) {

            // 1️⃣ Tambahkan index biasa dulu supaya FK tetap aman
            $table->index('member_id', 'idx_member_temp');
            $table->index('ref_position_id', 'idx_position_temp');
        });

        Schema::table('church_officers', function (Blueprint $table) {

            // 2️⃣ Sekarang aman drop unique lama
            $table->dropUnique('unique_active_position_per_member');

            // 3️⃣ Drop kolom is_active
            $table->dropColumn('is_active');

            // 4️⃣ Tambah unique elegant version
            $table->unique(
                ['member_id', 'ref_position_id', 'tanggal_selesai'],
                'unique_active_position'
            );
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('church_officers', function (Blueprint $table) {
            //
        });
    }
};
