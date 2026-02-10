<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function refHubunganKeluarga()
    {
        return $this->belongsTo(RefHubunganKeluarga::class, 'hubungan_keluarga_id');
    }

    public function refPekerjaan()
    {
        return $this->belongsTo(RefPekerjaan::class, 'pekerjaan_id');
    }

    // Mengambil semua riwayat peristiwa member ini
    public function events()
    {
        return $this->hasMany(MemberEvent::class)->orderBy('tanggal', 'desc');
    }

    // Helper: Ambil event terakhir (misal: status terakhir)
    public function latestEvent()
    {
        return $this->hasOne(MemberEvent::class)->latestOfMany('tanggal');
    }



    /**
     * Relasi ke riwayat sakramen jemaat.
     */
    public function sacraments(): HasMany
    {
        return $this->hasMany(SacramentRecord::class);
    }
}
