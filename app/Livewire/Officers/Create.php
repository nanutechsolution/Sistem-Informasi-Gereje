<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\Member;
use App\Models\RefPosition;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $member_id, $ref_position_id, $nip_gereja;
    public $ref_budget_post_id, $ref_perumahan_post_id, $ref_pensiun_post_id;
    public $status_kepegawaian = 'organik', $lokasi_tugas = 'pusat';
    public $nomor_sk, $tanggal_mulai, $tanggal_selesai;
    public $gaji_pokok = 0, $tunjangan_perumahan = 0, $tunjangan_lain = 0, $iuran_pensiun = 0;
    public $searchMember = '', $selectedMemberName = '';

    protected $rules = [
        'member_id' => 'required',
        'ref_position_id' => 'required',
        'ref_budget_post_id' => 'required', // Pos Gaji Wajib
        'tanggal_mulai' => 'required|date',
        'gaji_pokok' => 'required|numeric|min:0',
    ];

    public function selectMember($id, $name) {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
    }

    public function save() {
        $this->validate();

        ChurchOfficer::create([
            'uuid' => (string) Str::uuid(),
            'member_id' => $this->member_id,
            'ref_position_id' => $this->ref_position_id,
            'ref_budget_post_id' => $this->ref_budget_post_id,
            'ref_perumahan_post_id' => $this->ref_perumahan_post_id ?: null,
            'ref_pensiun_post_id' => $this->ref_pensiun_post_id ?: null,
            'nip_gereja' => $this->nip_gereja,
            'status_kepegawaian' => $this->status_kepegawaian,
            'lokasi_tugas' => $this->lokasi_tugas,
            'nomor_sk' => $this->nomor_sk,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'gaji_pokok' => (float)$this->gaji_pokok,
            'tunjangan_perumahan' => (float)$this->tunjangan_perumahan,
            'tunjangan_lain' => (float)$this->tunjangan_lain,
            'iuran_pensiun' => (float)$this->iuran_pensiun,
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'Personil berhasil disimpan!', type: 'success');
        return redirect()->route('officers.index');
    }

    public function render() {
        return view('livewire.officers.create', [
            'positions' => RefPosition::orderBy('urutan')->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')->whereNotNull('parent_id')->orderBy('kode')->get(),
            'foundMembers' => strlen($this->searchMember) > 2 ? Member::where('nama', 'like', '%'.$this->searchMember.'%')->limit(5)->get() : []
        ]);
    }
}