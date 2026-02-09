<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OpeningBalance extends Model
{
    use HasUuids;
    
    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function account() { return $this->belongsTo(RefAccount::class, 'ref_account_id'); }
    public function fiscalYear() { return $this->belongsTo(FiscalYear::class); }
}