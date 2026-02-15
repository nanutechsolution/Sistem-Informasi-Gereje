<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // 1. Import ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasUuids, HasFactory, SoftDeletes; // 2. Pakai Trait ini

    protected $guarded = [];

    // 3. Tambahkan ini agar kolom 'uuid' yang otomatis terisi
    public function uniqueIds()
    {
        return ['uuid'];
    }

    // 4. Tambahkan ini agar Laravel mencari data berdasarkan UUID di URL, bukan ID
    public function getRouteKeyName()
    {
        return 'uuid';
    }


    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(RefWilayah::class, 'wilayah_id');
    }
    public function refWilayah(): BelongsTo
    {
        return $this->belongsTo(RefWilayah::class, 'wilayah_id');
    }

    /**
     * Relasi ke Jadwal Kegiatan (PKS, dll)
     * Ini yang dibutuhkan untuk fitur Audit Antrian
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ActivitySchedule::class);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
