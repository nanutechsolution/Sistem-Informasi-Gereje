<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPosition extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }

    // Jabatan ini bisa dipegang oleh banyak pegawai
    public function officers() {
        return $this->hasMany(ChurchOfficer::class, 'ref_position_id');
    }
}