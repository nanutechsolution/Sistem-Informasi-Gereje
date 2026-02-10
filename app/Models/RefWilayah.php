<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefWilayah extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi ke Data Keluarga
     * Satu wilayah menaungi banyak keluarga (KK).
     */
    public function families(): HasMany
    {
        return $this->hasMany(Family::class, 'wilayah_id');
    }

    /**
     * Relasi ke Jadwal Kegiatan
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ActivitySchedule::class, 'ref_wilayah_id');
    }
}