<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('diakonia_requests', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned()->nullable()->change(); // Jadi nullable
            $table->string('nama_luar')->nullable()->after('member_id'); // Kolom nama orang luar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diakonia_requests', function (Blueprint $table) {
            //
        });
    }
};
