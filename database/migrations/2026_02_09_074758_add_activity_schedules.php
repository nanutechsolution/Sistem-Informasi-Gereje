<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->decimal('nominal_persembahan', 15, 2)->default(0)->after('keterangan');
            $table->enum('status_setoran', ['pending', 'disetor'])->default('pending')->after('nominal_persembahan');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
        });
    }

    public function down(): void {
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->dropColumn(['nominal_persembahan', 'status_setoran', 'verified_at']);
            $table->dropConstrainedForeignId('transaction_id');
        });
    }
};