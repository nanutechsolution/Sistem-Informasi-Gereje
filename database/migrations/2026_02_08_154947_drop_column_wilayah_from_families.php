<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {

            // HAPUS ENUM DUPLIKAT (karena sudah pakai tabel master & event)
            if (Schema::hasColumn('members', 'hubungan_keluarga')) {
                $table->dropColumn('hubungan_keluarga');
            }

            if (Schema::hasColumn('members', 'pekerjaan')) {
                $table->dropColumn('pekerjaan');
            }

            // OPSIONAL (jika mau full event-based nanti)
            // $table->dropColumn(['status_baptis', 'status_sidi', 'status_nikah']);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {

            // rollback jika dibutuhkan
            $table->enum('hubungan_keluarga', [
                'Kepala Keluarga',
                'Istri',
                'Anak',
                'Famili Lain'
            ])->after('no_hp');

            $table->string('pekerjaan')->nullable()->after('status_nikah');
        });
    }
};
