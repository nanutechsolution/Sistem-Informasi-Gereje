<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_perubahan' => 'date',
        'data_lama' => 'json',
        'data_baru' => 'json',
    ];

    public function officer() {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}