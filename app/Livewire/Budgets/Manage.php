<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use App\Models\FiscalYear;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Illuminate\Support\Str;

class Manage extends Component
{
    public $fiscalYearId;
    public $targets = []; // Array input [post_id => nominal]

    // State Modal
    public $isYearModalOpen = false;
    public $isPostModalOpen = false;

    // Form Tambah Tahun
    public $newYear;
    public $newYearDesc;

    // Form Tambah Pos
    public $newPostCode;
    public $newPostName;
    public $newPostType = 'pengeluaran';
    public $newPostParentId;

    public function mount()
    {
        // Default ke tahun aktif
        $active = FiscalYear::active();
        if ($active) {
            $this->fiscalYearId = $active->id;
            $this->loadData();
        }
    }

    public function updatedFiscalYearId()
    {
        $this->loadData();
    }

    public function loadData()
    {
        if (!$this->fiscalYearId) return;

        // Ambil semua pos
        $posts = RefBudgetPost::orderBy('kode')->get();
        
        // Ambil budget yang sudah ada
        $existingBudgets = Budget::where('fiscal_year_id', $this->fiscalYearId)
            ->pluck('nominal_target', 'ref_budget_post_id');

        foreach ($posts as $post) {
            $this->targets[$post->id] = number_format($existingBudgets[$post->id] ?? 0, 0, '', '');
        }
    }

    public function save()
    {
        if (!$this->fiscalYearId) return;

        foreach ($this->targets as $postId => $nominal) {
            $cleanNominal = (float) str_replace('.', '', $nominal);
            Budget::updateOrCreate(
                ['fiscal_year_id' => $this->fiscalYearId, 'ref_budget_post_id' => $postId],
                ['nominal_target' => $cleanNominal]
            );
        }

        $this->dispatch('notify', message: 'RAPB berhasil disimpan!', type: 'success');
    }

    // --- LOGIKA SET TAHUN AKTIF ---
    public function activateYear()
    {
        if (!$this->fiscalYearId) return;

        // 1. Non-aktifkan semua tahun
        FiscalYear::query()->update(['is_active' => false]);

        // 2. Aktifkan tahun yang dipilih
        $year = FiscalYear::find($this->fiscalYearId);
        $year->update(['is_active' => true]);

        $this->dispatch('notify', message: "Tahun Anggaran {$year->tahun} sekarang AKTIF!", type: 'success');
    }

    // --- LOGIKA TAMBAH TAHUN ---
    public function saveYear()
    {
        $this->validate([
            'newYear' => 'required|numeric|digits:4|unique:fiscal_years,tahun',
            'newYearDesc' => 'nullable|string',
        ]);

        $year = FiscalYear::create([
            'uuid' => Str::uuid(),
            'tahun' => $this->newYear,
            'keterangan' => $this->newYearDesc,
            'is_active' => false // Default tidak aktif
        ]);

        $this->fiscalYearId = $year->id; // Pindah ke tahun baru
        $this->loadData();
        $this->isYearModalOpen = false;
        $this->reset(['newYear', 'newYearDesc']);
        $this->dispatch('notify', message: 'Tahun Anggaran baru berhasil dibuat!', type: 'success');
    }

    // --- LOGIKA TAMBAH POS ---
    public function savePost()
    {
        $this->validate([
            'newPostCode' => 'required|unique:ref_budget_posts,kode',
            'newPostName' => 'required|min:3',
            'newPostType' => 'required|in:pemasukan,pengeluaran',
            'newPostParentId' => 'nullable|exists:ref_budget_posts,id',
        ]);

        // Jika punya parent, ikuti jenis parent
        if ($this->newPostParentId) {
            $parent = RefBudgetPost::find($this->newPostParentId);
            $this->newPostType = $parent->jenis;
        }

        RefBudgetPost::create([
            'uuid' => Str::uuid(),
            'kode' => $this->newPostCode,
            'nama' => $this->newPostName,
            'jenis' => $this->newPostType,
            'parent_id' => $this->newPostParentId,
            'is_active' => true,
        ]);

        $this->loadData(); // Refresh list pos
        $this->isPostModalOpen = false;
        $this->reset(['newPostCode', 'newPostName', 'newPostType', 'newPostParentId']);
        $this->dispatch('notify', message: 'Pos Anggaran baru berhasil ditambahkan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.budgets.manage', [
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'groupedPosts' => RefBudgetPost::with('children.children')->whereNull('parent_id')->orderBy('kode')->get(),
            // List untuk dropdown parent di modal (hanya level 1 & 2)
            'parentPosts' => RefBudgetPost::whereNull('parent_id')->orWhereHas('parent', function($q){ $q->whereNull('parent_id'); })->orderBy('kode')->get()
        ]);
    }
}