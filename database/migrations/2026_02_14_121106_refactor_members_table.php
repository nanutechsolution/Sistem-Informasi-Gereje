<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {

            // Drop foreign key lama
            $table->dropForeign(['church_person_id']);

            // Rename FK
            $table->renameColumn('church_person_id', 'church_people_id');

            // Drop kolom duplikat identitas
            $table->dropColumn([
                'nama',
                'nik',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'no_hp'
            ]);
        });

        // Tambah foreign key baru
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('church_people_id')
                ->references('id')
                ->on('church_people')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {

            $table->dropForeign(['church_people_id']);

            $table->renameColumn('church_people_id', 'church_person_id');

            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L','P']);
            $table->string('no_hp')->nullable();
        });
    }
};
