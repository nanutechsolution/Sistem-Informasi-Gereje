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
        Schema::create('families', function (Blueprint $table) {
            $table->id();

            // Nomor KK Gereja (Unik)
            $table->string('nomor_kk')->unique()->comment('Nomor Kartu Keluarga versi Gereja');

            // Nama Kepala Keluarga (Disimpan sebagai string dulu untuk master, detailnya relasi nanti)
            $table->string('kepala_keluarga');

            // Wilayah Pelayanan (Sektor/Lingkungan)
            // GKS biasanya membagi jemaat per Wilayah (1, 2, 3, dst)
            $table->string('wilayah');

            // Alamat Lengkap
            $table->text('alamat')->nullable();

            // Status Keanggotaan Keluarga
            $table->enum('status', ['aktif', 'pindah', 'keluar', 'disiplin'])->default('aktif');

            // Metadata
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
