<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ref_due_types', function (Blueprint $table) {
            // Tambahkan kolom deleted_at setelah updated_at
            if (!Schema::hasColumn('ref_due_types', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ref_due_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};