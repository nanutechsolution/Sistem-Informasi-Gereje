<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('officer_payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('officer_payroll_id')->constrained('officer_payrolls')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_payroll_payments');
    }
};
