<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // contoh: 2026-01
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft','locked','processed','paid'])
                  ->default('draft');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE payroll_periods
            ADD CONSTRAINT chk_period_date
            CHECK (tanggal_selesai >= tanggal_mulai)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_periods');
    }
};
