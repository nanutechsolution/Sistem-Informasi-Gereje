<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class DuesRegistry extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi Polimorfik: Bisa ke Member atau Family
     */
    public function assignee()
    {
        return $this->morphTo();
    }

    public function dueType()
    {
        return $this->belongsTo(RefDueType::class, 'ref_due_type_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * Relasi ke Log Pembayaran
     */
    public function logs()
    {
        return $this->hasMany(DuesLog::class)->orderBy('tanggal_serah', 'desc');
    }

    /**
     * Accessor untuk sisa tanggungan
     */
    public function getSisaNominalAttribute()
    {
        return max(0, $this->target_nominal - $this->current_paid_nominal);
    }

    public function getSisaQtyAttribute()
    {
        return max(0, $this->target_qty - $this->current_paid_qty);
    }
}