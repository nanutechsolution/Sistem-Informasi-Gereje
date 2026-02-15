<?php

namespace App\Livewire\Finance;

use App\Models\Member;
use App\Models\DiakoniaRequest;
use App\Models\RefDiakoniaType;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\RefUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DiakoniaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $is_external = false;

    // Form data
    public $member_id, $nama_luar, $ref_diakonia_type_id, $tanggal_pemberian, $alasan_bantuan;
    public $ref_account_id, $ref_budget_post_id;
    public $items = [];
    public $total_nominal = 0;

    // Search Helpers
    public $searchMember = '', $selectedMemberName = '', $foundMembers = [];

    protected function rules() {
        return [
            'is_external' => 'required|boolean',
            'member_id' => $this->is_external ? 'nullable' : 'required|exists:members,id',
            'nama_luar' => $this->is_external ? 'required|string|max:255' : 'nullable',
            'ref_diakonia_type_id' => 'required|exists:ref_diakonia_types,id',
            'tanggal_pemberian' => 'required|date',
            'ref_account_id' => 'required|exists:ref_accounts,id',
            'ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.ref_unit_id' => 'required|exists:ref_units,id',
            'items.*.nominal' => 'required|numeric|min:0',
            'alasan_bantuan' => 'required|string|min:5',
        ];
    }

    protected $messages = [
        'member_id.required' => 'Wajib memilih jemaat.',
        'nama_luar.required' => 'Nama penerima luar wajib diisi.',
        'ref_diakonia_type_id.required' => 'Jenis diakonia wajib dipilih.',
        'alasan_bantuan.required' => 'Alasan bantuan wajib diisi.',
        'items.*.nama_item.required' => 'Nama bantuan wajib diisi.',
        'items.*.ref_unit_id.required' => 'Wajib pilih satuan.',
    ];

    public function mount()
    {
        $this->tanggal_pemberian = now()->format('Y-m-d');
        $this->ref_account_id = RefAccount::where('nama', 'like', '%Kas Umum%')->first()?->id;
        $this->ref_budget_post_id = RefBudgetPost::where('nama', 'like', '%Diakonia%')->first()?->id;
        $this->resetItems();
    }

    public function resetItems()
    {
        $defaultUnit = RefUnit::where('is_active', true)->first()?->id;
        $this->items = [['nama_item' => 'Bantuan Tunai', 'qty' => 1, 'ref_unit_id' => $defaultUnit, 'nominal' => 0]];
        $this->calculateTotal();
    }

    public function addItem()
    {
        $defaultUnit = RefUnit::where('is_active', true)->first()?->id;
        $this->items[] = ['nama_item' => '', 'qty' => 1, 'ref_unit_id' => $defaultUnit, 'nominal' => 0];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotal();
        }
    }

    public function updatedItems() { $this->calculateTotal(); }

    public function calculateTotal()
    {
        $this->total_nominal = collect($this->items)->sum(fn($i) => (float)($i['nominal'] ?? 0) * (float)($i['qty'] ?? 1));
    }

    public function updatedSearchMember($value)
    {
        if (strlen($value) < 3) return $this->foundMembers = [];
        $this->foundMembers = Member::whereHas('churchPeople', function($q) use ($value) {
            $q->where('full_name', 'like', "%{$value}%")->orWhere('nik', 'like', "%{$value}%");
        })->with('churchPeople')->limit(5)->get();
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; 
        $this->foundMembers = [];
        $this->resetErrorBag('member_id');
    }

    public function save()
    {
        $this->validate();
        $fiscalYear = FiscalYear::where('is_active', true)->first();
        if (!$fiscalYear) return $this->dispatch('notify', message: 'Tahun anggaran aktif tidak ditemukan.', type: 'error');

        try {
            DB::transaction(function () use ($fiscalYear) {
                $request = DiakoniaRequest::create([
                    'uuid' => (string) Str::uuid(),
                    'member_id' => $this->is_external ? null : $this->member_id,
                    'nama_luar' => $this->is_external ? $this->nama_luar : null,
                    'ref_diakonia_type_id' => $this->ref_diakonia_type_id,
                    'fiscal_year_id' => $fiscalYear->id,
                    'nominal' => $this->total_nominal,
                    'tanggal_pemberian' => $this->tanggal_pemberian,
                    'alasan_bantuan' => $this->alasan_bantuan,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'tanggal_approved' => now(),
                ]);

                foreach ($this->items as $item) {
                    $unit = RefUnit::find($item['ref_unit_id']);
                    $request->items()->create([
                        'nama_item' => $item['nama_item'],
                        'qty' => $item['qty'],
                        'satuan' => $unit?->nama ?? 'Unit',
                        'nominal' => $item['nominal']
                    ]);
                }

                $penerima = $this->is_external ? $this->nama_luar : $this->selectedMemberName;
                $trx = Transaction::create([
                    'uuid' => (string) Str::uuid(),
                    'fiscal_year_id' => $fiscalYear->id,
                    'tanggal' => $this->tanggal_pemberian,
                    'jenis' => 'keluar',
                    'ref_account_id' => $this->ref_account_id,
                    'ref_budget_post_id' => $this->ref_budget_post_id,
                    'nominal' => $this->total_nominal,
                    'keterangan' => "Penyaluran Diakonia: {$penerima}",
                    'user_id' => Auth::id(),
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                $request->update(['transaction_id' => $trx->id]);
            });

            $this->isModalOpen = false;
            $this->dispatch('notify', message: 'Data berhasil disimpan dan dijurnal.', type: 'success');
            $this->reset(['member_id', 'nama_luar', 'selectedMemberName', 'alasan_bantuan', 'is_external']);
            $this->resetItems();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Terjadi kesalahan: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.finance.diakonia-manager', [
            'requests' => DiakoniaRequest::with(['member.churchPeople', 'type', 'items'])
                ->where(function ($q) {
                    $q->whereHas('member.churchPeople', fn($m) => $m->where('full_name', 'like', "%{$this->search}%"))
                      ->orWhere('nama_luar', 'like', "%{$this->search}%");
                })->latest()->paginate(10),
            'types' => RefDiakoniaType::where('is_active', true)->get(),
            'units' => RefUnit::where('is_active', true)->get(),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')->get(),
        ]);
    }

    public function exportPdf($id)
    {
        $request = DiakoniaRequest::with(['member.churchPeople', 'type', 'items', 'transaction'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.diakonia-report', ['data' => $request, 'app_name' => 'SIG-GKS'])->setPaper('a4', 'portrait');
        return response()->streamDownload(fn() => print($pdf->output()), "Bantuan-Diakonia-{$id}.pdf");
    }
}