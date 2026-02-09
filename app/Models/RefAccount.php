<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefAccount extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }
    public function getRouteKeyName() { return 'uuid'; }

    // Hitung saldo saat ini (Saldo Awal + Masuk - Keluar)
    // Note: Logika detailnya nanti kita buat di Service terpisah agar performa terjaga
}