<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'bulan' => 'integer',
        'tahun' => 'integer'
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Relasi ke Pengerja/Pegawai
     */
    public function officer()
    {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }

    /**
     * Relasi ke Jurnal Transaksi (Opsional jika bayar lunas langsung)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke Tahun Anggaran
     */
    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * Relasi ke Cicilan Pembayaran (PENTING: Memperbaiki Error)
     */
    public function payments()
    {
        return $this->hasMany(PayrollPayment::class);
    }

    /**
     * Accessor untuk hitung total yang sudah dibayar
     */
    public function getTotalTerbayarAttribute()
    {
        return $this->payments()->sum('nominal');
    }

    /**
     * Accessor untuk sisa gaji yang belum dibayar
     */
    public function getSisaGajiAttribute()
    {
        return $this->netto - $this->total_terbayar;
    }

    /**
     * Cek status lunas
     */
    public function getIsLunasAttribute()
    {
        return $this->sisa_gaji <= 0;
    }

    /**
     * Nama Bulan Indonesia
     */
    public function getNamaBulanAttribute()
    {
        return \Carbon\Carbon::create()->month($this->bulan)->isoFormat('MMMM');
    }
}