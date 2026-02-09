<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Referensi Jabatan
        Schema::create('ref_positions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Pdt, Vic, Sekretaris, Koster, dll
            $table->string('singkatan')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Data Utama Pegawai/Pejabat
        Schema::create('church_officers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Relasi ke tabel jemaat
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('ref_position_id')->constrained();
            
            // Identitas HR
            $table->string('nip_gereja')->nullable()->unique();
            $table->enum('status_kepegawaian', ['organik', 'non_organik', 'vicaris', 'majelis', 'relawan']);
            
            // Administrasi SK (Kunci Histori)
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable(); // Penting untuk Vicaris/Kontrak
            
            // Komponen Keuangan (Sesuai Dokumen Reda Pada 2026)
            $table->decimal('gaji_pokok', 15, 2)->default(0); // Pemeliharaan Pengerja
            $table->decimal('tunjangan_perumahan', 15, 2)->default(0);
            $table->decimal('iuran_pensiun', 15, 2)->default(0); // Potongan
            
            // Data Rekening
            $table->string('bank_nama')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            
            // Status Aktif
            $table->boolean('is_active')->default(true)->index();
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Log Audit / Riwayat Perubahan (Audit Trail 20 Tahun)
        Schema::create('officer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_officer_id')->constrained()->onDelete('cascade');
            $table->string('jenis_perubahan'); // Kenaikan Gaji, Perpanjangan SK, Mutasi
            $table->date('tanggal_perubahan');
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->string('sk_pendukung')->nullable();
            $table->foreignId('user_id')->constrained(); // Siapa yang menginput
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('officer_histories');
        Schema::dropIfExists('church_officers');
        Schema::dropIfExists('ref_positions');
    }
};