<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasUuids; // Budget biasanya tidak butuh soft delete, tapi boleh ada
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }
    
    public function budgetPost() { return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id'); }
    public function fiscalYear() { return $this->belongsTo(FiscalYear::class); }
}