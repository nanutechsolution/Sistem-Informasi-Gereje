<?php

namespace App\Livewire\Schedules;

use App\Models\ServiceGroup;
use App\Models\ChurchOfficer;
use App\Models\RefWilayah;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GroupManager extends Component
{
    public $search = '', $isModalOpen = false;
    public $editId = null;

    // Form
    public $nama_kelompok, $ref_wilayah_id;
    public $selectedOfficers = []; // Array ID [1, 5, 8]
    public $defaultRoles = []; // Array [officer_id => 'Pembaca Firman'/'Pendamping']

    protected $messages = [
        'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
        'selectedOfficers.required' => 'Pilih minimal satu anggota tim.',
        'selectedOfficers.min' => 'Kelompok harus memiliki anggota.',
    ];

    public function create()
    {
        $this->reset(['editId', 'nama_kelompok', 'ref_wilayah_id', 'selectedOfficers', 'defaultRoles']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $group = ServiceGroup::with('officers')->findOrFail($id);
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

    public function save()
    {
        $this->validate([
            'nama_kelompok' => 'required|min:3',
            'selectedOfficers' => 'required|array|min:1'
        ]);

        // 1. VALIDASI BENTROK (Anti-Clash)
        // Cek apakah personil yang dipilih sudah ada di kelompok LAIN
        $clashingOfficers = DB::table('service_group_members')
            ->whereIn('church_officer_id', $this->selectedOfficers)
            ->where('service_group_id', '!=', $this->editId) // Abaikan kelompok ini sendiri saat edit
            ->join('church_officers', 'service_group_members.church_officer_id', '=', 'church_officers.id')
            ->join('members', 'church_officers.member_id', '=', 'members.id')
            ->join('service_groups', 'service_group_members.service_group_id', '=', 'service_groups.id')
            ->select('members.nama as nama_personil', 'service_groups.nama_kelompok as nama_kelompok_lama')
            ->get();

        if ($clashingOfficers->isNotEmpty()) {
            $names = $clashingOfficers->map(fn($o) => "{$o->nama_personil} (sudah di {$o->nama_kelompok_lama})")->join(', ');
            $this->dispatch('notify', message: "Gagal! Bentrok Personil: $names. Hapus mereka dari kelompok lama dulu.", type: 'error');
            return;
        }

        // 2. SIMPAN KELOMPOK
        $group = ServiceGroup::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? ServiceGroup::find($this->editId)->uuid : (string) Str::uuid(),
            'nama_kelompok' => $this->nama_kelompok,
            'ref_wilayah_id' => $this->ref_wilayah_id ?: null,
        ]);

        // 3. SIMPAN ANGGOTA & PERAN DEFAULT
        // Siapkan data pivot dengan peran
        $syncData = [];
        foreach ($this->selectedOfficers as $officerId) {
            $syncData[$officerId] = [
                'peran_default' => $this->defaultRoles[$officerId] ?? 'Pendamping'
            ];
        }
        
        $group->officers()->sync($syncData);

        $this->dispatch('notify', message: 'Kelompok pelayanan berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        $group = ServiceGroup::findOrFail($id);
        $group->delete();
        $this->dispatch('notify', message: 'Kelompok berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        // Ambil semua officer beserta info kelompoknya saat ini (untuk indikator UI)
        $allOfficers = ChurchOfficer::with(['member', 'position', 'serviceGroups'])
            ->active()
            ->get()
            ->map(function($officer) {
                // Tambahkan properti virtual untuk cek status
                $officer->current_group = $officer->serviceGroups->first(); // Ambil kelompok pertama (asumsi 1 orang 1 kelompok)
                return $officer;
            })
            ->sortBy('member.nama');

        return view('livewire.schedules.group-manager', [
            'groups' => ServiceGroup::with(['officers.member', 'wilayah'])
                ->where('nama_kelompok', 'like', "%{$this->search}%")
                ->latest()
                ->get(),
            'wilayahs' => RefWilayah::orderBy('nama')->get(),
            'allOfficers' => $allOfficers
        ]);
    }
}