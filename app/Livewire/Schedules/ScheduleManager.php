<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ScheduleManager extends Component
{
    use WithPagination;

    // State UI
    public $search = '';
    public $filterType = '';
    public $isModalOpen = false;
    public $editId = null;

    // Form Properties
    public $ref_activity_type_id, $ref_wilayah_id, $family_id;
    public $tanggal, $jam_mulai, $tema, $lokasi_manual, $keterangan;

    // Search Helper
    public $searchFamily = '', $foundFamilies = [], $selectedFamilyLabel = '';

    protected function rules()
    {
        return [
            'ref_activity_type_id' => 'required|exists:ref_activity_types,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'tema' => 'required|min:5',
            'lokasi_manual' => 'required_if:family_id,null',
        ];
    }

    protected $messages = [
        'ref_activity_type_id.required' => 'Jenis kegiatan wajib dipilih.',
        'tanggal.required' => 'Tanggal pelaksanaan harus diisi.',
        'jam_mulai.required' => 'Jam mulai harus ditentukan.',
        'tema.required' => 'Tema atau nama kegiatan wajib diisi.',
        'tema.min' => 'Tema kegiatan minimal 5 karakter.',
        'lokasi_manual.required_if' => 'Lokasi harus diisi jika tidak memilih Tuan Rumah.',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->jam_mulai = '09:00';
    }

    public function updatedSearchFamily($value)
    {
        if (strlen($value) > 2) {
            $this->foundFamilies = Family::where('nomor_kk', 'like', "%{$value}%")
                ->orWhereHas('members.churchPeople', fn($q) => $q->where('full_name', 'like', "%{$value}%"))
                ->limit(5)->get();
        } else {
            $this->foundFamilies = [];
        }
    }

    public function selectFamily($id, $name)
    {
        $this->family_id = $id;
        $this->selectedFamilyLabel = "Keluarga $name";
        $this->searchFamily = '';
        $this->foundFamilies = [];
    }

    public function create()
    {
        $this->reset(['editId', 'ref_activity_type_id', 'ref_wilayah_id', 'family_id', 'tema', 'lokasi_manual', 'keterangan', 'selectedFamilyLabel']);
        $this->tanggal = date('Y-m-d');
        $this->jam_mulai = '09:00';
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $schedule = ActivitySchedule::with('family.members.churchPeople')->findOrFail($id);
        $this->editId = $schedule->id;
        $this->ref_activity_type_id = $schedule->ref_activity_type_id;
        $this->ref_wilayah_id = $schedule->ref_wilayah_id;
        $this->family_id = $schedule->family_id;
        $this->tanggal = $schedule->tanggal->format('Y-m-d');
        $this->jam_mulai = Carbon::parse($schedule->jam_mulai)->format('H:i');
        $this->tema = $schedule->tema;
        $this->lokasi_manual = $schedule->lokasi_manual;
        $this->keterangan = $schedule->keterangan;

        if ($schedule->family) {
            $head = $schedule->family->members->sortBy('hubungan_keluarga_id')->first();
            $this->selectedFamilyLabel = "Keluarga " . ($head->churchPeople->full_name ?? '-');
        }

        $this->isModalOpen = true;
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
            'status' => 'rencana'
        ]);

        $this->isModalOpen = false;
        $this->dispatch('notify', message: 'Agenda berhasil disimpan.', type: 'success');
    }

    public function delete($id)
    {
        ActivitySchedule::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Agenda telah dihapus.', type: 'success');
    }

    public function render()
    {
        $query = ActivitySchedule::with(['type', 'wilayah', 'family.members.churchPeople'])
            ->whereHas('type', function ($q) {
                $q->where('nama', 'not like', '%PKS%');
            })
            ->when($this->filterType, fn($q) => $q->where('ref_activity_type_id', $this->filterType))
            ->where(function ($q) {
                $q->where('tema', 'like', "%{$this->search}%")
                  ->orWhere('lokasi_manual', 'like', "%{$this->search}%");
            });

        return view('livewire.schedules.schedule-manager', [
            'schedules' => $query->orderBy('tanggal', 'desc')->paginate(10),
            'types' => RefActivityType::where('nama', 'not like', '%PKS%')->get(),
            'wilayahs' => RefWilayah::orderBy('nama')->get()
        ]);
    }
}