<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Definisi Jenis Tanggungan (Master)
        Schema::create('ref_due_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: Iuran Tahunan, Pengadaan Kursi, Pembangunan Gedung
            $table->enum('target_level', ['member', 'family']); // Per orang atau per KK
            $table->enum('unit_type', ['money', 'item']); // Uang atau Barang
            $table->string('satuan_barang')->nullable(); // Misal: "Semen (Sack)", "Kursi (Unit)"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Registrasi Tanggungan (Siapa Menanggung Apa)
        Schema::create('dues_registries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('ref_due_type_id')->constrained();
            $table->foreignId('fiscal_year_id')->constrained();
            
            // Polimorfik: Bisa terhubung ke Member atau Family
            $table->nullableMorphs('assignee'); 
            
            $table->decimal('target_nominal', 15, 2)->default(0); // Jika uang
            $table->integer('target_qty')->default(0); // Jika barang
            
            $table->decimal('current_paid_nominal', 15, 2)->default(0);
            $table->integer('current_paid_qty')->default(0);
            
            $table->enum('status', ['belum', 'cicil', 'lunas'])->default('belum');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Log Riwayat Pembayaran / Penyerahan Barang
        Schema::create('dues_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('dues_registry_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained(); // Null jika berupa barang (bukan uang)
            
            $table->decimal('nominal', 15, 2)->default(0);
            $table->integer('qty')->default(0);
            $table->date('tanggal_serah');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained(); // Petugas yang menerima
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dues_logs');
        Schema::dropIfExists('dues_registries');
        Schema::dropIfExists('ref_due_types');
    }
};