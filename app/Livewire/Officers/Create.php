<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\Member;
use App\Models\RefPosition;
use App\Models\RefSalaryComponent;
use App\Models\RefBudgetPost;
use App\Models\OfficerSalaryComponent;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $member_id, $ref_position_id, $nip_gereja, $nomor_sk;
    public $status_kepegawaian = 'organik', $lokasi_tugas = 'pusat';
    public $tanggal_mulai, $tanggal_selesai;

    public $searchMember = '', $selectedMemberName = '', $searchResults = [];
    public $components = [];

    protected function rules()
    {
        return [
            'member_id' => 'required|exists:members,id',
            'ref_position_id' => 'required|exists:ref_positions,id',
            'nip_gereja' => 'nullable|unique:church_officers,nip_gereja',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'components.*.ref_salary_component_id' => 'required|exists:ref_salary_components,id',
            'components.*.ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
            'components.*.nominal' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'member_id.required' => 'Silakan pilih jemaat terlebih dahulu.',
        'ref_position_id.required' => 'Jabatan struktural wajib dipilih.',
        'tanggal_mulai.required' => 'Tanggal mulai tugas wajib diisi.',
        'components.*.ref_salary_component_id.required' => 'Pilih komponen gaji.',
        'components.*.ref_budget_post_id.required' => 'Pos anggaran wajib dipilih.',
        'components.*.nominal.required' => 'Nominal wajib diisi.',
    ];

    public function mount()
    {
        $this->tanggal_mulai = date('Y-m-d');
        $this->addComponent();
    }

    public function updatedSearchMember($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Member::whereHas('churchPeople', function ($q) use ($value) {
            $q->where('full_name', 'like', "%{$value}%")
              ->orWhere('nik', 'like', "%{$value}%");
        })->limit(5)->get();
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
        $this->searchResults = [];
    }

    public function addComponent()
    {
        $this->components[] = [
            'ref_salary_component_id' => '', 
            'ref_budget_post_id' => '', 
            'nominal' => 0
        ];
    }

    public function removeComponent($index)
    {
        unset($this->components[$index]);
        $this->components = array_values($this->components);
    }

    public function getEstimatedThpProperty()
    {
        $total = 0;
        foreach ($this->components as $c) {
            $nominal = (float) $c['nominal'];
            $ref = RefSalaryComponent::find($c['ref_salary_component_id']);
            if ($ref) {
                ($ref->jenis === 'penerimaan') ? $total += $nominal : $total -= $nominal;
            }
        }
        return $total;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $officer = ChurchOfficer::create([
                'uuid' => Str::uuid(),
                'member_id' => $this->member_id,
                'ref_position_id' => $this->ref_position_id,
                'nip_gereja' => $this->nip_gereja,
                'status_kepegawaian' => $this->status_kepegawaian,
                'lokasi_tugas' => $this->lokasi_tugas,
                'nomor_sk' => $this->nomor_sk,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai ?: null,
            ]);

            foreach ($this->components as $comp) {
                OfficerSalaryComponent::create([
                    'uuid' => Str::uuid(),
                    'church_officer_id' => $officer->id,
                    'ref_salary_component_id' => $comp['ref_salary_component_id'],
                    'ref_budget_post_id' => $comp['ref_budget_post_id'],
                    'nominal' => $comp['nominal'],
                    // 'is_active' => true,
                    'is_fixed' => true,
                    'tanggal_mulai' => $this->tanggal_mulai,
                ]);
            }
        });

        $this->dispatch('notify', message: 'Pejabat berhasil ditambahkan.', type: 'success');
        return redirect()->route('officers.index');
    }

    public function render()
    {
        return view('livewire.officers.create', [
            'positions' => RefPosition::orderBy('nama')->get(),
            'refSalaryComponents' => RefSalaryComponent::all(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')->whereNotNull('parent_id')->orderBy('kode')->get(),
        ]);
    }
}