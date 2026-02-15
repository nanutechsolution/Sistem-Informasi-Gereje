<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $table = 'payroll_periods';

    protected $fillable = [
        'uuid',
        'name',           // nama periode, misal "Februari 2026"
        'start_date',     // tanggal mulai periode
        'end_date',       // tanggal berakhir periode
        'is_active',      // menandai periode aktif
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($period) {
            if (empty($period->uuid)) {
                $period->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    // Relasi ke officer payrolls
    public function officerPayrolls()
    {
        return $this->hasMany(OfficerPayroll::class, 'payroll_period_id');
    }

    // Scope untuk periode aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('start_date', 'desc');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }
}
