<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DuesLog extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'tanggal_serah' => 'date',
        'nominal' => 'float',
        'qty' => 'integer',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function registry()
    {
        return $this->belongsTo(DuesRegistry::class, 'dues_registry_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}