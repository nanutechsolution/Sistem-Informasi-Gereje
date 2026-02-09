<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberEvent extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];
    
    // Cast tanggal agar otomatis jadi Carbon object (bisa diformat .format('d M Y'))
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    // Relasi ke Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi ke Jenis Event
    public function eventType()
    {
        return $this->belongsTo(RefEventType::class, 'event_type_id');
    }
}