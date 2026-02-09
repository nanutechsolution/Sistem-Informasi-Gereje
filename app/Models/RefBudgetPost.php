<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefBudgetPost extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Relasi Parent (Kategori Induk)
    public function parent()
    {
        return $this->belongsTo(RefBudgetPost::class, 'parent_id');
    }

    // Relasi Children (Sub-kategori)
    public function children()
    {
        return $this->hasMany(RefBudgetPost::class, 'parent_id');
    }

    /**
     * Relasi ke Target RAPB 
     */
    public function budgets()
    {
        return $this->hasMany(Budget::class, 'ref_budget_post_id');
    }

    /**
     * Relasi ke Transaksi Realisasi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'ref_budget_post_id');
    }
}
