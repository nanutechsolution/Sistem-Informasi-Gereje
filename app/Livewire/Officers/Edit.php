<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\RefPosition;
use App\Models\RefSalaryComponent;
use App\Models\RefBudgetPost;
use App\Models\OfficerSalaryComponent;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public ChurchOfficer $officer;
    public $member_name;

    public $ref_position_id, $nip_gereja, $nomor_sk;
    public $status_kepegawaian, $lokasi_tugas;
    public $tanggal_mulai, $tanggal_selesai;
    public $components = [];

    protected function rules()
    {
        return [
            'ref_position_id' => 'required|exists:ref_positions,id',
            'nip_gereja' => ['nullable', Rule::unique('church_officers')->ignore($this->officer->id)],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'components.*.ref_salary_component_id' => 'required|exists:ref_salary_components,id',
            'components.*.ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
            'components.*.nominal' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'ref_position_id.required' => 'Jabatan struktural wajib dipilih.',
        'tanggal_mulai.required' => 'Tanggal mulai tugas tidak boleh kosong.',
        'components.*.ref_salary_component_id.required' => 'Pilih komponen gaji.',
        'components.*.ref_budget_post_id.required' => 'Pos anggaran wajib.',
        'components.*.nominal.required' => 'Nominal wajib diisi.',
    ];

    public function mount(ChurchOfficer $officer)
    {
        $this->officer = $officer->load('salaryComponents', 'member.churchPeople');
        $this->member_name = $officer->member->churchPeople->full_name;

        $this->ref_position_id = $officer->ref_position_id;
        $this->nip_gereja = $officer->nip_gereja;
        $this->status_kepegawaian = $officer->status_kepegawaian;
        $this->lokasi_tugas = $officer->lokasi_tugas;
        $this->nomor_sk = $officer->nomor_sk;
        $this->tanggal_mulai = $officer->tanggal_mulai ? $officer->tanggal_mulai->format('Y-m-d') : null;
        $this->tanggal_selesai = $officer->tanggal_selesai ? $officer->tanggal_selesai->format('Y-m-d') : null;

        foreach ($officer->salaryComponents as $comp) {
            $this->components[] = [
                'id' => $comp->id,
                'ref_salary_component_id' => $comp->ref_salary_component_id,
                'ref_budget_post_id' => $comp->ref_budget_post_id,
                'nominal' => $comp->nominal
            ];
        }
    }

    public function addComponent()
    {
        $this->components[] = [
            'id' => null, 
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

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $this->officer->update([
                'ref_position_id' => $this->ref_position_id,
                'nip_gereja' => $this->nip_gereja,
                'status_kepegawaian' => $this->status_kepegawaian,
                'lokasi_tugas' => $this->lokasi_tugas,
                'nomor_sk' => $this->nomor_sk,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai ?: null,
            ]);

            $submittedIds = array_filter(array_column($this->components, 'id'));
            OfficerSalaryComponent::where('church_officer_id', $this->officer->id)
                ->whereNotIn('id', $submittedIds)
                ->delete();

            foreach ($this->components as $comp) {
                $data = [
                    'ref_salary_component_id' => $comp['ref_salary_component_id'],
                    'ref_budget_post_id' => $comp['ref_budget_post_id'],
                    'nominal' => $comp['nominal'],
                    // 'is_active' => true,
                    'tanggal_mulai' => $this->tanggal_mulai,
                ];

                if (isset($comp['id']) && $comp['id']) {
                    OfficerSalaryComponent::where('id', $comp['id'])->update($data);
                } else {
                    $data['uuid'] = Str::uuid();
                    $data['church_officer_id'] = $this->officer->id;
                    $data['is_fixed'] = true;
                    OfficerSalaryComponent::create($data);
                }
            }
        });

        $this->dispatch('notify', message: 'Data pejabat berhasil diperbarui.', type: 'success');
        return redirect()->route('officers.index');
    }

    public function render()
    {
        return view('livewire.officers.edit', [
            'positions' => RefPosition::orderBy('nama')->get(),
            'refSalaryComponents' => RefSalaryComponent::all(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')->whereNotNull('parent_id')->orderBy('kode')->get(),
        ]);
    }
}