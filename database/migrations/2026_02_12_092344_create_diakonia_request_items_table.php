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
        Schema::create('diakonia_request_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diakonia_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nama_item'); // Beras, Uang Tunai, dll
            $table->decimal('qty', 10, 2)->nullable(); // Jumlah barang
            $table->string('satuan')->nullable(); // kg, liter, pcs
            $table->decimal('nominal', 15, 2)->nullable(); // Kalau uang

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diakonia_request_items');
    }
};
