<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiakoniaRequest extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'tanggal_pemberian' => 'date',
        'nominal' => 'float',
    ];

    /**
     * Jemaat penerima bantuan
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Kategori bantuan (Sakit, Duka, dll)
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RefDiakoniaType::class, 'ref_diakonia_type_id');
    }

    /**
     * Link ke Jurnal Transaksi Keuangan (Kas Keluar)
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Tahun Anggaran
     */
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * User yang menyetujui/mencatat
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}