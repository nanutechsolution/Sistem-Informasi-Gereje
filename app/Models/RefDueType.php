<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefDueType extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi ke pendaftar tanggungan
     */
    public function registries()
    {
        return $this->hasMany(DuesRegistry::class);
    }

    /**
     * Scope untuk mengambil jenis tanggungan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}