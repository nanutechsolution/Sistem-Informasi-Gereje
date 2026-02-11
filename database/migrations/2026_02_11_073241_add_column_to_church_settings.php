<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('church_settings', function (Blueprint $table) {
            // Skema Warna Utama
            $table->string('color_primary', 7)->default('#1e3a8a')->after('warna_utama');
            $table->string('color_accent', 7)->default('#d97706')->after('warna_aksen');
            $table->string('color_background', 7)->default('#f8fafc');
            $table->string('color_sidebar', 7)->default('#0f172a');
            
            // Mode Tampilan
            $table->enum('appearance_mode', ['light', 'dark'])->default('light');
            
            // Border Radius (Custom UI Feel)
            $table->string('ui_rounded', 20)->default('1rem'); 
        });
    }

    public function down(): void {
        Schema::table('church_settings', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_accent', 'color_background', 'color_sidebar', 'appearance_mode', 'ui_rounded']);
        });
    }
};