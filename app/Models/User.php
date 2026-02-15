<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'church_people_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function churchPerson()
    {
        return $this->belongsTo(\App\Models\ChurchPeople::class, 'church_people_id');
    }

    // Helper untuk ambil Member ID langsung
    public function getMemberIdAttribute()
    {
        return \App\Models\Member::where('church_people_id', $this->church_people_id)->first()?->id;
    }
}