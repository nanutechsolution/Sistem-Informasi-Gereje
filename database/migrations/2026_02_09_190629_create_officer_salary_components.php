<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('officer_salary_components', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Relasi ke Pegawai
            $table->foreignId('church_officer_id')->constrained()->onDelete('cascade');
            
            // Relasi ke RAPB (Agar setiap komponen punya pos audit sendiri)
            $table->foreignId('ref_budget_post_id')->nullable()->constrained()->nullOnDelete();

            // Definisi Komponen
            $table->string('nama_komponen'); // Contoh: "Gaji Pokok", "Tunjangan Anak", "Potongan BPJS"
            $table->enum('jenis', ['penerimaan', 'potongan'])->default('penerimaan'); 
            // penerimaan = Menambah THP (Earnings)
            // potongan = Mengurangi THP (Deductions)

            $table->decimal('nominal', 15, 2);
            $table->boolean('is_fixed')->default(true); // true = nominal tetap, false = variabel (manual tiap bulan)

            // Periode Berlaku (Untuk Sejarah & Kenaikan Berkala)
            $table->date('tanggal_mulai'); // Efektif per tanggal ini
            $table->date('tanggal_berakhir')->nullable(); // Jika diisi, otomatis stop saat lewat tanggal
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('officer_salary_components');
    }
};