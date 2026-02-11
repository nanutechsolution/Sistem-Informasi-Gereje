<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrayerRequest extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }

    // Helper untuk menyamarkan nama jika private
    public function getDisplayNameAttribute()
    {
        return $this->is_private ? 'Hamba Tuhan' : ($this->nama_pemohon ?? 'Jemaat');
    }
}