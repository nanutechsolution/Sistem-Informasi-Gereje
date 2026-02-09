<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Relasi
    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }
    public function account()
    {
        return $this->belongsTo(RefAccount::class, 'ref_account_id');
    }
    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi untuk Transfer (Pindah Buku)
    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }
}
