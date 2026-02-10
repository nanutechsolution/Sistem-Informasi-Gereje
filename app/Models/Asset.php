<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'nilai_estimasi' => 'float',
    ];

    public function uniqueIds() { return ['uuid']; }
    public function getRouteKeyName() { return 'uuid'; }

    /**
     * Relasi ke Jemaat (jika aset berupa hibah)
     */
    public function donatur()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}