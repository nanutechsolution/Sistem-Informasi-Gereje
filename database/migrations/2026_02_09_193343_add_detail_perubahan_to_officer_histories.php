<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom detail_perubahan untuk mencatat narasi riwayat gaji.
     */
    public function up(): void
    {
        Schema::table('officer_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('officer_histories', 'detail_perubahan')) {
                $table->text('detail_perubahan')->nullable()->after('tanggal_perubahan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('officer_histories', function (Blueprint $table) {
            $table->dropColumn('detail_perubahan');
        });
    }
};