<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('church_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Identitas Dasar
            $table->string('nama_gereja');
            $table->string('nama_jemaat')->nullable(); // e.g. "Jemaat Reda Pada"
            $table->text('deskripsi_singkat')->nullable();
            $table->string('logo_path')->nullable();
            
            // Branding & Tema (Ultra-Modern Config)
            $table->string('warna_utama', 7)->default('#1e3a8a'); // Hex Color
            $table->string('warna_aksen', 7)->default('#d97706'); // Hex Color
            
            // Informasi Kontak
            $table->string('alamat');
            $table->string('email');
            $table->string('telepon')->nullable();
            
            // Social Media
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();

            // Profil Gereja
            $table->text('visi')->nullable();
            $table->json('misi')->nullable(); // Array list misi
            $table->text('sejarah_singkat')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('church_settings');
    }
};