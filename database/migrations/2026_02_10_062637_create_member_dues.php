<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabel Master Kewajiban (Piutang)
        Schema::create('member_dues', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('fiscal_year_id')->constrained();
            $table->decimal('nominal_target', 15, 2); // Target iuran thn tsb
            $table->decimal('total_terbayar', 15, 2)->default(0);
            $table->enum('status', ['belum', 'cicil', 'lunas'])->default('belum');
            $table->timestamps();
            
            $table->unique(['member_id', 'fiscal_year_id']); // 1 orang 1 kewajiban per tahun
        });

        // 2. Tabel Detail Pembayaran (Terhubung ke Jurnal)
        Schema::create('member_due_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_due_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained(); // Link ke Jurnal Umum
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('member_due_payments');
        Schema::dropIfExists('member_dues');
    }
};