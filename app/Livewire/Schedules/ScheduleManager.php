<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ScheduleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = ''; // Filter jenis kegiatan
    public $isModalOpen = false;
    public $editId = null;

    // Form Properties
    public $ref_activity_type_id, $ref_wilayah_id, $family_id;
    public $tanggal, $jam_mulai, $tema, $lokasi_manual, $keterangan;

    // Search Helper
    public $searchFamily = '', $foundFamilies = [], $selectedFamilyLabel = '';

    protected $rules = [
        'ref_activity_type_id' => 'required',
        'tanggal' => 'required|date',
        'jam_mulai' => 'required',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->jam_mulai = '09:00';
    }

    // --- LOGIKA PENCARIAN KELUARGA (Opsional untuk jadwal umum) ---
    public function updatedSearchFamily($value)
    {
        if (strlen($value) > 2) {
            $this->foundFamilies = Family::where('kepala_keluarga', 'like', "%{$value}%")
                ->orWhere('nomor_kk', 'like', "%{$value}%")->limit(5)->get()->toArray();
        } else {
            $this->foundFamilies = [];
        }
    }

    public function selectFamily($id, $name, $kk)
    {
        $this->family_id = $id;
        $this->selectedFamilyLabel = "Kel. $name ($kk)";
        $this->searchFamily = '';
        $this->foundFamilies = [];
    }

    // --- CRUD ---
    public function create()
    {
        $this->reset(['editId', 'ref_activity_type_id', 'ref_wilayah_id', 'family_id', 'tema', 'lokasi_manual', 'keterangan']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = true;
    }

    function closeModal()
    {
        $this->reset(['editId', 'ref_activity_type_id', 'ref_wilayah_id', 'family_id', 'tema', 'lokasi_manual', 'keterangan']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = false;
    }
    public function save()
    {
        $this->validate();

        ActivitySchedule::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? ActivitySchedule::find($this->editId)->uuid : (string) Str::uuid(),
            'ref_activity_type_id' => $this->ref_activity_type_id,
            'ref_wilayah_id' => $this->ref_wilayah_id ?: null,
            'family_id' => $this->family_id ?: null,
            'tanggal' => $this->tanggal,
            'jam_mulai' => $this->jam_mulai,
            'tema' => $this->tema,
            'lokasi_manual' => $this->lokasi_manual,
            'keterangan' => $this->keterangan,
        ]);

        $this->isModalOpen = false;
        $this->dispatch('notify', message: 'Agenda berhasil disimpan.', type: 'success');
    }

    public function delete($id)
    {
        ActivitySchedule::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Agenda dihapus.', type: 'success');
    }

    public function edit($id)
    {
        $schedule = ActivitySchedule::with('family')->findOrFail($id);
        $this->editId = $schedule->id;
        $this->ref_activity_type_id = $schedule->ref_activity_type_id;
        $this->ref_wilayah_id = $schedule->ref_wilayah_id;
        $this->family_id = $schedule->family_id;
        $this->tanggal = $schedule->tanggal->format('Y-m-d');
        $this->jam_mulai = $schedule->jam_mulai->format('H:i');
        $this->tema = $schedule->tema;
        $this->lokasi_manual = $schedule->lokasi_manual;
        $this->keterangan = $schedule->keterangan;

        if ($schedule->family) {
            $this->selectedFamilyLabel = "Kel. " . $schedule->family->kepala_keluarga;
        }

        $this->isModalOpen = true;
    }

    public function render()
    {
        // FILTER UTAMA: Hanya ambil kegiatan yang BUKAN PKS
        $schedules = ActivitySchedule::with(['type', 'wilayah', 'family', 'servants.member'])
            ->whereHas('type', function ($q) {
                $q->where('nama', 'not like', '%PKS%'); // Exclude PKS
            })
            ->when($this->filterType, function ($q) {
                $q->where('ref_activity_type_id', $this->filterType);
            })
            ->where(function ($q) {
                $q->where('tema', 'like', "%{$this->search}%")
                    ->orWhere('lokasi_manual', 'like', "%{$this->search}%");
            })
            ->latest('tanggal')
            ->paginate(9);

        // Ambil jenis kegiatan selain PKS untuk dropdown
        $types = RefActivityType::where('nama', 'not like', '%PKS%')->get();

        return view('livewire.schedules.schedule-manager', [
            'schedules' => $schedules,
            'types' => $types,
            'wilayahs' => RefWilayah::orderBy('nama')->get()
        ]);
    }
}
