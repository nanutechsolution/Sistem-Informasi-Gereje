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
        // Tambahkan kolom deleted_at ke tabel families
        Schema::table('families', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at'); // Kolom tipe timestamp nullable
        });

        // Tambahkan kolom deleted_at ke tabel members
        Schema::table('members', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};