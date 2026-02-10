<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionEvent extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];
    /**
     * Tentukan kolom yang digunakan untuk pencarian di URL (Route Model Binding).
     * Tanpa ini, Laravel akan mencari berdasarkan 'id' sehingga muncul 404 jika diisi UUID.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    /**
     * Relasi ke Pos Anggaran
     * Agar integrasi ke laporan RAPB berfungsi
     */
    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }

    /**
     * HELPER PIUTANG (HUTANG JEMAAT)
     */

    // Total Nilai Nota Lelang
    public function getTotalValueAttribute()
    {
        return $this->auctions()->sum('harga_jadi');
    }

    // Total Uang yang Sudah Masuk
    public function getTotalPaidAttribute()
    {
        return $this->auctions()->sum('total_terbayar_cache');
    }

    // Total Piutang yang Belum Dibayar
    public function getTotalReceivablesAttribute()
    {
        return $this->total_value - $this->total_paid;
    }
}
