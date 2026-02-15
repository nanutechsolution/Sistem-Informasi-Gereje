<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficerPayrollPayment extends Model
{
    use HasFactory;

    protected $table = 'officer_payroll_payments';

    protected $fillable = [
        'uuid',
        'officer_payroll_id',
        'transaction_id',
        'nominal',
        'tanggal_bayar',
        'keterangan',
    ];

    /**
     * Officer payroll yang dibayar
     */
    public function payroll()
    {
        return $this->belongsTo(OfficerPayroll::class, 'officer_payroll_id');
    }

    /**
     * Transaksi pembayaran terkait
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
