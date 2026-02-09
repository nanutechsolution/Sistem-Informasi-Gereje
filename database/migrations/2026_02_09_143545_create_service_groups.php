<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Pastikan Tabel Kelompok ada untuk template tim
        if (!Schema::hasTable('service_groups')) {
            Schema::create('service_groups', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('nama_kelompok');
                $table->foreignId('ref_wilayah_id')->nullable()->constrained();
                $table->timestamps();
            });
        }

        // 2. Hubungkan Kelompok ke Pegawai (Bukan Jemaat Umum)
        if (!Schema::hasTable('service_group_members')) {
            Schema::create('service_group_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_group_id')->constrained()->onDelete('cascade');
                $table->foreignId('church_officer_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 3. Tambahkan field Kolekte di Jadwal jika belum ada
        Schema::table('activity_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_schedules', 'nominal_persembahan')) {
                $table->decimal('nominal_persembahan', 15, 2)->default(0)->after('keterangan');
                $table->enum('status_setoran', ['pending', 'disetor'])->default('pending')->after('nominal_persembahan');
                $table->timestamp('verified_at')->nullable();
            }
        });
    }

    public function down(): void {
        // Drop jika rollback
    }
};