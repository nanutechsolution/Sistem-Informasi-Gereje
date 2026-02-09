<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel untuk mencatat setiap cicilan gaji
        Schema::create('payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Update tabel payrolls untuk mendukung status cicilan
        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('status_bayar', ['belum', 'cicil', 'lunas'])->default('belum')->after('status');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payroll_payments');
    }
};