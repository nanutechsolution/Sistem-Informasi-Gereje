<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasUuids, SoftDeletes;
    
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }

    public function event() { return $this->belongsTo(AuctionEvent::class, 'auction_event_id'); }
    public function donatur() { return $this->belongsTo(Member::class, 'donatur_member_id'); }
    public function pemenang() { return $this->belongsTo(Member::class, 'pemenang_member_id'); }
    public function payments() { return $this->hasMany(AuctionPayment::class); }

    // Accessor untuk hitung saldo sisa piutang
    public function getSisaPiutangAttribute()
    {
        return $this->harga_jadi - $this->payments()->sum('nominal');
    }

    public function getStatusLunasAttribute()
    {
        return $this->sisa_piutang <= 0;
    }
}