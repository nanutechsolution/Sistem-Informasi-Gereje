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

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'gaji_pokok' => 'float',
        'tunjangan_perumahan' => 'float',
        'tunjangan_lain' => 'float',
        'iuran_pensiun' => 'float',
    ];

    /**
     * RELASI
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function position()
    {
        return $this->belongsTo(RefPosition::class, 'ref_position_id');
    }
    public function histories()
    {
        return $this->hasMany(OfficerHistory::class);
    }
    public function serviceGroups()
    {
        return $this->belongsToMany(ServiceGroup::class, 'service_group_members')->withPivot('peran_default')->withTimestamps();
    }
    // RELASI BARU: Komponen Gaji Fleksibel
    public function salaryComponents()
    {
        return $this->hasMany(OfficerSalaryComponent::class);
    }
    // Legacy Relations (Untuk data lama sebelum migrasi penuh)
    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }
    public function budgetPostPerumahan()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_perumahan_post_id');
    }
    public function budgetPostPensiun()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_pensiun_post_id');
    }
    /**
     * LOGIKA GAJI DINAMIS (HYBRID)
     * Jika ada data di tabel komponen, pakai itu. Jika tidak, pakai kolom lama.
     */
    public function getTotalEarningsAttribute(): float
    {
        if ($this->salaryComponents()->exists()) {
            return $this->salaryComponents()->active()->where('jenis', 'penerimaan')->sum('nominal');
        }
        return $this->gaji_pokok + $this->tunjangan_perumahan + $this->tunjangan_lain;
    }

    public function getTotalDeductionsAttribute(): float
    {
        if ($this->salaryComponents()->exists()) {
            return $this->salaryComponents()->active()->where('jenis', 'potongan')->sum('nominal');
        }
        // Sesuai putusan sebelumnya: Pensiun bukan potongan gaji, jadi deduction default 0
        // Kecuali ada kebijakan baru nanti
        return 0;
    }

    // THP = Total Penerimaan - Total Potongan
    public function getNetSalaryAttribute(): float
    {
        return $this->total_earnings - $this->total_deductions;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', Carbon::today());
            });
    }
}
