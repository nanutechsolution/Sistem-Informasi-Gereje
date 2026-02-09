<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'member_id')) {
                // Relasi: User (Login) -> Member (Data Jemaat)
                // Nullable karena Admin sistem mungkin bukan jemaat
                $table->foreignId('member_id')
                    ->nullable()
                    ->after('id') // Letakkan setelah ID
                    ->constrained('members')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropColumn('member_id');
        });
    }
};
