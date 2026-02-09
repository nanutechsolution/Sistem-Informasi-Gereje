<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambahkan kolom yang hilang dan memperbaiki constraint null pada tunjangan.
     */
    public function up(): void {
        // 1. Perbaikan pada tabel church_officers
        Schema::table('church_officers', function (Blueprint $table) {
            if (!Schema::hasColumn('church_officers', 'lokasi_tugas')) {
                $table->enum('lokasi_tugas', ['pusat', 'cabang', 'klasis', 'umum'])
                      ->default('pusat')
                      ->after('status_kepegawaian');
            }
            
            // Pastikan tunjangan_lain ada dan default 0
            if (!Schema::hasColumn('church_officers', 'tunjangan_lain')) {
                $table->decimal('tunjangan_lain', 15, 2)->default(0)->after('tunjangan_perumahan');
            }
        });

        // 2. Perbaikan pada tabel payrolls agar tidak error saat insert null
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('tunjangan_lain', 15, 2)->default(0)->change();
        });
    }

    public function down(): void {
        Schema::table('church_officers', function (Blueprint $table) {
            $table->dropColumn(['lokasi_tugas', 'tunjangan_lain']);
        });
    }
};