<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerPayroll extends Model
{
    protected $fillable = [
        'payroll_period_id',
        'fiscal_year_id',
        'church_officer_id',
        'total_penerimaan',
        'total_potongan',
        'take_home_pay',
        'status',
        'finalized_at',
        'paid_at',
    ];

    public function payroll_period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }
    public function period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }
    public function officer()
    {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }

    public function items()
    {
        return $this->hasMany(OfficerPayrollItem::class, 'officer_payroll_id');
    }

    // total sudah dibayar
    public function totalPaid()
    {
        return $this->items()->sum('nominal'); // jika items menyimpan pembayaran per komponen
    }

    public function getIsPaidAttribute()
    {
        return $this->status === 'paid';
    }

    public function getStatusLabelAttribute()
    {
        $totalPaid = $this->items->sum('nominal_bayar');
        $totalNominal = $this->items->sum('nominal_snapshot');

        if ($totalPaid >= $totalNominal) return 'lunas';
        if ($totalPaid > 0) return 'cicil';
        return 'draft';
    }

         public function payments()
    {
        return $this->hasMany(OfficerPayrollPayment::class, 'officer_payroll_id');
    }
}
