<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menghapus kolom lama.
     */
    public function up(): void
    {
        Schema::table('church_settings', function (Blueprint $table) {
            // Menghapus kolom warna lama yang sudah digantikan
            $table->dropColumn(['warna_utama', 'warna_aksen']);
        });
    }

    /**
     * Balikkan migrasi (tambahkan kembali kolom jika rollback).
     */
    public function down(): void
    {
        Schema::table('church_settings', function (Blueprint $table) {
            $table->string('warna_utama', 7)->default('#1e3a8a');
            $table->string('warna_aksen', 7)->default('#d97706');
        });
    }
};