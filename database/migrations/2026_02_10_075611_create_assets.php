<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_aset');
            $table->string('kategori'); // Elektronik, Mebeul, Bangunan, Kendaraan
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->default('Unit'); // Unit, Sack, Truck
            
            // Kondisi & Lokasi
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('lokasi_fisik')->nullable(); // Ruang Ibadah, Gudang, Pastori
            
            // Asal Usul
            $table->enum('asal_perolehan', ['pembelian', 'hibah_jemaat', 'sinode'])->default('pembelian');
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete(); // Jika hibah dari jemaat spesifik
            $table->date('tanggal_perolehan');
            $table->decimal('nilai_estimasi', 15, 2)->default(0);
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('assets');
    }
};