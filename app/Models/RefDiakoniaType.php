<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefDiakoniaType extends Model
{
    protected $guarded = [];

    /**
     * Relasi ke daftar permintaan/pemberian diakonia
     */
    public function requests(): HasMany
    {
        return $this->hasMany(DiakoniaRequest::class);
    }
}