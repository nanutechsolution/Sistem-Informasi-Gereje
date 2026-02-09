<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityServant extends Model
{
    protected $guarded = [];

    /**
     * Relasi ke Jadwal Utama
     */
    public function schedule()
    {
        return $this->belongsTo(ActivitySchedule::class, 'activity_schedule_id');
    }

    /**
     * Relasi ke Data Jemaat (Siapa yang melayani)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}