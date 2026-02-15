<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officer_salary_components', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('ref_budget_post_id');
        });
    }

    public function down(): void
    {
        Schema::table('officer_salary_components', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
