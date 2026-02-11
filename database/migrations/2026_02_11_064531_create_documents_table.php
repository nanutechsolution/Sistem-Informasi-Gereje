<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('judul');
            $table->string('kategori'); // Tata Ibadah, Warta, Formulir, SK
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_public')->default(true); // Tampil di web publik?
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('documents');
    }
};