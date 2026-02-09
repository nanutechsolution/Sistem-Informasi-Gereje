<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tahun Anggaran (Fiscal Years)
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tahun', 4)->unique(); 
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false); // FITUR BARU: Kunci data agar tidak bisa diedit setelah audit
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. [BARU] Master Akun Kas & Bank (Dompet)
        // Penting: Membedakan uang tunai di brankas vs uang di bank
        Schema::create('ref_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: "Kas Besar", "Bank NTT", "Kas Pembangunan"
            $table->string('nomor_rekening')->nullable();
            $table->enum('jenis', ['kas_tunai', 'bank', 'e_wallet']); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. [BARU] Saldo Awal (Opening Balances)
        // Penting: Jembatan antar tahun. Saldo akhir 2025 menjadi Saldo Awal 2026.
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('fiscal_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('ref_account_id')->constrained()->onDelete('cascade'); // Saldo awal milik akun mana?
            $table->decimal('nominal', 15, 2)->default(0);
            $table->timestamps();
            
            // Satu akun hanya punya satu saldo awal per tahun
            $table->unique(['fiscal_year_id', 'ref_account_id']);
        });

        // 4. Master Pos Anggaran (Chart of Accounts - Income/Expense)
        Schema::create('ref_budget_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode', 20)->index(); 
            $table->string('nama'); 
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->foreignId('parent_id')->nullable()->constrained('ref_budget_posts'); // Support Sub-kategori (1.1 -> 1.1.1)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. RAPB (Rencana Anggaran)
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('fiscal_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('ref_budget_post_id')->constrained()->onDelete('cascade');
            $table->decimal('nominal_target', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['fiscal_year_id', 'ref_budget_post_id']);
        });

        // 6. Transaksi Keuangan (Jurnal Umum)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Waktu
            $table->foreignId('fiscal_year_id')->constrained();
            $table->date('tanggal')->index();
            $table->string('nomor_bukti')->nullable()->index(); // No Kwitansi Manual
            
            // Klasifikasi (Pos Anggaran) - Nullable karena "Transfer Antar Kas" tidak butuh Pos Anggaran
            $table->foreignId('ref_budget_post_id')->nullable()->constrained();
            
            // Sumber Dana (Uang ini masuk ke/keluar dari dompet mana?)
            $table->foreignId('ref_account_id')->constrained();
            
            // Jenis Mutasi
            $table->enum('jenis', ['masuk', 'keluar', 'pindah_buku']); 
            
            // Nominal & Detail
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan');
            
            // Approval & Audit
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Operator Input
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Bendahara/Ketua yg setuju
            $table->timestamp('approved_at')->nullable();

            // Link untuk Pindah Buku (Transfer)
            // Jika transfer dari Kas -> Bank, akan ada 2 row saling terhubung
            $table->foreignId('related_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('ref_budget_posts');
        Schema::dropIfExists('opening_balances');
        Schema::dropIfExists('ref_accounts');
        Schema::dropIfExists('fiscal_years');
    }
};