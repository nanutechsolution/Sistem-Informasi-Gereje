<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PayrollPayment extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Relasi balik ke data Payroll (Induk)
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    /**
     * Relasi ke Jurnal Transaksi (Bukti Kas Keluar)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}