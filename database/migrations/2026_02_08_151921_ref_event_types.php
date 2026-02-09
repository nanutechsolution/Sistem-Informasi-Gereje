<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Jenis Peristiwa (Lookup)
        // Agar fleksibel jika sinode menambah jenis sakramen/peristiwa baru
        Schema::create('ref_event_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: "Baptis Kudus", "Sidi", "Pernikahan"
            
            // Indexing kategori agar filter 'rohani' vs 'sipil' cepat
            $table->string('kategori')->index(); // Values: rohani, sipil, mutasi
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Transaksi Event Jemaat
        // Mencatat 5W1H (Who, What, When, Where, Why/Detail)
        Schema::create('member_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Who (Siapa)
            // Relasi ke tabel members. Cascade: Jika member dihapus, history ikut terhapus (soft delete).
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            
            // What (Apa)
            $table->foreignId('event_type_id')->constrained('ref_event_types');
            
            // When (Kapan)
            // Indexing tanggal penting untuk laporan statistik/grafik
            $table->date('tanggal')->index();
            
            // Where (Dimana - Opsional, krn bisa di gereja lain)
            $table->string('lokasi')->nullable(); // Nama Gereja / Tempat
            
            // Detail Pendukung (Sangat penting untuk audit sinode)
            $table->string('pendeta')->nullable(); // Nama Pendeta yg melayani
            
            // Indexing nomor surat agar pencarian arsip cepat
            $table->string('nomor_surat')->nullable()->index(); // No. Surat Baptis/Nikah
            
            $table->text('keterangan')->nullable(); // Catatan tambahan
            
            // Bukti Fisik (Opsional utk v2: scan surat)
            // $table->string('file_path')->nullable(); 

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_events');
        Schema::dropIfExists('ref_event_types');
    }
};