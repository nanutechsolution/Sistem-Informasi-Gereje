<?php

namespace App\Livewire\Clerical;

use App\Models\Member;
use App\Models\SacramentRecord;
use App\Models\RefSacramentType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class SacramentManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;
    
    // Form State
    public $member_id, $ref_sacrament_type_id, $nomor_surat, $tanggal_pelaksanaan, $tempat_pelaksanaan, $pelayan_firman, $catatan;
    public $is_marriage = false;
    public $partner_member_id, $partner_external_name;
    
    // Search Helpers
    public $searchMember = '', $selectedMemberName = '', $foundMembers = [];
    public $searchPartner = '', $selectedPartnerName = '', $foundPartners = [];

    protected function rules() {
        return [
            'member_id' => 'required|exists:members,id',
            'ref_sacrament_type_id' => 'required|exists:ref_sacrament_types,id',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string',
            'pelayan_firman' => 'required|string',
            'partner_member_id' => 'nullable|exists:members,id',
            'partner_external_name' => 'nullable|string',
        ];
    }

    protected $messages = [
        'member_id.required' => 'Wajib memilih jemaat.',
        'ref_sacrament_type_id.required' => 'Jenis sakramen wajib dipilih.',
        'nomor_surat.required' => 'Nomor surat/akta wajib diisi.',
        'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
        'tempat_pelaksanaan.required' => 'Tempat pelaksanaan wajib diisi.',
        'pelayan_firman.required' => 'Nama pendeta/pelayan wajib diisi.',
    ];

    public function mount() {
        $this->tanggal_pelaksanaan = date('Y-m-d');
        $this->tempat_pelaksanaan = 'GKS Jemaat Reda Pada';
    }

    // --- SEARCH JEMAAT UTAMA ---
    public function updatedSearchMember($value) {
        if (strlen($value) < 3) return $this->foundMembers = [];
        $this->foundMembers = Member::whereHas('churchPeople', function(Builder $q) use ($value) {
            $q->where('full_name', 'like', "%{$value}%")->orWhere('nik', 'like', "%{$value}%");
        })->with('churchPeople')->limit(5)->get();
    }

    public function selectMember($id, $name) {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; $this->foundMembers = [];
        $this->generateNomorSurat();
    }

    // --- SEARCH PASANGAN (KHUSUS NIKAH) ---
    public function updatedSearchPartner($value) {
        if (strlen($value) < 3) return $this->foundPartners = [];
        $this->foundPartners = Member::whereHas('churchPeople', function(Builder $q) use ($value) {
            $q->where('full_name', 'like', "%{$value}%");
        })->where('id', '!=', $this->member_id)->with('churchPeople')->limit(5)->get();
    }

    public function selectPartner($id, $name) {
        $this->partner_member_id = $id;
        $this->selectedPartnerName = $name;
        $this->partner_external_name = null;
        $this->searchPartner = ''; $this->foundPartners = [];
    }

    public function updatedRefSacramentTypeId($id) {
        $type = RefSacramentType::find($id);
        $this->is_marriage = ($type && ($type->kode === 'NKH' || str_contains($type->nama, 'Nikah')));
        $this->generateNomorSurat();
    }

    public function generateNomorSurat() {
        if ($this->ref_sacrament_type_id && $this->member_id) {
            $type = RefSacramentType::find($this->ref_sacrament_type_id);
            $count = SacramentRecord::where('ref_sacrament_type_id', $this->ref_sacrament_type_id)->count() + 1;
            $this->nomor_surat = "GKS-RP/" . ($type->kode ?? 'SKR') . "/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
    }

    public function save() {
        $this->validate();

        if ($this->is_marriage && !$this->partner_member_id && !$this->partner_external_name) {
            $this->addError('searchPartner', 'Nama pasangan wajib diisi.');
            return;
        }

        SacramentRecord::create([
            'uuid' => (string) Str::uuid(),
            'member_id' => $this->member_id,
            'ref_sacrament_type_id' => $this->ref_sacrament_type_id,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan,
            'tempat_pelaksanaan' => $this->tempat_pelaksanaan,
            'pelayan_firman' => $this->pelayan_firman,
            'partner_member_id' => $this->partner_member_id,
            'partner_external_name' => $this->partner_external_name,
            'catatan' => $this->catatan,
        ]);

        $this->dispatch('notify', message: 'Arsip sakramen berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
        $this->reset(['member_id', 'selectedMemberName', 'partner_member_id', 'selectedPartnerName', 'partner_external_name', 'nomor_surat', 'catatan']);
    }

    public function render() {
        return view('livewire.clerical.sacrament-manager', [
            'records' => SacramentRecord::with(['member.churchPeople', 'type', 'partner.churchPeople'])
                ->where(function($q) {
                    $q->whereHas('member.churchPeople', fn($query) => $query->where('full_name', 'like', "%{$this->search}%"))
                      ->orWhere('nomor_surat', 'like', "%{$this->search}%");
                })
                ->latest()->paginate(10),
            'types' => RefSacramentType::all()
        ]);
    }
}