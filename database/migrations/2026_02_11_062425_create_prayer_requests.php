<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Identitas (Boleh Null jika Anonim)
            $table->string('nama_pemohon')->nullable();
            $table->string('kontak')->nullable(); // WA/HP
            
            // Isi Doa
            $table->string('kategori'); // Sakit, Pergumulan, Syukur, Usaha, Keluarga
            $table->text('pokok_doa');
            
            // Privasi & Status
            $table->boolean('is_private')->default(true); // True = Hanya Pendeta, False = Boleh Warta
            $table->boolean('butuh_konseling')->default(false); // Request kunjungan/telepon
            $table->enum('status', ['baru', 'didoakan', 'selesai'])->default('baru');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prayer_requests');
    }
};