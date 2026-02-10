<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PastoralVisit extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'perlu_tindak_lanjut' => 'boolean',
    ];

    public function uniqueIds() { return ['uuid']; }

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function visitor() {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }
}