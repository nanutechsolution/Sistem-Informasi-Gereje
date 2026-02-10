<?php

namespace App\Livewire\Finance;

use App\Models\Member;
use App\Models\Family;
use App\Models\FiscalYear;
use App\Models\RefDueType;
use App\Models\DuesRegistry;
use App\Models\DuesLog;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\Asset;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FlexibleDuesManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    // State Filter & UI
    public $search = '', $filterYear, $typeFilter = '';
    public $isModalOpen = false, $isPayModalOpen = false, $isSingleModalOpen = false;

    // Form Properti: Generate Masal (Otomatis)
    public $due_type_id, $nominal_standar = 0, $qty_standar = 0;

    // Form Properti: Input Manual (Single)
    public $single_due_type_id, $single_target_nominal = 0, $single_target_qty = 0;
    public $searchAssignee = '', $selectedAssigneeId, $selectedAssigneeType, $selectedAssigneeName;
    public $foundAssignees = [];

    // Form Properti: Bayar/Update
    public $activeDue, $pay_nominal, $pay_qty, $ref_account_id, $ref_budget_post_id;

    protected $queryString = ['search', 'filterYear', 'typeFilter'];

    public function mount()
    {
        $activeYear = FiscalYear::active();
        $this->filterYear = $activeYear ? $activeYear->id : FiscalYear::latest('tahun')->first()?->id;

        $acc = RefAccount::where('nama', 'like', '%Umum%')->first();
        $this->ref_account_id = $acc?->id;
    }

    // --- FITUR GENERATE OTOMATIS (MASAL) ---
    public function generateBulk()
    {
        $this->validate([
            'due_type_id' => 'required|exists:ref_due_types,id',
        ], [
            'due_type_id.required' => 'Pilih jenis iuran yang akan digenerate.',
        ]);

        $dueType = RefDueType::find($this->due_type_id);
        $nominal = (float) str_replace(['.', ','], '', $this->nominal_standar);

        // Ambil sasaran (Member atau Family)
        $targets = $dueType->target_level === 'member'
            ? Member::where('is_active', true)->get()
            : Family::where('status', 'aktif')->get();

        $count = 0;
        foreach ($targets as $target) {
            $exists = DuesRegistry::where('ref_due_type_id', $this->due_type_id)
                ->where('fiscal_year_id', $this->filterYear)
                ->where('assignee_id', $target->id)
                ->where('assignee_type', get_class($target))
                ->exists();

            if (!$exists) {
                DuesRegistry::create([
                    'uuid' => (string) Str::uuid(),
                    'ref_due_type_id' => $this->due_type_id,
                    'fiscal_year_id' => $this->filterYear,
                    'assignee_id' => $target->id,
                    'assignee_type' => get_class($target), // Menyimpan App\Models\Member atau App\Models\Family
                    'target_nominal' => $nominal,
                    'target_qty' => $this->qty_standar ?: 0,
                    'status' => 'belum'
                ]);
                $count++;
            }
        }

        $this->dispatch('notify', message: "$count data iuran berhasil dibuat otomatis.", type: 'success');
        $this->isModalOpen = false;
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedTypeFilter()
    {
        $this->resetPage();
    }
    public function updatedFilterYear()
    {
        $this->resetPage();
    }

    // --- LOGIKA PENCARIAN SUBJEK ---
    public function updatedSearchAssignee($value)
    {
        if (strlen($value) < 3) {
            $this->foundAssignees = [];
            return;
        }

        $members = Member::where('nama', 'like', "%{$value}%")
            ->limit(5)->get()->map(fn($m) => [
                'id' => $m->id,
                'type' => Member::class, // Full Class Path
                'nama' => $m->nama,
                'label' => "👤 Jiwa: {$m->nama}"
            ]);

        $families = Family::where('kepala_keluarga', 'like', "%{$value}%")
            ->limit(5)->get()->map(fn($f) => [
                'id' => $f->id,
                'type' => Family::class, // Full Class Path
                'nama' => $f->kepala_keluarga,
                'label' => "🏠 KK: {$f->kepala_keluarga}"
            ]);

        $this->foundAssignees = $members->concat($families)->toArray();
    }

    public function selectAssignee($id, $type, $name)
    {
        $this->selectedAssigneeId = $id;
        $this->selectedAssigneeType = $type;
        $this->selectedAssigneeName = $name;
        $this->searchAssignee = '';
        $this->foundAssignees = [];
    }

    // --- SIMPAN PENDAFTARAN MANUAL (SINGLE) ---
    public function saveSingle()
    {
        $this->validate([
            'selectedAssigneeId' => 'required',
            'single_due_type_id' => 'required|exists:ref_due_types,id',
        ], [
            'selectedAssigneeId.required' => 'Wajib memilih Jemaat/KK dari hasil pencarian.',
        ]);

        $nominal = (float) str_replace(['.', ','], '', $this->single_target_nominal);

        DuesRegistry::updateOrCreate([
            'ref_due_type_id' => $this->single_due_type_id,
            'fiscal_year_id' => $this->filterYear,
            'assignee_id' => $this->selectedAssigneeId,
            'assignee_type' => $this->selectedAssigneeType,
        ], [
            'uuid' => (string) Str::uuid(),
            'target_nominal' => $nominal,
            'target_qty' => $this->single_target_qty ?: 0,
            'status' => 'belum'
        ]);

        $this->dispatch('notify', message: 'Tanggungan berhasil didaftarkan.', type: 'success');
        $this->isSingleModalOpen = false;
        $this->reset(['selectedAssigneeId', 'selectedAssigneeType', 'selectedAssigneeName', 'single_due_type_id', 'single_target_nominal', 'single_target_qty']);
    }

    // --- LOGIKA SETORAN ---
    public function openPayModal($id)
    {
        $this->activeDue = DuesRegistry::with(['dueType', 'assignee'])->findOrFail($id);
        $this->pay_nominal = number_format($this->activeDue->sisa_nominal, 0, ',', '.');
        $this->pay_qty = $this->activeDue->sisa_qty;

        $pos = RefBudgetPost::where('nama', 'like', "%{$this->activeDue->dueType->nama}%")->first();
        $this->ref_budget_post_id = $pos?->id;

        $this->isPayModalOpen = true;
    }

    public function savePayment()
    {
        $this->validate(['ref_account_id' => 'required']);

        DB::transaction(function () {
            $nominal = (float) str_replace(['.', ','], '', $this->pay_nominal);
            $qty = (int) $this->pay_qty;
            $trxId = null;

            if ($this->activeDue->dueType->unit_type == 'money') {
                $trx = Transaction::create([
                    'uuid' => Str::uuid(),
                    'fiscal_year_id' => $this->filterYear,
                    'tanggal' => now(),
                    'jenis' => 'masuk',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $this->ref_budget_post_id,
                    'nominal' => $nominal,
                    'keterangan' => "Setoran " . $this->activeDue->dueType->nama . ": " . ($this->activeDue->assignee->nama ?? $this->activeDue->assignee->kepala_keluarga),
                    'user_id' => Auth::id(),
                ]);
                $trxId = $trx->id;
            } else {
                $asset = Asset::where('nama_aset', $this->activeDue->dueType->nama)->first();
                if ($asset) {
                    $asset->increment('jumlah', $qty);
                } else {
                    Asset::create([
                        'uuid' => (string) Str::uuid(),
                        'nama_aset' => $this->activeDue->dueType->nama,
                        'kategori' => 'Bangunan',
                        'jumlah' => $qty,
                        'satuan' => $this->activeDue->dueType->satuan_barang ?? 'Unit',
                        'asal_perolehan' => 'hibah_jemaat',
                        'tanggal_perolehan' => now(),
                    ]);
                }
            }

            DuesLog::create([
                'uuid' => Str::uuid(),
                'dues_registry_id' => $this->activeDue->id,
                'transaction_id' => $trxId,
                'nominal' => $nominal,
                'qty' => $qty,
                'tanggal_serah' => now(),
                'user_id' => Auth::id(),
            ]);

            $this->activeDue->update([
                'current_paid_nominal' => $this->activeDue->current_paid_nominal + $nominal,
                'current_paid_qty' => $this->activeDue->current_paid_qty + $qty,
            ]);

            $this->activeDue->refresh();
            $isLunas = ($this->activeDue->dueType->unit_type == 'money')
                ? ($this->activeDue->current_paid_nominal >= $this->activeDue->target_nominal)
                : ($this->activeDue->current_paid_qty >= $this->activeDue->target_qty);

            $this->activeDue->update(['status' => $isLunas ? 'lunas' : 'cicil']);
        });

        $this->dispatch('notify', message: 'Setoran berhasil disimpan.', type: 'success');
        $this->isPayModalOpen = false;
    }

    public function render()
    {
        $query = DuesRegistry::with(['dueType', 'assignee', 'fiscalYear'])
            ->where('fiscal_year_id', $this->filterYear)
            ->when($this->typeFilter, fn($q) => $q->where('ref_due_type_id', $this->typeFilter))
            ->where(function ($q) {
                if ($this->search) {
                    $q->whereHasMorph('assignee', [Member::class, Family::class], function ($mq, $type) {
                        if ($type === Member::class) {
                            $mq->where('nama', 'like', "%{$this->search}%");
                        } else {
                            $mq->where('kepala_keluarga', 'like', "%{$this->search}%");
                        }
                    });
                }
            });

        return view('livewire.finance.flexible-dues-manager', [
            'dues' => $query->latest()->paginate(15),
            'dueTypes' => RefDueType::where('is_active', true)->get(),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->orderBy('kode')->get(),
            'years' => FiscalYear::orderBy('tahun', 'desc')->get(),
        ]);
    }
}
