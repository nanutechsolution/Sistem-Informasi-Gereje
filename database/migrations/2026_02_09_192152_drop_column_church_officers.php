<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            // Hapus kolom gaji statis karena sudah pindah ke tabel komponen
            $table->dropColumn([
                'gaji_pokok', 
                'tunjangan_perumahan', 
                'tunjangan_lain', 
                'iuran_pensiun'
            ]);

            // Hapus kolom relasi pos anggaran statis
            $table->dropForeign(['ref_budget_post_id']);
            $table->dropForeign(['ref_perumahan_post_id']);
            $table->dropForeign(['ref_pensiun_post_id']);
            $table->dropColumn([
                'ref_budget_post_id', 
                'ref_perumahan_post_id', 
                'ref_pensiun_post_id'
            ]);
        });
    }

    public function down(): void {
        // Rollback logic (opsional)
    }
};