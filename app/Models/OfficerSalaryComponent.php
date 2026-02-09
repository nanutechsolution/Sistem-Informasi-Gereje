<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class OfficerSalaryComponent extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected $casts = [
        'nominal' => 'float',
        'is_fixed' => 'boolean',
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function officer()
    {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }

    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }

    // Scope: Ambil komponen yang valid saat ini (Aktif & Dalam Periode)
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
            ->where('tanggal_mulai', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('tanggal_berakhir')
                    ->orWhere('tanggal_berakhir', '>=', $now);
            });
    }
}
