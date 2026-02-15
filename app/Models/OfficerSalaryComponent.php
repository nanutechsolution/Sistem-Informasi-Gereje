<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OfficerSalaryComponent extends Model
{
    use HasFactory;

    protected $table = 'officer_salary_components';

    protected $fillable = [
        'uuid',
        'church_officer_id',
        'ref_salary_component_id',
        'ref_budget_post_id',
        'nominal',
        'is_fixed',
        'tanggal_mulai',
        'tanggal_berakhir',
        'is_active',
    ];

    protected $casts = [
        'is_fixed' => 'boolean',
        'is_active' => 'boolean',
        'nominal' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    // Auto-generate UUID saat membuat record baru
    protected static function booted()
    {
        static::creating(function ($component) {
            if (empty($component->uuid)) {
                $component->uuid = (string) Str::uuid();
            }
        });
    }

    // Relasi ke ChurchOfficer
    public function churchOfficer()
    {
        return $this->belongsTo(ChurchOfficer::class, 'church_officer_id');
    }

    // Relasi opsional ke ref_budget_post
    public function budgetPost()
    {
        return $this->belongsTo(RefBudgetPost::class, 'ref_budget_post_id');
    }


    /**
     * Relasi ke RefSalaryComponent
     */
    public function refSalaryComponent()
    {
        return $this->belongsTo(RefSalaryComponent::class, 'ref_salary_component_id');
    }


    public function component()
    {
        return $this->belongsTo(RefSalaryComponent::class, 'ref_salary_component_id');
    }
}
