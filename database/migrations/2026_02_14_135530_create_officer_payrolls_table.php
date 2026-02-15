<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officer_payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_period_id')
                ->constrained('payroll_periods')
                ->cascadeOnDelete();

            $table->foreignId('church_officer_id')
                ->constrained('church_officers');

            $table->decimal('total_penerimaan', 15, 2);
            $table->decimal('total_potongan', 15, 2);
            $table->decimal('take_home_pay', 15, 2);

            $table->enum('status', ['draft', 'finalized', 'paid'])
                ->default('draft');

            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['payroll_period_id', 'church_officer_id'],
                'unique_period_officer'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_payrolls');
    }
};
