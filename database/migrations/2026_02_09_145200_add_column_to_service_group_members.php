<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom peran_default yang hilang pada tabel pivot.
     */
    public function up(): void
    {
        Schema::table('service_group_members', function (Blueprint $table) {
            if (!Schema::hasColumn('service_group_members', 'peran_default')) {
                $table->string('peran_default')->nullable()->after('church_officer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_group_members', function (Blueprint $table) {
            $table->dropColumn('peran_default');
        });
    }
};