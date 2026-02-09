<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionEvent extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }

    public function fiscalYear() { return $this->belongsTo(FiscalYear::class); }
    public function auctions() { return $this->hasMany(Auction::class); }
}