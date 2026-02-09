<?php

namespace App\Livewire\Reports;

use App\Models\RefBudgetPost;
use App\Models\FiscalYear;
use App\Models\Transaction;
use Livewire\Component;

class BudgetRealization extends Component
{
    public $fiscalYearId;
    public $search = '';

    public function mount()
    {
        $active = FiscalYear::where('is_active', true)->first();
        $this->fiscalYearId = $active ? $active->id : FiscalYear::latest('tahun')->first()?->id;
    }

    public function render()
    {
        $fiscalYear = FiscalYear::find($this->fiscalYearId);

        if (!$fiscalYear) {
            return view('livewire.reports.budget-realization', [
                'reportData' => collect(),
                'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
                'selectedYear' => null
            ]);
        }

        // Eager Load data hingga 3 level kedalaman untuk efisiensi
        $posts = RefBudgetPost::with([
            'children.children',
            // Load Relasi Budget (Target)
            'budgets' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
            'children.budgets' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
            'children.children.budgets' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
            // Load Relasi Transaction (Realisasi)
            'transactions' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
            'children.transactions' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
            'children.children.transactions' => fn($q) => $q->where('fiscal_year_id', $this->fiscalYearId),
        ])
        ->whereNull('parent_id')
        ->when($this->search, fn($q) => $q->where('nama', 'like', '%'.$this->search.'%'))
        ->orderBy('kode')
        ->get();

        // Proses kalkulasi hierarki
        $reportData = $posts->map(fn($post) => $this->calculateNode($post));

        return view('livewire.reports.budget-realization', [
            'reportData' => $reportData,
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'selectedYear' => $fiscalYear
        ]);
    }

    private function calculateNode($post)
    {
        // 1. Nilai Diri Sendiri
        $selfTarget = $post->budgets->first()?->nominal_target ?? 0;
        $selfRealization = $post->transactions->sum('nominal');

        // 2. Proses Anak-anak (Rekursif)
        $children = $post->children->map(fn($child) => $this->calculateNode($child));

        // 3. Akumulasi Total (Self + Children)
        // FIX: Menggunakan key 'totalTarget' dari hasil rekursif anak
        $totalTarget = $selfTarget + $children->sum('totalTarget');
        $totalRealization = $selfRealization + $children->sum('totalRealization');

        $percentage = $totalTarget > 0 ? ($totalRealization / $totalTarget) * 100 : 0;
        $diff = $totalTarget - $totalRealization;

        return [
            'id' => $post->id,
            'kode' => $post->kode,
            'nama' => $post->nama,
            'jenis' => $post->jenis,
            
            // Nilai Murni Pos Ini
            'selfTarget' => $selfTarget,
            'selfRealization' => $selfRealization,
            
            // Nilai Akumulasi (Untuk Summary)
            'totalTarget' => $totalTarget,
            'totalRealization' => $totalRealization,
            
            'diff' => $diff,
            'percentage' => $percentage,
            'children' => $children,
            'has_children' => $children->count() > 0
        ];
    }
}