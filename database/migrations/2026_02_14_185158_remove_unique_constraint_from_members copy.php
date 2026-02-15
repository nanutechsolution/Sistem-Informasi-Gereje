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
        // LANGKAH 1: Hapus Foreign Key terlebih dahulu
        // Kita pisahkan Schema::table agar query dieksekusi terpisah
        Schema::table('members', function (Blueprint $table) {
            // Gunakan nama constraint eksplisit 'members_family_id_foreign' 
            // agar lebih aman daripada array ['family_id']
            $table->dropForeign('members_family_id_foreign');
        });

        // LANGKAH 2: Hapus Index Unik (sekarang aman karena FK sudah lepas)
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique('unique_kepala_keluarga_per_family');
        });

        // LANGKAH 3: Pasang kembali Foreign Key
        // Laravel otomatis akan membuat index biasa (non-unique) untuk mendukung FK ini
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('family_id')
                  ->references('id')
                  ->on('families')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kondisi semula (index unik)
        // Kita harus drop FK dulu agar bisa memodifikasi indexnya
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign('members_family_id_foreign');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->unique(['family_id', 'hubungan_keluarga_id'], 'unique_kepala_keluarga_per_family');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->foreign('family_id')
                  ->references('id')
                  ->on('families')
                  ->onDelete('cascade');
        });
    }
};