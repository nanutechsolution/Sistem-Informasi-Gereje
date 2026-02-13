<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiakoniaRequestItem extends Model
{
    protected $guarded = [];


    protected static function booted()
    {
        static::saved(function ($item) {
            $item->diakoniaRequest->refreshTotalNominal();
        });

        static::deleted(function ($item) {
            $item->diakoniaRequest->refreshTotalNominal();
        });
    }
    public function diakoniaRequest(): BelongsTo
    {
        return $this->belongsTo(DiakoniaRequest::class);
    }
}
