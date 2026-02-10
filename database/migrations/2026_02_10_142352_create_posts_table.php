<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabel Warta & Berita (CMS)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('konten');
            $table->string('kategori'); // Pengumuman, Renungan, Berita
            $table->string('gambar_fitur')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Penulis
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Galeri Foto
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('judul');
            $table->string('kategori'); // Ibadah, Pembangunan, Pemuda, dll
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('posts');
    }
};