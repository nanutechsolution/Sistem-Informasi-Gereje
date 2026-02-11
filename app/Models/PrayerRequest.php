<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrayerRequest extends Model
{
    use HasUuids, SoftDeletes;

    /**
     * Keamanan: Mencegah Mass Assignment Attack.
     * Hanya field di bawah ini yang boleh diisi secara massal.
     * Field seperti 'id' atau 'uuid' diproteksi sistem.
     */
    protected $fillable = [
        'nama_pemohon',
        'kontak',
        'kategori',
        'pokok_doa',
        'is_private',
        'butuh_konseling',
        'status',
        'ip_address',
        'user_agent'
    ];

    /**
     * Kolom yang harus disembunyikan saat dikonversi ke Array/JSON.
     * Melindungi privasi IP dan kontak jika data diakses via API.
     */
    protected $hidden = [
        'ip_address',
        'user_agent',
        'kontak',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Sanitasi saat output untuk mencegah XSS (Cross-Site Scripting).
     */
    public function getPokokDoaAttribute($value)
    {
        return e($value);
    }

    /**
     * Masking Nama: Jika private, sembunyikan identitas asli.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->is_private) {
            return 'Hamba Tuhan';
        }
        
        return e($this->nama_pemohon ?? 'Jemaat');
    }
}