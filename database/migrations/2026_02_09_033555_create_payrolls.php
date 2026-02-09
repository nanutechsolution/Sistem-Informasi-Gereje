<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('church_officer_id')->constrained();
            $table->foreignId('fiscal_year_id')->constrained();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete(); // Link ke Jurnal
            
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            
            // Snapshot nominal saat gaji dibayar (Audit Trail)
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan_perumahan', 15, 2)->default(0);
            $table->decimal('tunjangan_lain', 15, 2)->default(0);
            $table->decimal('iuran_pensiun', 15, 2)->default(0);
            $table->decimal('netto', 15, 2);
            
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->date('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payrolls');
    }
};