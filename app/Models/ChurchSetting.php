<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ChurchSetting extends Model
{
    use HasUuids;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }

    /**
     * Singleton: Ambil setting gereja saat ini (berdasarkan tenant/id pertama)
     */
    public static function current()
    {
        // Untuk multi-tenant, tambahkan logic filter berdasarkan domain/id di sini
        return self::first() ?? new self([
            'nama_gereja' => 'GKS Jemaat Reda Pada',
            'color_primary' => '#1e3a8a',
            'color_accent' => '#d97706',
            'color_background' => '#f8fafc',
            'color_sidebar' => '#0f172a',
            'appearance_mode' => 'light',
            'ui_rounded' => '1.5rem'
        ]);
    }
}