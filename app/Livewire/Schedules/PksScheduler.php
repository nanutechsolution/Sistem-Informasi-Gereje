<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\ActivityServant;
use App\Models\ServiceGroup;
use App\Models\Family;
use App\Models\ChurchOfficer;
use App\Models\RefActivityType; // Pastikan ini di-import
use App\Models\RefWilayah;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PksScheduler extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isBatchModalOpen = false;
    public $editId = null; // FIX: Tambahkan properti ini
    
    // Filter State
    public $filterStatus = '';
    public $filterStartDate;
    public $filterEndDate;

    // Form Input
    public $family_id, $tanggal, $jam_mulai = '16:00', $tema;
    public $service_group_id, $selected_pf_id, $selected_pendamping_ids = [];

    // Batch Input
    public $batch_wilayah_id, $batch_start_date, $batch_time = '16:00', $batch_group_id;

    // Search Helper
    public $searchFamily = '', $foundFamilies = [], $selectedFamilyLabel = ''; 

    protected $rules = [
        'family_id' => 'required',
        'tanggal' => 'required|date',
        'jam_mulai' => 'required',
        'selected_pf_id' => 'required',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->filterStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSearchFamily($value)
    {
        if (strlen($value) > 2) {
            $this->foundFamilies = Family::where('kepala_keluarga', 'like', "%{$value}%")
                ->orWhere('nomor_kk', 'like', "%{$value}%")
                ->limit(5)->get()->toArray();
        } else {
            $this->foundFamilies = [];
        }
    }

    public function selectFamily($id, $kepalaKeluarga, $nomorKK)
    {
        $this->family_id = $id;
        $this->selectedFamilyLabel = "Kel. $kepalaKeluarga ($nomorKK)";
        $this->searchFamily = ''; 
        $this->foundFamilies = []; 
    }

    public function updatedServiceGroupId($id)
    {
        $this->selected_pf_id = null;
        $this->selected_pendamping_ids = [];

        if ($id) {
            $group = ServiceGroup::with(['officers.position', 'officers.member'])->find($id);
            
            if ($group && $group->officers->count() > 0) {
                $calonPF = $group->officers->first(fn($o) => $o->pivot->peran_default === 'Pembaca Firman');
                if (!$calonPF) $calonPF = $group->officers->sortBy('position.urutan')->first();
                
                if ($calonPF) $this->selected_pf_id = $calonPF->member_id;

                $this->selected_pendamping_ids = $group->officers
                    ->where('member_id', '!=', $this->selected_pf_id)
                    ->pluck('member_id')
                    ->map(fn($val) => (string)$val)
                    ->toArray();
                    
                $this->dispatch('notify', message: "Tim dimuat.", type: 'success');
            }
        }
    }

    public function create()
    {
        // FIX: Tambahkan reset editId
        $this->reset(['family_id', 'selectedFamilyLabel', 'tema', 'selected_pf_id', 'selected_pendamping_ids', 'service_group_id', 'editId']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function openBatchModal()
    {
        $this->reset(['batch_wilayah_id', 'batch_start_date', 'batch_time', 'batch_group_id']);
        $this->batch_start_date = date('Y-m-d');
        $this->isBatchModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        // Cek Bentrok
        $isConflict = ActivityServant::whereHas('schedule', function($q) {
            $q->where('tanggal', $this->tanggal)->where('jam_mulai', $this->jam_mulai);
        })->where('member_id', $this->selected_pf_id)->exists();

        if ($isConflict) {
            $this->dispatch('notify', message: 'Gagal: Pembaca Firman bentrok jadwal!', type: 'error');
            return;
        }

        $pksType = RefActivityType::firstOrCreate(
            ['nama' => 'PKS (Ibadah Rumah Tangga)'],
            ['uuid' => (string) Str::uuid(), 'warna_label' => '#d97706']
        );

        DB::transaction(function () use ($pksType) {
            $schedule = ActivitySchedule::create([
                'uuid' => (string) Str::uuid(),
                'ref_activity_type_id' => $pksType->id,
                'family_id' => $this->family_id,
                'tanggal' => $this->tanggal,
                'jam_mulai' => $this->jam_mulai,
                'tema' => $this->tema ?? 'Ibadah Syukur Keluarga',
                'status_setoran' => 'pending',
                'status' => 'rencana'
            ]);

            ActivityServant::create(['activity_schedule_id' => $schedule->id, 'member_id' => $this->selected_pf_id, 'peran' => 'Pembaca Firman']);

            foreach ($this->selected_pendamping_ids as $mId) {
                if ($mId != $this->selected_pf_id) {
                    ActivityServant::create(['activity_schedule_id' => $schedule->id, 'member_id' => $mId, 'peran' => 'Pendamping']);
                }
            }
        });

        $this->isModalOpen = false;
        $this->dispatch('notify', message: 'Jadwal PKS berhasil disimpan.', type: 'success');
    }

    public function generateBatch()
    {
        $this->validate([
            'batch_wilayah_id' => 'required',
            'batch_start_date' => 'required|date',
            'batch_group_id' => 'required',
        ]);

        $families = Family::where('wilayah_id', $this->batch_wilayah_id)->orderBy('kepala_keluarga')->get();
        if ($families->isEmpty()) {
            $this->dispatch('notify', message: 'Wilayah ini kosong.', type: 'error');
            return;
        }

        $group = ServiceGroup::with('officers')->find($this->batch_group_id);
        $calonPF = $group->officers->first(fn($o) => $o->pivot->peran_default === 'Pembaca Firman') 
                   ?? $group->officers->sortBy('position.urutan')->first();
        $pendampingIds = $group->officers->where('id', '!=', $calonPF?->id)->pluck('member_id');
        
        $pksType = RefActivityType::firstOrCreate(['nama' => 'PKS (Ibadah Rumah Tangga)'], ['uuid' => Str::uuid(), 'warna_label' => '#d97706']);
        $currDate = Carbon::parse($this->batch_start_date);
        
        foreach($families as $f) {
            $sch = ActivitySchedule::create([
                'uuid' => (string) Str::uuid(),
                'ref_activity_type_id' => $pksType->id,
                'family_id' => $f->id,
                'tanggal' => $currDate->format('Y-m-d'),
                'jam_mulai' => $this->batch_time,
                'tema' => 'Ibadah Rumah Tangga',
                'status_setoran' => 'pending'
            ]);
            
            if($calonPF) ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $calonPF->member_id, 'peran' => 'Pembaca Firman']);
            foreach($pendampingIds as $mid) ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $mid, 'peran' => 'Pendamping']);
            
            $currDate->addDays(7);
        }

        $this->isBatchModalOpen = false;
        $this->dispatch('notify', message: 'Batch jadwal berhasil dibuat.', type: 'success');
    }

    public function render()
    {
        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();
        $pksId = $pksType ? $pksType->id : 0;

        $schedules = ActivitySchedule::with(['family.refWilayah', 'servants.member'])
            ->where('ref_activity_type_id', $pksId)
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterStartDate && $this->filterEndDate, fn($q) => $q->whereBetween('tanggal', [$this->filterStartDate, $this->filterEndDate]))
            ->latest('tanggal')
            ->paginate(9);

        return view('livewire.schedules.pks-scheduler', [
            'schedules' => $schedules,
            'groups' => ServiceGroup::where('is_active', true)->orderBy('nama_kelompok')->get(),
            'staffList' => ChurchOfficer::with(['member', 'position'])->active()->get()->sortBy('position.urutan'),
            'allWilayahs' => RefWilayah::orderBy('nama')->get(),
            'types' => RefActivityType::all() // FIX: Menambahkan variabel $types yang hilang
        ]);
    }
}