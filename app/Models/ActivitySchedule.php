<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivitySchedule extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function type()
    {
        return $this->belongsTo(RefActivityType::class, 'ref_activity_type_id');
    }
    public function wilayah()
    {
        return $this->belongsTo(RefWilayah::class, 'ref_wilayah_id');
    }
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
    public function servants()
    {
        return $this->hasMany(ActivityServant::class);
    }

    // Helper untuk Nama Lokasip
    public function getLokasiDisplayAttribute()
    {
        if ($this->family_id) return "Keluarga " . $this->family->kepala_keluarga;
        return $this->lokasi_manual ?? 'Gedung Gereja';
    }
}
