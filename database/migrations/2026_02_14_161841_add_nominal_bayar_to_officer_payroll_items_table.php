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
    Schema::table('officer_payroll_items', function (Blueprint $table) {
        $table->decimal('nominal_bayar', 15, 2)->default(0)->after('nominal_snapshot');
    });
}

public function down()
{
    Schema::table('officer_payroll_items', function (Blueprint $table) {
        $table->dropColumn('nominal_bayar');
    });
}

};
