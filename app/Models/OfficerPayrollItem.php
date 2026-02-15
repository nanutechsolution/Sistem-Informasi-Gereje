<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficerPayrollItem extends Model
{
    use HasFactory;

    protected $table = 'officer_payroll_items';

    protected $fillable = [
        'officer_payroll_id',
        'ref_salary_component_id',
        'ref_budget_post_id',
        'nama_snapshot',
        'jenis',
        'nominal_snapshot',
    ];


    /**
     * Relasi ke OfficerPayroll
     */
    public function payroll()
    {
        return $this->belongsTo(OfficerPayroll::class, 'officer_payroll_id');
    }

    public function component()
    {
        return $this->belongsTo(RefSalaryComponent::class, 'ref_salary_component_id');
    }
    /**
     * Relasi ke RefSalaryComponent
     */
    public function salaryComponent()
    {
        return $this->belongsTo(RefSalaryComponent::class, 'ref_salary_component_id');
    }

    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }
   
}
