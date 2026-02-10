<?php

namespace App\Livewire\Finance;

use App\Models\Asset;
use App\Models\Member;
use App\Models\Family;
use App\Models\FiscalYear;
use App\Models\RefDueType;
use App\Models\DuesRegistry;
use App\Models\DuesLog;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefPekerjaan;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FlexibleDuesManager extends Component
{
    use WithPagination;

    public $filterYear, $search = '', $typeFilter = '';
    public $isModalOpen = false, $isPayModalOpen = false, $isSingleModalOpen = false;

    // Form Bulk Assign
    public $due_type_id, $assign_method = 'pekerjaan';
    public $pekerjaan_id, $nominal_standar = 0;

    // Form Tambah Manual (Flexible)
    public $single_due_type_id, $single_target_nominal = 0, $single_target_qty = 0;
    public $searchAssignee = '', $foundAssignees = [], $selectedAssigneeId, $selectedAssigneeType, $selectedAssigneeName;

    // Form Pembayaran
    public $activeDue, $pay_nominal, $pay_qty, $ref_account_id, $ref_budget_post_id;

    protected function rules()
    {
        return [
            'due_type_id' => 'nullable|exists:ref_due_types,id',
            'single_due_type_id' => 'nullable|exists:ref_due_types,id',
            'nominal_standar' => 'nullable|numeric',
            'pay_nominal' => 'nullable',
            'ref_account_id' => 'nullable|exists:ref_accounts,id',
            'ref_budget_post_id' => 'nullable|exists:ref_budget_posts,id',
        ];
    }

    /**
     * Helper: Menentukan kategori aset secara otomatis berdasarkan nama barang
     */
    private function inferCategory($name)
    {
        $name = strtolower($name);
        if (Str::contains($name, ['semen', 'batu', 'pasir', 'bambu', 'cat', 'keramik'])) return 'Bangunan';
        if (Str::contains($name, ['kursi', 'meja', 'lemari', 'mimbar'])) return 'Mebeul';
        if (Str::contains($name, ['mic', 'sound', 'speaker', 'lampu', 'kabel'])) return 'Elektronik';
        return 'Lainnya';
    }

    public function mount()
    {
        $this->filterYear = FiscalYear::active()?->id;
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first() ?: RefAccount::where('jenis', 'kas_tunai')->first();
        $this->ref_account_id = $acc?->id;
    }

    // --- FITUR BARU: INPUT MANUAL FLEXIBLE ---

    public function openSingleModal()
    {
        $this->reset(['single_due_type_id', 'single_target_nominal', 'single_target_qty', 'searchAssignee', 'foundAssignees', 'selectedAssigneeId', 'selectedAssigneeType', 'selectedAssigneeName']);
        $this->isSingleModalOpen = true;
    }

    public function updatedSearchAssignee($value)
    {
        if (strlen($value) < 2) {
            $this->foundAssignees = [];
            return;
        }

        // Cari di Member
        $members = Member::where('nama', 'like', "%{$value}%")->limit(5)->get()->map(fn($m) => [
            'id' => $m->id,
            'type' => Member::class,
            'nama' => $m->nama,
            'label' => '(Jiwa) ' . $m->nama
        ]);

        // Cari di Keluarga
        $families = Family::where('kepala_keluarga', 'like', "%{$value}%")->limit(5)->get()->map(fn($f) => [
            'id' => $f->id,
            'type' => Family::class,
            'nama' => $f->kepala_keluarga,
            'label' => '(KK) ' . $f->kepala_keluarga
        ]);

        $this->foundAssignees = $members->merge($families)->toArray();
    }

    public function selectAssignee($id, $type, $name)
    {
        $this->selectedAssigneeId = $id;
        $this->selectedAssigneeType = $type;
        $this->selectedAssigneeName = $name;
        $this->searchAssignee = $name;
        $this->foundAssignees = [];
    }

    public function saveSingle()
    {
        $this->validate([
            'single_due_type_id' => 'required',
            'selectedAssigneeId' => 'required',
        ]);

        $exists = DuesRegistry::where('ref_due_type_id', $this->single_due_type_id)
            ->where('fiscal_year_id', $this->filterYear)
            ->where('assignee_type', $this->selectedAssigneeType)
            ->where('assignee_id', $this->selectedAssigneeId)
            ->exists();

        if ($exists) {
            $this->dispatch('notify', message: 'Gagal! Orang/Keluarga ini sudah terdaftar di iuran tersebut.', type: 'error');
            return;
        }

        DuesRegistry::create([
            'uuid' => Str::uuid(),
            'ref_due_type_id' => $this->single_due_type_id,
            'fiscal_year_id' => $this->filterYear,
            'assignee_type' => $this->selectedAssigneeType,
            'assignee_id' => $this->selectedAssigneeId,
            'target_nominal' => (float) $this->single_target_nominal,
            'target_qty' => (int) $this->single_target_qty,
        ]);

        $this->dispatch('notify', message: 'Tanggungan manual berhasil ditambahkan.', type: 'success');
        $this->isSingleModalOpen = false;
    }

    // --- FITUR LAMA (BULK, PAYMENT) ---

    public function bulkAssign()
    {
        $this->validate([
            'due_type_id' => 'required',
            'nominal_standar' => 'required|numeric|min:1'
        ]);

        $type = RefDueType::find($this->due_type_id);
        $count = 0;

        if ($type->target_level == 'member') {
            $members = Member::where('status_sidi', 'Sudah')
                ->when($this->pekerjaan_id, fn($q) => $q->where('pekerjaan_id', $this->pekerjaan_id))
                ->get();

            foreach ($members as $m) {
                $exists = DuesRegistry::where('ref_due_type_id', $this->due_type_id)
                    ->where('fiscal_year_id', $this->filterYear)
                    ->where('assignee_type', Member::class)
                    ->where('assignee_id', $m->id)
                    ->exists();

                if (!$exists) {
                    DuesRegistry::create([
                        'uuid' => Str::uuid(),
                        'ref_due_type_id' => $this->due_type_id,
                        'fiscal_year_id' => $this->filterYear,
                        'assignee_type' => Member::class,
                        'assignee_id' => $m->id,
                        'target_nominal' => $this->nominal_standar,
                    ]);
                    $count++;
                }
            }
        } else {
            $families = Family::active()->get();
            foreach ($families as $f) {
                $exists = DuesRegistry::where('ref_due_type_id', $this->due_type_id)
                    ->where('fiscal_year_id', $this->filterYear)
                    ->where('assignee_type', Family::class)
                    ->where('assignee_id', $f->id)
                    ->exists();

                if (!$exists) {
                    DuesRegistry::create([
                        'uuid' => Str::uuid(),
                        'ref_due_type_id' => $this->due_type_id,
                        'fiscal_year_id' => $this->filterYear,
                        'assignee_type' => Family::class,
                        'assignee_id' => $f->id,
                        'target_nominal' => $this->nominal_standar,
                    ]);
                    $count++;
                }
            }
        }

        $this->dispatch('notify', message: "$count data baru berhasil dibuat.", type: 'success');
        $this->isModalOpen = false;
    }

    public function openPayModal($id)
    {
        $this->activeDue = DuesRegistry::with(['assignee', 'dueType'])->findOrFail($id);
        $this->pay_nominal = number_format($this->activeDue->sisa_nominal, 0, ',', '.');
        $this->pay_qty = $this->activeDue->sisa_qty;

        $keyword = Str::contains(strtolower($this->activeDue->dueType->nama), 'pembangunan') ? 'Pembangunan' : 'Iuran';
        $pos = RefBudgetPost::where('nama', 'like', "%$keyword%")->where('jenis', 'pemasukan')->first();
        $this->ref_budget_post_id = $pos?->id;

        $this->isPayModalOpen = true;
    }

    public function savePayment()
    {
        $validationRules = [];
        if ($this->activeDue->dueType->unit_type == 'money') {
            $validationRules = [
                'pay_nominal' => 'required',
                'ref_account_id' => 'required|exists:ref_accounts,id',
                'ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
            ];
        } else {
            $validationRules = ['pay_qty' => 'required|numeric|min:1'];
        }

        $this->validate($validationRules);

        DB::transaction(function () {
            $nominal = (float) str_replace(['.', ','], '', $this->pay_nominal);
            $qty = (int) $this->pay_qty;

            $trxId = null;

            // LOGIKA 1: Jika Uang -> Masuk Jurnal Transaksi
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
            }

            // LOGIKA 2: Jika Barang -> Masuk Inventaris Aset (OTOMATIS)
            else {
                // Cari aset dengan nama yang sama
                $existingAsset = Asset::where('nama_aset', $this->activeDue->dueType->nama)->first();

                if ($existingAsset) {
                    // Jika sudah ada, tambahkan jumlahnya saja
                    $existingAsset->increment('jumlah', $qty);
                    $existingAsset->update(['kondisi' => 'baik']); // Reset status jika ada penambahan baru
                } else {
                    // Jika belum ada, buat record aset baru
                    Asset::create([
                        'uuid' => (string) Str::uuid(),
                        'nama_aset' => $this->activeDue->dueType->nama,
                        'kategori' => $this->inferCategory($this->activeDue->dueType->nama),
                        'jumlah' => $qty,
                        'satuan' => $this->activeDue->dueType->satuan_barang,
                        'asal_perolehan' => 'hibah_jemaat',
                        'member_id' => $this->activeDue->assignee_type === Member::class ? $this->activeDue->assignee_id : null,
                        'tanggal_perolehan' => now(),
                        'catatan' => 'Input otomatis via Setoran Tanggungan Natura.',
                    ]);
                }
            }

            // Simpan Log Tanggungan
            DuesLog::create([
                'uuid' => Str::uuid(),
                'dues_registry_id' => $this->activeDue->id,
                'transaction_id' => $trxId,
                'nominal' => $nominal,
                'qty' => $qty,
                'tanggal_serah' => now(),
                'user_id' => Auth::id(),
            ]);

            // Update Status Progress Tanggungan Jemaat
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

        $this->isPayModalOpen = false;
        $this->dispatch('notify', message: 'Setoran berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        $query = DuesRegistry::with(['assignee', 'dueType', 'fiscalYear'])
            ->where('fiscal_year_id', $this->filterYear)
            ->when($this->typeFilter, fn($q) => $q->where('ref_due_type_id', $this->typeFilter))
            ->where(function ($q) {
                $q->whereHasMorph('assignee', [Member::class], fn($m) => $m->where('nama', 'like', "%{$this->search}%"))
                    ->orWhereHasMorph('assignee', [Family::class], fn($f) => $f->where('kepala_keluarga', 'like', "%{$this->search}%"));
            });

        return view('livewire.finance.flexible-dues-manager', [
            'dues' => $query->paginate(15),
            'dueTypes' => RefDueType::active()->get(),
            'pekerjaans' => RefPekerjaan::all(),
            'years' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->whereNotNull('parent_id')->orderBy('kode')->get()
        ]);
    }
}
