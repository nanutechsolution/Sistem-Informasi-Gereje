<?php

namespace App\Livewire\Clerical;

use App\Models\Member;
use App\Models\SacramentRecord;
use App\Models\RefSacramentType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class SacramentManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;
    
    // Form State Utama
    public $member_id, $ref_sacrament_type_id, $nomor_surat, $tanggal_pelaksanaan, $tempat_pelaksanaan, $pelayan_firman, $catatan;
    
    // Form State Khusus Nikah
    public $is_marriage = false;
    public $partner_member_id, $partner_external_name;
    
    // Search Helpers (Subjek Utama)
    public $searchMember = '', $selectedMemberName = '', $foundMembers = [];
    
    // Search Helpers (Pasangan)
    public $searchPartner = '', $selectedPartnerName = '', $foundPartners = [];

    protected $rules = [
        'member_id' => 'required',
        'ref_sacrament_type_id' => 'required',
        'nomor_surat' => 'required',
        'tanggal_pelaksanaan' => 'required|date',
        'tempat_pelaksanaan' => 'required',
        'pelayan_firman' => 'required',
    ];

    public function mount() {
        $this->tanggal_pelaksanaan = date('Y-m-d');
        $this->tempat_pelaksanaan = 'GKS Jemaat Reda Pada';
    }

    // --- LOGIKA PENCARIAN SUBJEK UTAMA ---
    public function updatedSearchMember($value) {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get() 
            : [];
    }

    public function selectMember($id, $name) {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; $this->foundMembers = [];
        $this->generateNomorSurat();
    }

    // --- LOGIKA PENCARIAN PASANGAN ---
    public function updatedSearchPartner($value) {
        $this->foundPartners = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")
                ->where('id', '!=', $this->member_id) // Jangan pilih diri sendiri
                ->limit(5)->get() 
            : [];
    }

    public function selectPartner($id, $name) {
        $this->partner_member_id = $id;
        $this->selectedPartnerName = $name;
        $this->partner_external_name = null;
        $this->searchPartner = ''; $this->foundPartners = [];
    }

    // --- LOGIKA DINAMIS FORM ---
    public function updatedRefSacramentTypeId($id) {
        $type = RefSacramentType::find($id);
        $this->is_marriage = ($type && $type->kode === 'NKH');
        $this->generateNomorSurat();
    }

    public function generateNomorSurat() {
        if ($this->ref_sacrament_type_id && $this->member_id) {
            $type = RefSacramentType::find($this->ref_sacrament_type_id);
            $count = SacramentRecord::where('ref_sacrament_type_id', $this->ref_sacrament_type_id)->count() + 1;
            $this->nomor_surat = "GKS-RP/" . $type->kode . "/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
    }

    public function save() {
        $this->validate();

        // Validasi tambahan jika nikah
        if ($this->is_marriage && !$this->partner_member_id && !$this->partner_external_name) {
            $this->addError('searchPartner', 'Nama pasangan (jemaat atau luar jemaat) wajib diisi.');
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

        $this->dispatch('notify', message: 'Arsip sakramen/nikah berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
        $this->reset(['member_id', 'selectedMemberName', 'partner_member_id', 'selectedPartnerName', 'partner_external_name']);
    }

    public function render() {
        return view('livewire.clerical.sacrament-manager', [
            'records' => SacramentRecord::with(['member', 'type', 'partner'])
                ->whereHas('member', fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
                ->orWhere('nomor_surat', 'like', "%{$this->search}%")
                ->latest()->paginate(10),
            'types' => RefSacramentType::all()
        ]);
    }
}