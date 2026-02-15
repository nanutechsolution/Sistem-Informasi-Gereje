<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('church_people', function (Blueprint $table) {
            $table->id();

            $table->string('nik')->nullable()->unique();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->boolean('is_baptized')->default(false);
            $table->boolean('is_sidi')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('church_people');
    }
};
