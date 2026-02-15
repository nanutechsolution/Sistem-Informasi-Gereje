<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officer_payroll_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('officer_payroll_id')
                  ->constrained('officer_payrolls')
                  ->cascadeOnDelete();

            $table->foreignId('ref_salary_component_id')
                  ->constrained('ref_salary_components');

            $table->string('nama_snapshot', 150);
            $table->enum('jenis', ['penerimaan','potongan']);
            $table->decimal('nominal_snapshot', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_payroll_items');
    }
};
