<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'church_people_id',
        'uuid',
        'family_id',
        'hubungan_keluarga_id',
        'pekerjaan_id',
        'status_keanggotaan',
        'tanggal_meninggal',
        'is_active',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Relasi ke data personil (Nama, NIK, dll)
     */
    public function churchPeople(): BelongsTo
    {
        return $this->belongsTo(ChurchPeople::class, 'church_people_id');
    }

    // Alias relasi untuk fleksibilitas pemanggilan
    public function person() { return $this->churchPeople(); }
    public function churchPerson() { return $this->churchPeople(); }

    /**
     * Relasi ke data Keluarga (KK)
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    public function refHubunganKeluarga(): BelongsTo
    {
        return $this->belongsTo(RefHubunganKeluarga::class, 'hubungan_keluarga_id');
    }

    public function refPekerjaan(): BelongsTo
    {
        return $this->belongsTo(RefPekerjaan::class, 'pekerjaan_id');
    }

    /**
     * Relasi ke data Pejabat (Jika member ini adalah pelayan/officer)
     */
    public function officer(): HasOne
    {
        return $this->hasOne(ChurchOfficer::class, 'member_id');
    }

    /**
     * Relasi ke riwayat pelayanan di jadwal kegiatan
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ActivityServant::class, 'member_id');
    }

    /**
     * Relasi ke riwayat sakramen jemaat.
     */
    public function sacraments(): HasMany
    {
        return $this->hasMany(SacramentRecord::class);
    }

    /**
     * Manajemen Event Member
     */
    public function events(): HasMany
    {
        return $this->hasMany(MemberEvent::class)->orderBy('tanggal', 'desc');
    }

    public function latestEvent(): HasOne
    {
        return $this->hasOne(MemberEvent::class)->latestOfMany('tanggal');
    }

    public function hasEvent($kode): bool
    {
        return $this->events()->whereHas('eventType', function ($q) use ($kode) {
            $q->where('kode', $kode);
        })->exists();
    }

    /**
     * Scopes & Helpers
     */
    public function scopeActive($query)
    {
        return $query->where('status_keanggotaan', 'aktif');
    }

    public function isActive(): bool
    {
        return $this->status_keanggotaan === 'aktif';
    }

    public function isDeceased(): bool
    {
        return $this->status_keanggotaan === 'meninggal';
    }

    public function markAsDeceased($tanggal)
    {
        $this->update([
            'status_keanggotaan' => 'meninggal',
            'tanggal_meninggal' => $tanggal,
            'is_active' => 0,
        ]);
    }
}