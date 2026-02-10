<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ChurchSetting extends Model
{
    use HasUuids;
    
    protected $guarded = [];

    protected $casts = [
        'misi' => 'array', // Otomatis convert JSON ke Array PHP
    ];

    public function uniqueIds() { return ['uuid']; }

    // Helper untuk ambil setting (Singleton pattern sederhana)
    public static function current()
    {
        return self::first() ?? new self([
            'nama_gereja' => 'Gereja Kristen Sumba',
            'nama_jemaat' => 'Jemaat Reda Pada',
            'warna_utama' => '#1e3a8a',
            'warna_aksen' => '#d97706',
            'misi' => []
        ]);
    }
}