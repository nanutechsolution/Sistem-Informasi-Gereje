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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Untuk keamanan URL

            // Relasi ke Tabel Families (Jika KK dihapus, anggota ikut terhapus/cascade)
            $table->foreignId('family_id')->constrained()->onDelete('cascade');

            // Data Pribadi
            $table->string('nama');
            $table->string('nik')->nullable()->unique(); // Nomor KTP (Opsional anak kecil)
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp')->nullable();

            // Data Gerejawi
            $table->enum('hubungan_keluarga', ['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain']);
            $table->enum('status_baptis', ['Sudah', 'Belum'])->default('Belum');
            $table->enum('status_sidi', ['Sudah', 'Belum'])->default('Belum');
            $table->enum('status_nikah', ['Belum', 'Sudah', 'Janda/Duda'])->default('Belum');

            // Profesi/Pendidikan (Opsional, bisa dikembangkan nanti)
            $table->string('pekerjaan')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
