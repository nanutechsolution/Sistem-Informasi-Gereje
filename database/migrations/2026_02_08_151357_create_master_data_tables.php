<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Wilayah
        Schema::create('ref_wilayahs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: "Wilayah 1", "Sektor Galilea"
            $table->string('kode')->nullable(); // Opsional: W01
            $table->integer('urutan')->default(0); // Untuk sorting di UI
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Master Hubungan Keluarga
        Schema::create('ref_hubungan_keluargas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: "Kepala Keluarga", "Istri"
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Tabel Master Pekerjaan
        Schema::create('ref_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Contoh: "PNS", "Petani", "Wiraswasta"
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Modifikasi Tabel Existing (EVOLUTIF: Tambah kolom baru, jangan hapus yang lama)
        
        Schema::table('families', function (Blueprint $table) {
            // Menambahkan foreign key nullable
            $table->foreignId('wilayah_id')
                  ->nullable()
                  ->after('wilayah') // Letakkan dekat kolom lama
                  ->constrained('ref_wilayahs')
                  ->nullOnDelete();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('hubungan_keluarga_id')
                  ->nullable()
                  ->after('hubungan_keluarga')
                  ->constrained('ref_hubungan_keluargas')
                  ->nullOnDelete();

            $table->foreignId('pekerjaan_id')
                  ->nullable()
                  ->after('pekerjaan')
                  ->constrained('ref_pekerjaans')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Rollback: Hapus kolom FK dulu, baru tabel masternya
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['pekerjaan_id']);
            $table->dropColumn('pekerjaan_id');
            $table->dropForeign(['hubungan_keluarga_id']);
            $table->dropColumn('hubungan_keluarga_id');
        });

        Schema::table('families', function (Blueprint $table) {
            $table->dropForeign(['wilayah_id']);
            $table->dropColumn('wilayah_id');
        });

        Schema::dropIfExists('ref_pekerjaans');
        Schema::dropIfExists('ref_hubungan_keluargas');
        Schema::dropIfExists('ref_wilayahs');
    }
};