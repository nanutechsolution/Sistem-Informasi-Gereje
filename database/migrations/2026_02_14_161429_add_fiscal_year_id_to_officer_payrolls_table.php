<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('officer_payrolls', function (Blueprint $table) {
        $table->unsignedBigInteger('fiscal_year_id')->after('payroll_period_id')->nullable();
        $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years');
    });
}

public function down()
{
    Schema::table('officer_payrolls', function (Blueprint $table) {
        $table->dropForeign(['fiscal_year_id']);
        $table->dropColumn('fiscal_year_id');
    });
}

};
