<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Drop foreign key lama
            $table->dropForeign(['church_person_id']);

            // Rename column
            $table->renameColumn('church_person_id', 'church_people_id');
        });

        // Tambahkan kembali foreign key baru
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('church_people_id')
                ->references('id')
                ->on('church_people')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['church_people_id']);

            $table->renameColumn('church_people_id', 'church_person_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('church_person_id')
                ->references('id')
                ->on('church_people')
                ->nullOnDelete();
        });
    }
};
