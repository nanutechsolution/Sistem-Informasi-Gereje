<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchPeople extends Model
{
    protected $table = 'church_people';

    protected $fillable = [
        'nik',
        'full_name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'is_baptized',
        'is_sidi',
    ];


    protected $casts = [
        'date_of_birth' => 'date',
        'is_baptized' => 'boolean',
        'is_sidi' => 'boolean',
    ];
    // Relasi ke user
    public function user()
    {
        return $this->hasOne(User::class, 'church_people_id');
    }

    // Relasi ke member
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    // Relasi ke jabatan gereja
    public function officers()
    {
        return $this->hasMany(ChurchOfficer::class);
    }
}
