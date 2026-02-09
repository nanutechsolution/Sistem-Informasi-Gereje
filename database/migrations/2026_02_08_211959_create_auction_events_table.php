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
        // 1. Event
        Schema::create('auction_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('fiscal_year_id')->constrained();
            $table->string('nama_event');
            $table->date('tanggal_event');
            $table->enum('tujuan_kas', ['umum', 'pembangunan'])->default('umum');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Items
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('auction_event_id')->constrained()->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('donatur_nama')->nullable();
            $table->foreignId('donatur_member_id')->nullable()->constrained('members');
            $table->string('pemenang_nama')->nullable();
            $table->foreignId('pemenang_member_id')->nullable()->constrained('members');
            $table->decimal('harga_jadi', 15, 2)->default(0);
            $table->decimal('total_terbayar_cache', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Payments
        Schema::create('auction_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_events');
    }
};
