<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }
    public function getRouteKeyName() { return 'uuid'; }

    protected $casts = [
        'tanggal_cetak' => 'date',
        'data_detail' => 'array', // Otomatis convert JSON ke Array PHP
    ];

    // Relasi ke Jemaat
    public function member() {
        return $this->belongsTo(Member::class);
    }

    // Relasi ke Penandatangan (Pejabat)
    public function signatory() {
        return $this->belongsTo(ChurchOfficer::class, 'signed_by_id');
    }

    // Helper: Nama Jenis yang Manusiawi
    public function getJenisLabelAttribute() {
        return match($this->jenis) {
            'baptis' => 'Surat Baptis Kudus',
            'sidi' => 'Surat Peneguhan Sidi',
            'nikah' => 'Akta Pernikahan',
            'atestasi_keluar' => 'Surat Atestasi (Pindah)',
            'atestasi_masuk' => 'Surat Penerimaan',
            'keterangan' => 'Surat Keterangan',
            'tugas' => 'Surat Tugas',
            default => ucfirst($this->jenis),
        };
    }
}