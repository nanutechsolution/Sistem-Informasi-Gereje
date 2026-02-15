<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officer_payroll_items', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_budget_post_id')->nullable()->after('ref_salary_component_id');
            $table->foreign('ref_budget_post_id')
                  ->references('id')->on('ref_budget_posts')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('officer_payroll_items', function (Blueprint $table) {
            $table->dropForeign(['ref_budget_post_id']);
            $table->dropColumn('ref_budget_post_id');
        });
    }
};
