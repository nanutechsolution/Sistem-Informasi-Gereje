<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AuctionPayment extends Model
{
    use HasUuids;
    protected $guarded = [];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function uniqueIds() { return ['uuid']; }

    public function auction() { return $this->belongsTo(Auction::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
}