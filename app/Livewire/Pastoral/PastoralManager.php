<?php

namespace App\Livewire\Pastoral;

use App\Models\PastoralVisit;
use App\Models\Member;
use App\Models\ChurchOfficer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PastoralManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;

    // Form Properties
    public $member_id, $church_officer_id, $tanggal_kunjungan, $kategori = 'rutin';
    public $pokok_doa, $catatan_kunjungan, $perlu_tindak_lanjut = false;


    // Search Helpers
    public $searchMember = '', $selectedMemberName = '';
    public $foundMembers = [];

    public $searchOfficer = '', $selectedOfficerName = '';
    public $foundOfficers = [];

    protected $rules = [
        'member_id' => 'required',
        'church_officer_id' => 'required',
        'tanggal_kunjungan' => 'required|date',
        'kategori' => 'required',
        'pokok_doa' => 'required|min:10',
    ];

    protected $messages = [
        'member_id.required' => 'Pilih jemaat yang dikunjungi.',
        'church_officer_id.required' => 'Pilih siapa yang mengunjungi.',
        'pokok_doa.required' => 'Isi pokok doa minimal 10 karakter.',
        'pokok_doa.min' => 'Isi pokok doa minimal 10 karakter.',
    ];

    public function mount()
    {
        $this->tanggal_kunjungan = date('Y-m-d');

        // Auto-detect visitor jika user login adalah pengurus
        $officer = ChurchOfficer::where('member_id', Auth::user()->member_id)->first();
        $this->church_officer_id = $officer?->id;
    }

    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get()
            : [];
    }
    public function updatedSearchOfficer($value)
    {
        $this->foundOfficers = strlen($value) > 2
            ? ChurchOfficer::where('nama', 'like', "%{$value}%")->limit(5)->get()
            : [];
    }

    public function selectOfficer($id, $name)
    {
        $this->church_officer_id = $id;
        $this->selectedOfficerName = $name;
        $this->searchOfficer = '';
        $this->foundOfficers = [];
    }
    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
        $this->foundMembers = [];
    }

    public function create()
    {
        $this->reset(['member_id', 'selectedMemberName', 'pokok_doa', 'catatan_kunjungan', 'selectedOfficerName', 'perlu_tindak_lanjut']);
        $this->tanggal_kunjungan = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        PastoralVisit::create([
            'uuid' => (string) Str::uuid(),
            'member_id' => $this->member_id,
            'church_officer_id' => $this->church_officer_id,
            'tanggal_kunjungan' => $this->tanggal_kunjungan,
            'kategori' => $this->kategori,
            'pokok_doa' => $this->pokok_doa,
            'catatan_kunjungan' => $this->catatan_kunjungan,
            'perlu_tindak_lanjut' => $this->perlu_tindak_lanjut,
        ]);

        $this->dispatch('notify', message: 'Kunjungan pastoral berhasil dicatat.', type: 'success');
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.pastoral.pastoral-manager', [
            'visits' => PastoralVisit::with(['member', 'visitor.member'])
                ->whereHas('member', fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
                ->latest('tanggal_kunjungan')
                ->paginate(10),
            'officers' => ChurchOfficer::with('member')->active()->get()
        ]);
    }
}
