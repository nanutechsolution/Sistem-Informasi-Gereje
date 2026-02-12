<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->enum('status_keanggotaan', ['aktif','pindah','meninggal'])
                  ->default('aktif')
                  ->after('pekerjaan_id');

            $table->date('tanggal_meninggal')
                  ->nullable()
                  ->after('status_keanggotaan');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'status_keanggotaan',
                'tanggal_meninggal'
            ]);
        });
    }
};
