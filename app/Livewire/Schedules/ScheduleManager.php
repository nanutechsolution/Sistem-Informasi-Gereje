<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\ActivityServant;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use App\Models\Family;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ScheduleManager extends Component
{
    use WithPagination;

    public $search = '', $isModalOpen = false;
    public $editId = null;

    // Form Properties
    public $ref_activity_type_id, $ref_wilayah_id, $family_id;
    public $tanggal, $jam_mulai, $tema, $lokasi_manual, $keterangan;
    
    // Searchable Family Helper
    public $searchFamily = '', $selectedFamilyName = '';
    public $foundFamilies = [];

    public function mount() {
        $this->tanggal = date('Y-m-d');
        $this->jam_mulai = '18:00';
    }

    public function updatedSearchFamily($value) {
        $this->foundFamilies = strlen($value) > 2 
            ? Family::where('kepala_keluarga', 'like', "%{$value}%")->limit(5)->get()->toArray() 
            : [];
    }

    public function selectFamily($id, $name) {
        $this->family_id = $id;
        $this->selectedFamilyName = $name;
        $this->searchFamily = $name;
        $this->foundFamilies = [];
    }

    public function create() {
        $this->reset(['editId', 'tema', 'family_id', 'searchFamily', 'selectedFamilyName', 'lokasi_manual']);
        $this->isModalOpen = true;
    }

    public function save() {
        $this->validate([
            'ref_activity_type_id' => 'required',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
        ]);

        ActivitySchedule::updateOrCreate(['id' => $this->editId], [
            'uuid' => (string) Str::uuid(),
            'ref_activity_type_id' => $this->ref_activity_type_id,
            'ref_wilayah_id' => $this->ref_wilayah_id ?: null,
            'family_id' => $this->family_id ?: null,
            'tanggal' => $this->tanggal,
            'jam_mulai' => $this->jam_mulai,
            'tema' => $this->tema,
            'lokasi_manual' => $this->lokasi_manual,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('notify', message: 'Jadwal berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function render() {
        return view('livewire.schedules.schedule-manager', [
            'schedules' => ActivitySchedule::with(['type', 'wilayah', 'family'])
                ->where('tema', 'like', "%{$this->search}%")
                ->orderBy('tanggal', 'desc')
                ->paginate(10),
            'types' => RefActivityType::all(),
            'wilayahs' => RefWilayah::all()
        ]);
    }
}