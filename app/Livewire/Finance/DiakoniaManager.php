<?php

namespace App\Livewire\Finance;

use App\Models\Member;
use App\Models\DiakoniaRequest;
use App\Models\RefDiakoniaType;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DiakoniaManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;
    public $editId = null;

    // Form Properties
    public $member_id, $ref_diakonia_type_id, $nominal = 0, $tanggal_pemberian, $alasan_bantuan;
    public $ref_account_id;

    // Search Helper
    public $searchMember = '', $selectedMemberName = '', $foundMembers = [];

    protected $rules = [
        'member_id' => 'required',
        'ref_diakonia_type_id' => 'required',
        'nominal' => 'required',
        'tanggal_pemberian' => 'required|date',
    ];

    public function mount()
    {
        $this->tanggal_pemberian = date('Y-m-d');
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first();
        $this->ref_account_id = $acc?->id;
    }

    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get()->toArray() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; $this->foundMembers = [];
    }

    public function create()
    {
        $this->reset(['editId', 'member_id', 'selectedMemberName', 'alasan_bantuan', 'nominal']);
        $this->nominal = 0;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();
        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal);

        DB::transaction(function() use ($cleanNominal) {
            $fiscalYear = FiscalYear::active();
            
            // 1. Buat Request Diakonia
            $request = DiakoniaRequest::updateOrCreate(['id' => $this->editId], [
                'uuid' => $this->editId ? DiakoniaRequest::find($this->editId)->uuid : (string) Str::uuid(),
                'member_id' => $this->member_id,
                'ref_diakonia_type_id' => $this->ref_diakonia_type_id,
                'fiscal_year_id' => $fiscalYear->id,
                'nominal' => $cleanNominal,
                'tanggal_pemberian' => $this->tanggal_pemberian,
                'alasan_bantuan' => $this->alasan_bantuan,
                'status' => 'approved', // Langsung approve oleh bendahara/admin
                'approved_by' => Auth::id()
            ]);

            // 2. Buat Jurnal Kas Keluar Otomatis
            $posDiakonia = RefBudgetPost::where('nama', 'like', '%Diakonia%')->first();
            
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $fiscalYear->id,
                'tanggal' => $this->tanggal_pemberian,
                'jenis' => 'keluar',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $posDiakonia?->id,
                'nominal' => $cleanNominal,
                'keterangan' => "Penyaluran Diakonia: " . $this->selectedMemberName,
                'user_id' => Auth::id(),
            ]);

            $request->update(['transaction_id' => $trx->id]);
        });

        $this->dispatch('notify', message: 'Bantuan diakonia berhasil dicatat & masuk jurnal.', type: 'success');
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.finance.diakonia-manager', [
            'requests' => DiakoniaRequest::with(['member', 'type', 'transaction'])
                ->whereHas('member', fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
                ->latest()->paginate(10),
            'types' => RefDiakoniaType::where('is_active', true)->get(),
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}