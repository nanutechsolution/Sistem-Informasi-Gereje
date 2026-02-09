<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Subjek Surat (Siapa yang diterbitkan suratnya)
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            
            // Jenis Dokumen
            $table->enum('jenis', [
                'baptis',           // Surat Baptis
                'sidi',             // Surat Sidi
                'nikah',            // Surat Nikah
                'atestasi_keluar',  // Surat Pindah (Keluar)
                'atestasi_masuk',   // Surat Terima (Masuk)
                'keterangan',       // Surat Keterangan Anggota/Kelakuan Baik
                'tugas'             // Surat Tugas Pelayanan
            ]);

            // Meta Data Surat
            $table->string('nomor_surat')->unique(); // Contoh: 001/GKS-RP/II/2026
            $table->date('tanggal_cetak');
            
            // Data Tambahan (Disimpan sebagai JSON agar fleksibel per jenis surat)
            // Contoh Baptis: { "nama_ayah": "A", "nama_ibu": "B", "pendeta_baptis": "Pdt X" }
            $table->json('data_detail')->nullable();
            
            // Penandatangan (Otomatis ambil dari Pejabat Aktif)
            $table->foreignId('signed_by_id')->nullable()->constrained('church_officers'); // Ketua Majelis/Sekretaris
            
            $table->text('keperluan')->nullable(); // Khusus surat keterangan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('letters');
    }
};