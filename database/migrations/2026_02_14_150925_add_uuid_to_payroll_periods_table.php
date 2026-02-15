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
    Schema::table('payroll_periods', function (Blueprint $table) {
        $table->uuid('uuid')->nullable()->after('id')->unique();
    });
}

public function down()
{
    Schema::table('payroll_periods', function (Blueprint $table) {
        $table->dropColumn('uuid');
    });
}

};
