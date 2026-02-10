<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Master Jenis Sakramen
        Schema::create('ref_sacrament_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Baptis Anak, Baptis Dewasa, Sidi, Nikah
            $table->string('kode', 10);
            $table->timestamps();
        });

        // Catatan Sakramen Jemaat
        Schema::create('sacrament_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('ref_sacrament_type_id')->constrained();
            
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_pelaksanaan');
            $table->string('tempat_pelaksanaan');
            $table->string('pelayan_firman'); // Pendeta yang melayani
            
            // Khusus Nikah
            $table->foreignId('partner_member_id')->nullable()->constrained('members'); // Jika pasangan jemaat lokal
            $table->string('partner_external_name')->nullable(); // Jika pasangan dari luar
            
            $table->text('catatan')->nullable();
            $table->string('file_sertifikat')->nullable(); // Path PDF jika ada
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sacrament_records');
        Schema::dropIfExists('ref_sacrament_types');
    }
};