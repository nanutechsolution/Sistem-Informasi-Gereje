<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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



    public function items(): HasMany
    {
        return $this->hasMany(DiakoniaRequestItem::class);
    }

    public function refreshTotalNominal(): void
    {
        $total = $this->items()->sum('nominal');

        $this->update([
            'nominal' => $total
        ]);
    }


    public function approve($userId): void
    {
        // Pastikan total nominal terbaru
        $this->refreshTotalNominal();

        // Jangan buat transaksi kalau tidak ada nominal
        if ($this->nominal <= 0) {
            throw new \Exception('Nominal bantuan kosong.');
        }

        // Buat transaksi kas keluar
        $transaction = Transaction::create([
            'uuid' => Str::uuid(),
            'jenis' => 'kas_keluar',
            'nominal' => $this->nominal,
            'tanggal' => now(),
            'keterangan' => 'Bantuan Diakonia: ' . $this->member->nama,
            'fiscal_year_id' => $this->fiscal_year_id,
        ]);

        // Update request
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'tanggal_approved' => now(),
            'transaction_id' => $transaction->id,
        ]);
    }


    public function reject($userId): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'tanggal_approved' => now()
        ]);
    }
}
