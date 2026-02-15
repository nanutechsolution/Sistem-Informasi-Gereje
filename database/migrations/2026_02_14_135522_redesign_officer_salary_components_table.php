<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('officer_salary_components', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn(['nama_komponen', 'is_active']);

            // Tambah FK reference
            $table->foreignId('ref_salary_component_id')
                  ->after('church_officer_id')
                  ->constrained('ref_salary_components');

            // Tambah check constraint tanggal
        });

        // Tambahkan check constraint manual (karena Laravel tidak native support penuh)
        DB::statement("
            ALTER TABLE officer_salary_components
            ADD CONSTRAINT chk_salary_date
            CHECK (
                tanggal_berakhir IS NULL
                OR tanggal_berakhir >= tanggal_mulai
            )
        ");
    }

    public function down(): void
    {
        Schema::table('officer_salary_components', function (Blueprint $table) {

            $table->dropForeign(['ref_salary_component_id']);
            $table->dropColumn('ref_salary_component_id');

            $table->string('nama_komponen')->after('ref_budget_post_id');
            $table->boolean('is_active')->default(true);
        });

        DB::statement("
            ALTER TABLE officer_salary_components
            DROP CHECK chk_salary_date
        ");
    }
};
