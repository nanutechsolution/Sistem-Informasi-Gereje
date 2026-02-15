<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {

            // Unique constraint kombinasi
            $table->unique(
                ['family_id', 'hubungan_keluarga_id'],
                'unique_kepala_keluarga_per_family'
            );
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique('unique_kepala_keluarga_per_family');
        });
    }
};
