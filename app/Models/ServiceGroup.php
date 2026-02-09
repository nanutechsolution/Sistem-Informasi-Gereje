<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceGroup extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi ke Wilayah (Opsional)
     * Kelompok bisa terikat pada wilayah tertentu atau umum.
     */
    public function wilayah()
    {
        return $this->belongsTo(RefWilayah::class, 'ref_wilayah_id');
    }

    /**
     * Relasi ke Anggota Kelompok (Pejabat/Staf)
     * Menggunakan tabel pivot 'service_group_members'
     */
    public function officers()
    {
        return $this->belongsToMany(ChurchOfficer::class, 'service_group_members')
            ->withPivot('peran_default')
            ->withTimestamps();
    }
}
