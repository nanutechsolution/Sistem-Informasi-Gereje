<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SacramentRecord extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'data_detail' => 'array',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Relasi ke Jemaat penerima sakramen.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relasi ke Jenis Sakramen (Baptis/Sidi/Nikah).
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RefSacramentType::class, 'ref_sacrament_type_id');
    }

    /**
     * Relasi ke pasangan (Khusus Pernikahan).
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'partner_member_id');
    }
}