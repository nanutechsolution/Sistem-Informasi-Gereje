<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->unsignedBigInteger('fiscal_year_id')->after('tanggal_mulai');
            
            // Jika ingin foreign key ke tabel fiscal_years
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->dropForeign(['fiscal_year_id']);
            $table->dropColumn('fiscal_year_id');
        });
    }
};
