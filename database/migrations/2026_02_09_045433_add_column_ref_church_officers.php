<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambahkan pemetaan pos anggaran spesifik untuk audit RAPB 20 tahun.
     */
    public function up(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            // Pos Anggaran untuk Gaji Pokok (Default: 2.1.x)
            $table->foreignId('ref_budget_post_id')
                  ->nullable()
                  ->after('ref_position_id')
                  ->constrained('ref_budget_posts')
                  ->nullOnDelete();

            // Pos Anggaran untuk Tunjangan Perumahan (Default: 2.3.x)
            $table->foreignId('ref_perumahan_post_id')
                  ->nullable()
                  ->after('ref_budget_post_id')
                  ->constrained('ref_budget_posts')
                  ->nullOnDelete();

            // Pos Anggaran untuk Iuran Pensiun (Default: 2.2.x)
            $table->foreignId('ref_pensiun_post_id')
                  ->nullable()
                  ->after('ref_perumahan_post_id')
                  ->constrained('ref_budget_posts')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            $table->dropForeign(['ref_perumahan_post_id']);
            $table->dropForeign(['ref_pensiun_post_id']);
            $table->dropColumn([ 'ref_perumahan_post_id', 'ref_pensiun_post_id']);
        });
    }
};