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

    // Filter & UI States
    public $search = '', $filterYear, $typeFilter = '';
    public $isModalOpen = false, $isPayModalOpen = false, $isSingleModalOpen = false;

    // Bulk Generate
    public $due_type_id, $nominal_standar = 0, $qty_standar = 0;

    // Single Input
    public $single_due_type_id, $single_target_nominal = 0, $single_target_qty = 0;
    public $searchAssignee = '', $selectedAssigneeId, $selectedAssigneeType, $selectedAssigneeName;
    public $foundAssignees = [];

    // Payment
    public $activeDue, $pay_nominal, $pay_qty, $ref_account_id, $ref_budget_post_id;

    protected $queryString = ['search', 'filterYear', 'typeFilter'];

    protected $messages = [
        'due_type_id.required' => 'Pilih jenis iuran yang akan dibuat.',
        'selectedAssigneeId.required' => 'Wajib memilih jemaat/keluarga dari daftar.',
        'single_due_type_id.required' => 'Jenis iuran wajib dipilih.',
        'ref_account_id.required' => 'Pilih kas penyimpanan uang.',
    ];

    public function mount()
    {
        $activeYear = FiscalYear::where('is_active', true)->first();
        $this->filterYear = $activeYear ? $activeYear->id : FiscalYear::latest('tahun')->first()?->id;
        $this->ref_account_id = RefAccount::where('nama', 'like', '%Umum%')->first()?->id;
    }

    private function parseRupiah($value)
    {
        if (!$value) return 0;
        return (float) preg_replace('/[^0-9]/', '', (string) $value);
    }

    public function generateBulk()
    {
        $this->validate(['due_type_id' => 'required|exists:ref_due_types,id']);
        
        $dueType = RefDueType::find($this->due_type_id);
        $nominal = $this->parseRupiah($this->nominal_standar);
        
        $targets = $dueType->target_level === 'member'
            ? Member::active()->get()
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
                    'assignee_type' => get_class($target),
                    'target_nominal' => $nominal,
                    'target_qty' => $this->qty_standar ?: 0,
                    'status' => 'belum'
                ]);
                $count++;
            }
        }

        $this->dispatch('notify', message: "$count tanggungan berhasil dibuat otomatis.", type: 'success');
        $this->isModalOpen = false;
    }

    public function updatedSearchAssignee($value)
    {
        if (strlen($value) < 3) return $this->foundAssignees = [];

        $members = Member::active()->whereHas('churchPeople', fn($q) => $q->where('full_name', 'like', "%{$value}%"))
            ->with('churchPeople')->limit(5)->get()->map(fn($m) => [
                'id' => $m->id, 'type' => Member::class, 'name' => $m->churchPeople->full_name, 'label' => "👤 Jiwa: {$m->churchPeople->full_name}"
            ]);

        $families = Family::where('status', 'aktif')->whereHas('members.churchPeople', fn($q) => $q->where('full_name', 'like', "%{$value}%"))
            ->limit(5)->get()->map(fn($f) => [
                'id' => $f->id, 'type' => Family::class, 'name' => "Keluarga #{$f->nomor_kk}", 'label' => "🏠 KK: {$f->nomor_kk}"
            ]);

        $this->foundAssignees = $members->concat($families)->toArray();
    }

    public function selectAssignee($id, $type, $name)
    {
        $this->selectedAssigneeId = $id;
        $this->selectedAssigneeType = $type;
        $this->selectedAssigneeName = $name;
        $this->foundAssignees = [];
    }

    public function saveSingle()
    {
        $this->validate(['selectedAssigneeId' => 'required', 'single_due_type_id' => 'required']);
        
        DuesRegistry::updateOrCreate([
            'ref_due_type_id' => $this->single_due_type_id,
            'fiscal_year_id' => $this->filterYear,
            'assignee_id' => $this->selectedAssigneeId,
            'assignee_type' => $this->selectedAssigneeType,
        ], [
            'uuid' => (string) Str::uuid(),
            'target_nominal' => $this->parseRupiah($this->single_target_nominal),
            'target_qty' => $this->single_target_qty ?: 0,
            'status' => 'belum'
        ]);

        $this->isSingleModalOpen = false;
        $this->dispatch('notify', message: 'Tanggungan berhasil didaftarkan.', type: 'success');
        $this->reset(['selectedAssigneeId', 'selectedAssigneeName', 'single_due_type_id', 'single_target_nominal']);
    }

    public function openPayModal($id)
    {
        $this->activeDue = DuesRegistry::with(['dueType', 'assignee'])->findOrFail($id);
        $this->pay_nominal = number_format($this->activeDue->sisa_nominal, 0, '', '');
        $this->pay_qty = $this->activeDue->sisa_qty;
        $this->ref_budget_post_id = RefBudgetPost::where('nama', 'like', "%{$this->activeDue->dueType->nama}%")->first()?->id;
        $this->isPayModalOpen = true;
    }

    public function savePayment()
    {
        $this->validate(['ref_account_id' => 'required']);

        DB::transaction(function () {
            $nominal = $this->parseRupiah($this->pay_nominal);
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
                    'keterangan' => "Iuran {$this->activeDue->dueType->nama}: " . $this->selectedAssigneeName,
                    'user_id' => Auth::id(),
                ]);
                $trxId = $trx->id;
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

            $isLunas = ($this->activeDue->dueType->unit_type == 'money')
                ? ($this->activeDue->current_paid_nominal >= $this->activeDue->target_nominal)
                : ($this->activeDue->current_paid_qty >= $this->activeDue->target_qty);

            $this->activeDue->update(['status' => $isLunas ? 'lunas' : 'cicil']);
        });

        $this->isPayModalOpen = false;
        $this->dispatch('notify', message: 'Pembayaran berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        // Memperbaiki eager loading polimorfik untuk menghindari error pada model Family
        $query = DuesRegistry::with([
                'dueType', 
                'fiscalYear',
                'assignee' => function ($morphTo) {
                    $morphTo->morphWith([
                        Member::class => ['churchPeople'],
                    ]);
                }
            ])
            ->where('fiscal_year_id', $this->filterYear)
            ->when($this->typeFilter, fn($q) => $q->where('ref_due_type_id', $this->typeFilter))
            ->where(function ($q) {
                if ($this->search) {
                    $q->whereHasMorph('assignee', [Member::class, Family::class], function ($mq, $type) {
                        if ($type === Member::class) {
                            $mq->whereHas('churchPeople', fn($cp) => $cp->where('full_name', 'like', "%{$this->search}%"));
                        } else {
                            $mq->where('nomor_kk', 'like', "%{$this->search}%");
                        }
                    });
                }
            });

        return view('livewire.finance.flexible-dues-manager', [
            'dues' => $query->latest()->paginate(15),
            'dueTypes' => RefDueType::where('is_active', true)->get(),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'years' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->get()
        ]);
    }
}