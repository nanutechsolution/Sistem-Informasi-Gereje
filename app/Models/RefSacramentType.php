<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefSacramentType extends Model
{
    protected $guarded = [];

    /**
     * Relasi ke daftar rekam sakramen jemaat.
     */
    public function records(): HasMany
    {
        return $this->hasMany(SacramentRecord::class, 'ref_sacrament_type_id');
    }
}