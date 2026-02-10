<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Master Jenis Diakonia (Sakit, Pendidikan, Duka, dll)
        Schema::create('ref_diakonia_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Transaksi Bantuan Diakonia
        Schema::create('diakonia_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('ref_diakonia_type_id')->constrained();
            $table->foreignId('fiscal_year_id')->constrained();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete(); // Link ke Jurnal (Kas Keluar)
            
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_pemberian');
            $table->text('alasan_bantuan')->nullable();
            
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('diakonia_requests');
        Schema::dropIfExists('ref_diakonia_types');
    }
};