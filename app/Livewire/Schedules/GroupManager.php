<?php

namespace App\Livewire\Schedules;

use App\Models\ServiceGroup;
use App\Models\ChurchOfficer;
use App\Models\RefWilayah;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GroupManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;
    public $editId = null;

    // Form Properties
    public $nama_kelompok, $ref_wilayah_id;
    public $selectedOfficers = []; // Array ID [1, 5, 8]
    public $defaultRoles = []; // Array [officer_id => 'Pembaca Firman'/'Pendamping']
    
    // Helper Search Modal
    public $searchOfficer = '';

    protected $messages = [
        'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
        'selectedOfficers.required' => 'Pilih minimal satu anggota tim.',
        'selectedOfficers.min' => 'Kelompok harus memiliki anggota.',
    ];

    public function create()
    {
        $this->reset(['editId', 'nama_kelompok', 'ref_wilayah_id', 'selectedOfficers', 'defaultRoles', 'searchOfficer']);
        $this->isModalOpen = true;
    }

    public function edit($uuid)
    {
        $group = ServiceGroup::where('uuid', $uuid)->with('officers')->firstOrFail();
        $this->editId = $group->id;
        $this->nama_kelompok = $group->nama_kelompok;
        $this->ref_wilayah_id = $group->ref_wilayah_id;
        
        $this->selectedOfficers = $group->officers->pluck('id')->map(fn($id) => (string) $id)->toArray();
        
        // Load peran default yang sudah tersimpan
        foreach($group->officers as $off) {
            $this->defaultRoles[$off->id] = $off->pivot->peran_default ?? 'Pendamping';
        }
        
        $this->isModalOpen = true;
    }

    // Toggle Checkbox: Set default role jika baru dipilih
    public function toggleOfficer($id)
    {
        if (in_array($id, $this->selectedOfficers)) {
            if (!isset($this->defaultRoles[$id])) {
                $this->defaultRoles[$id] = 'Pendamping';
            }
        } else {
            unset($this->defaultRoles[$id]);
        }
    }

    public function save()
    {
        $this->validate([
            'nama_kelompok' => 'required|min:3',
            'selectedOfficers' => 'required|array|min:1'
        ]);

        // 1. VALIDASI BENTROK (Anti-Clash)
        // Cek apakah personil yang dipilih sudah ada di kelompok LAIN
        // Join ke church_people untuk ambil nama
        $clashingOfficers = DB::table('service_group_members')
            ->whereIn('service_group_members.church_officer_id', $this->selectedOfficers)
            ->where('service_group_members.service_group_id', '!=', $this->editId) // Abaikan kelompok ini sendiri saat edit
            ->join('church_officers', 'service_group_members.church_officer_id', '=', 'church_officers.id')
            ->join('members', 'church_officers.member_id', '=', 'members.id')
            ->join('church_people', 'members.church_people_id', '=', 'church_people.id') // Fix: Ambil nama dari sini
            ->join('service_groups', 'service_group_members.service_group_id', '=', 'service_groups.id')
            ->select('church_people.full_name as nama_personil', 'service_groups.nama_kelompok as nama_kelompok_lama')
            ->get();

        if ($clashingOfficers->isNotEmpty()) {
            $names = $clashingOfficers->map(fn($o) => "{$o->nama_personil} (di {$o->nama_kelompok_lama})")->unique()->join(', ');
            $this->dispatch('notify', message: "Gagal! Personil berikut sudah ada di kelompok lain: $names. Harap hapus dari kelompok lama terlebih dahulu.", type: 'error');
            return;
        }

        DB::transaction(function () {
            // 2. SIMPAN KELOMPOK
            $group = ServiceGroup::updateOrCreate(['id' => $this->editId], [
                'uuid' => $this->editId ? ServiceGroup::find($this->editId)->uuid : (string) Str::uuid(),
                'nama_kelompok' => $this->nama_kelompok,
                'ref_wilayah_id' => $this->ref_wilayah_id ?: null,
                'is_active' => true,
            ]);

            // 3. SIMPAN ANGGOTA & PERAN DEFAULT
            $syncData = [];
            foreach ($this->selectedOfficers as $officerId) {
                $syncData[$officerId] = [
                    'peran_default' => $this->defaultRoles[$officerId] ?? 'Pendamping'
                ];
            }
            
            $group->officers()->sync($syncData);
        });

        $this->dispatch('notify', message: 'Kelompok pelayanan berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($uuid)
    {
        $group = ServiceGroup::where('uuid', $uuid)->first();
        if ($group) {
            $group->delete();
            $this->dispatch('notify', message: 'Kelompok berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        // List Officer untuk Modal (dengan Filter Search)
        $officersQuery = ChurchOfficer::with(['member.churchPeople', 'position', 'serviceGroups'])
            ->where('is_active', true);

        if ($this->searchOfficer) {
            $officersQuery->whereHas('member.churchPeople', function ($q) {
                $q->where('full_name', 'like', '%' . $this->searchOfficer . '%');
            });
        }

        $allOfficers = $officersQuery->get()->map(function($officer) {
            // Cek grup saat ini (jika ada) untuk indikator visual
            $officer->current_group = $officer->serviceGroups->first(); 
            return $officer;
        })->sortBy('member.churchPeople.full_name');

        // List Group Utama
        $groups = ServiceGroup::with(['officers.member.churchPeople', 'wilayah'])
            ->where('nama_kelompok', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(9);

        return view('livewire.schedules.group-manager', [
            'groups' => $groups,
            'wilayahs' => RefWilayah::orderBy('nama')->get(),
            'allOfficers' => $allOfficers
        ]);
    }
}