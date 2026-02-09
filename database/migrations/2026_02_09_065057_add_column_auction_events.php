<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambahkan kolom Pos Anggaran ke tabel Lelang Event untuk integrasi RAPB.
     */
    public function up(): void {
        Schema::table('auction_events', function (Blueprint $table) {
            $table->foreignId('ref_budget_post_id')
                  ->nullable()
                  ->after('tujuan_kas')
                  ->constrained('ref_budget_posts')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('auction_events', function (Blueprint $table) {
            $table->dropForeign(['ref_budget_post_id']);
            $table->dropColumn('ref_budget_post_id');
        });
    }
};