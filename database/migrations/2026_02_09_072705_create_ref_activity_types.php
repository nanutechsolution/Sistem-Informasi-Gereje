<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Master Jenis Kegiatan (Ibadah Minggu, PKS, Rapat, Katekisasi, dll)
        Schema::create('ref_activity_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama');
            $table->string('warna_label')->default('#3b82f6'); // Untuk kalender
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Utama Jadwal Kegiatan
        Schema::create('activity_schedules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('ref_activity_type_id')->constrained();
            $table->foreignId('ref_wilayah_id')->nullable()->constrained(); // Jika PKS/Kegiatan Wilayah
            $table->foreignId('family_id')->nullable()->constrained(); // Jika PKS di rumah warga

            $table->date('tanggal')->index();
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();

            $table->string('tema')->nullable();
            $table->string('lokasi_manual')->nullable(); // Jika bukan di rumah warga (misal: Aula, Pantai)
            $table->text('keterangan')->nullable();

            $table->enum('status', ['rencana', 'terlaksana', 'batal'])->default('rencana');

            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Tabel Pelayan Kegiatan (Siapa melayani sebagai apa)
        // Memungkinkan tracking statistik pelayanan personil selama 20 tahun
        Schema::create('activity_servants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained(); // Link ke database jemaat
            $table->string('peran'); // Contoh: Pengkhotbah, Liturgos, Pemusik, Kolektan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_servants');
        Schema::dropIfExists('activity_schedules');
        Schema::dropIfExists('ref_activity_types');
    }
};
