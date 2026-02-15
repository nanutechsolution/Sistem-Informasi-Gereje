<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSalaryComponent extends Model
{
    use HasFactory;

    // Nama tabel (jika mengikuti konvensi Laravel, ini opsional)
    protected $table = 'ref_salary_components';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'is_taxable',
    ];

    // Tipe data khusus
    protected $casts = [
        'is_taxable' => 'boolean',
    ];

    /**
     * Relasi ke OfficerSalaryComponent
     * Seorang ref component bisa dipakai di banyak officer
     */
    public function officerComponents()
    {
        return $this->hasMany(OfficerSalaryComponent::class, 'ref_salary_component_id');
    }
}
