<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom deleted_at yang hilang untuk fitur Soft Deletes.
     */
    public function up(): void
    {
        Schema::table('service_groups', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada agar tidak error duplikat
            if (!Schema::hasColumn('service_groups', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_groups', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};