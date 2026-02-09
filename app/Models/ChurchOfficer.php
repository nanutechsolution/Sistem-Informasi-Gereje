<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ChurchOfficer extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    // Konfigurasi UUID
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // Cast data agar otomatis menjadi objek Carbon
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * RELASI
     */

    // Terhubung ke data jemaat
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Terhubung ke jabatan
    public function position()
    {
        return $this->belongsTo(RefPosition::class, 'ref_position_id');
    }

    // Terhubung ke wilayah tugas
    public function wilayah()
    {
        return $this->belongsTo(RefWilayah::class, 'ref_wilayah_id');
    }

    // Riwayat kenaikan gaji/jabatan
    public function histories()
    {
        return $this->hasMany(OfficerHistory::class);
    }

    /**
     * ACCESSOR & LOGIC
     */

    // Hitung Gaji Bersih (Netto)
    public function getNetSalaryAttribute(): float
    {
        $pendapatan = $this->gaji_pokok + $this->tunjangan_perumahan + $this->tunjangan_lain;
        return $pendapatan - $this->iuran_pensiun;
    }

    // Cek apakah masa tugas (Vicaris/Kontrak) sudah berakhir
    public function getIsExpiredAttribute(): bool
    {
        if ($this->tanggal_selesai && Carbon::now()->gt($this->tanggal_selesai)) {
            return true;
        }
        return false;
    }

    /**
     * SCOPES
     */

    // Ambil yang benar-benar aktif (Status Aktif & Belum Expired)
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', Carbon::today());
            });
    }
}
