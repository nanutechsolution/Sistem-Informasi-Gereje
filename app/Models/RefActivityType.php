<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefActivityType extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi ke Jadwal Kegiatan
     */
    public function schedules()
    {
        return $this->hasMany(ActivitySchedule::class, 'ref_activity_type_id');
    }
}