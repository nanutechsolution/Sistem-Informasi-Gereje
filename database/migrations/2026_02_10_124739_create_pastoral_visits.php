<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pastoral_visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Siapa yang dikunjungi
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            
            // Siapa yang mengunjungi (Majelis/Pendeta)
            $table->foreignId('church_officer_id')->constrained();
            
            $table->date('tanggal_kunjungan')->index();
            
            // Kategori Peristiwa
            $table->enum('kategori', [
                'rutin',        // Kunjungan rumah berkala
                'sakit',        // Mengunjungi orang sakit
                'penguatan',    // Penguatan iman/beban berat
                'syukuran',     // Syukuran rumah/berkat
                'duka'          // Kedukaan
            ])->default('rutin');

            $table->text('pokok_doa')->nullable();
            $table->text('catatan_kunjungan')->nullable();
            
            // Follow up status
            $table->boolean('perlu_tindak_lanjut')->default(false);
            $table->string('tindak_lanjut_detail')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pastoral_visits');
    }
};