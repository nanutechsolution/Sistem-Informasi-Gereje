<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Drop foreign key dulu
            if (Schema::hasColumn('users', 'member_id')) {
                $table->dropForeign(['member_id']);
                $table->dropColumn('member_id');
            }

            // Drop role column
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            // Tambah is_active
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->nullable();
            $table->enum('role', [
                'admin',
                'pendeta',
                'majelis',
                'bendahara',
                'sekretaris',
                'operator'
            ])->default('operator');

            $table->dropColumn('is_active');
        });
    }
};
