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
        // Default ke tahun anggaran aktif
        $active = FiscalYear::where('is_active', true)->first();
        $this->fiscalYearId = $active ? $active->id : null;
    }

    public function render()
    {
        $fiscalYear = FiscalYear::find($this->fiscalYearId);
        
        // Ambil struktur budget mulai dari Root (Level 1)
        $budgetPosts = RefBudgetPost::with(['children'])
            ->whereNull('parent_id')
            ->when($this->search, function($q) {
                $q->where('nama', 'like', '%'.$this->search.'%')
                  ->orWhere('kode', 'like', '%'.$this->search.'%');
            })
            ->orderBy('kode')
            ->get();

        // Transformasi data secara rekursif agar detail sub-pos terbawa
        $reportData = $budgetPosts->map(function($post) use ($fiscalYear) {
            return $this->formatPostData($post, $fiscalYear);
        });

        return view('livewire.reports.budget-realization', [
            'reportData' => $reportData,
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'activeYear' => $fiscalYear
        ]);
    }

    /**
     * Fungsi Rekursif: Menghitung Realisasi & Target termasuk semua anak-cucunya
     */
    private function formatPostData($post, $fiscalYear)
    {
        // 1. Ambil Target Langsung di Pos ini
        $target = $post->budgets()->where('fiscal_year_id', $this->fiscalYearId)->first()?->nominal_target ?? 0;

        // 2. Ambil Realisasi Langsung dari Transaksi di Pos ini
        $realization = Transaction::where('ref_budget_post_id', $post->id)
            ->where('fiscal_year_id', $this->fiscalYearId)
            ->sum('nominal');

        // 3. Proses Anak-anak secara rekursif
        $children = $post->children->map(function($child) use ($fiscalYear) {
            return $this->formatPostData($child, $fiscalYear);
        });

        // 4. Akumulasi Total (Diri Sendiri + Semua Keturunan)
        // Penting: Induk (2.1) akan otomatis menjumlahkan semua sub-pos (2.1.1, 2.1.2, dst)
        $totalTarget = $target + $children->sum('totalTarget');
        $totalRealization = $realization + $children->sum('totalRealization');

        return [
            'id' => $post->id,
            'kode' => $post->kode,
            'nama' => $post->nama,
            'jenis' => $post->jenis,
            'target' => $target,
            'realization' => $realization,
            'totalTarget' => $totalTarget,
            'totalRealization' => $totalRealization,
            'percentage' => $totalTarget > 0 ? ($totalRealization / $totalTarget) * 100 : 0,
            'children' => $children,
            'has_children' => $children->count() > 0
        ];
    }
}