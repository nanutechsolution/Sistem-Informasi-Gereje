<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('judul');
            $table->string('pengkhotbah'); // Nama Pendeta
            $table->string('youtube_url'); // Link Asli
            $table->string('youtube_id');  // ID Video (untuk thumbnail & embed)
            $table->date('tanggal');
            $table->text('ringkasan')->nullable(); // Ringkasan Khotbah
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sermons');
    }
};