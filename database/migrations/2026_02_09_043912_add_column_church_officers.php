<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            // Menghubungkan personil langsung ke kode budget (misal 2.1.1)
            // $table->foreignId('ref_budget_post_id')->nullable()->after('ref_position_id')->constrained();
        });
    }

    public function down(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            $table->dropForeign(['ref_budget_post_id']);
            $table->dropColumn('ref_budget_post_id');
        });
    }
};