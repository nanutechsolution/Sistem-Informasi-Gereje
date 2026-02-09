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
        Schema::table('church_officers', function (Blueprint $table) {
            if (!Schema::hasColumn('church_officers', 'lokasi_tugas')) {
                $table->enum('lokasi_tugas', ['pusat', 'cabang', 'klasis', 'umum'])
                    ->default('pusat')
                    ->after('status_kepegawaian');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('church_officers', function (Blueprint $table) {
            $table->dropColumn('lokasi_tugas');
        });
    }
};
