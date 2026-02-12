<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'status_baptis',
                'status_sidi',
                'status_nikah',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->enum('status_baptis', ['Sudah', 'Belum'])->default('Belum');
            $table->enum('status_sidi', ['Sudah', 'Belum'])->default('Belum');
            $table->enum('status_nikah', ['Belum', 'Sudah', 'Janda/Duda'])->default('Belum');
        });
    }
};
