<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\ActivityServant;
use App\Models\ServiceGroup;
use App\Models\Family;
use App\Models\ChurchOfficer;
use App\Models\RefActivityType; 
use App\Models\RefWilayah;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PksScheduler extends Component
{
    use WithPagination;

    // State Modals
    public $isModalOpen = false;
    public $isBatchModalOpen = false;
    public $isAuditModalOpen = false; 
    public $editId = null; 
    
    // Filters & Search
    public $search = '';
    public $filterWilayah = '';
    public $filterStatus = ''; 
    public $filterStartDate;
    public $filterEndDate;

    // Form Properties (Manual Input)
    public $family_id, $tanggal, $jam_mulai = '16:00', $tema;
    public $service_group_id, $selected_pf_id, $selected_pendamping_ids = [];

    // Form Batch Generator
    public $batch_wilayah_id, $batch_start_date, $batch_time = '16:00', $batch_group_id;

    // Search Helper
    public $searchFamily = '', $foundFamilies = [], $selectedFamilyLabel = ''; 

    protected $queryString = ['search', 'filterWilayah', 'filterStatus', 'filterStartDate', 'filterEndDate'];

    /**
     * Pesan Error dalam Bahasa Indonesia (UX Friendly)
     */
    protected $messages = [
        'family_id.required' => 'Tuan rumah (Keluarga) wajib dipilih.',
        'tanggal.required' => 'Tanggal ibadah tidak boleh kosong.',
        'tanggal.date' => 'Format tanggal tidak valid.',
        'selected_pf_id.required' => 'Pelayan Firman wajib ditentukan.',
        'batch_wilayah_id.required' => 'Pilih wilayah untuk generate massal.',
        'batch_start_date.required' => 'Tentukan tanggal mulai jadwal.',
        'batch_group_id.required' => 'Pilih kelompok tim pelayan.',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        // Default View: 2 Bulan (Sesuai siklus PKS jemaat)
        $this->filterStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = Carbon::now()->addMonths(2)->endOfMonth()->format('Y-m-d');
    }

    // --- LOGIKA OTOMATIS AMBIL PF DARI KELOMPOK (KETUA TIM) ---
    public function updatedServiceGroupId($id)
    {
        if ($id) {
            $group = ServiceGroup::with('officers')->find($id);
            if ($group) {
                // Ambil officer yang di-set sebagai 'Pembaca Firman' di pivot table
                $pf = $group->officers->first(fn($o) => $o->pivot->peran_default === 'Pembaca Firman');
                
                // Set PF terpilih otomatis
                $this->selected_pf_id = $pf ? $pf->member_id : null;

                // Masukkan sisanya sebagai pendamping otomatis
                $this->selected_pendamping_ids = $group->officers
                    ->where('member_id', '!=', $this->selected_pf_id)
                    ->pluck('member_id')
                    ->map(fn($v) => (string)$v)
                    ->toArray();
                
                if (!$pf) {
                    $this->dispatch('notify', message: 'Kelompok ini belum memiliki Ketua Tim (PF) default.', type: 'error');
                }
            }
        }
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

    public function selectFamily($id, $name, $kk)
    {
        $this->family_id = $id;
        $this->selectedFamilyLabel = "Kel. $name ($kk)";
        $this->searchFamily = ''; 
        $this->foundFamilies = []; 
    }

    public function create()
    {
        $this->reset(['family_id', 'selectedFamilyLabel', 'tema', 'selected_pf_id', 'selected_pendamping_ids', 'service_group_id', 'editId']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'family_id' => 'required',
            'tanggal' => 'required|date',
            'selected_pf_id' => 'required',
        ]);

        // --- VALIDASI BENTROK JADWAL (CLASH DETECTION) ---
        
        // 1. Cek Bentrok Tuan Rumah
        $hostClash = ActivitySchedule::where('family_id', $this->family_id)
            ->where('tanggal', $this->tanggal)
            ->where('jam_mulai', $this->jam_mulai)
            ->where('id', '!=', $this->editId)
            ->exists();

        if ($hostClash) {
            $this->addError('family_id', 'Keluarga ini sudah memiliki jadwal ibadah di waktu yang sama.');
            return;
        }

        // 2. Cek Bentrok Pelayan Firman (PF)
        $pfClash = ActivityServant::where('member_id', $this->selected_pf_id)
            ->whereHas('schedule', function($q) {
                $q->where('tanggal', $this->tanggal)
                  ->where('jam_mulai', $this->jam_mulai)
                  ->where('id', '!=', $this->editId);
            })->exists();

        if ($pfClash) {
            $this->addError('selected_pf_id', 'Pelayan Firman tersebut sudah bertugas di tempat lain pada waktu yang sama.');
            return;
        }

        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();

        DB::transaction(function () use ($pksType) {
            $schedule = ActivitySchedule::updateOrCreate(['id' => $this->editId], [
                'uuid' => $this->editId ? ActivitySchedule::find($this->editId)->uuid : (string) Str::uuid(),
                'ref_activity_type_id' => $pksType->id,
                'family_id' => $this->family_id,
                'tanggal' => $this->tanggal,
                'jam_mulai' => $this->jam_mulai,
                'tema' => $this->tema ?? 'Ibadah Rumah Tangga',
                'status' => $this->editId ? ActivitySchedule::find($this->editId)->status : 'rencana'
            ]);

            // Sync Tim Pelayan
            $schedule->servants()->delete();
            ActivityServant::create([
                'activity_schedule_id' => $schedule->id, 
                'member_id' => $this->selected_pf_id, 
                'peran' => 'Pembaca Firman'
            ]);
            
            foreach ($this->selected_pendamping_ids as $mId) {
                ActivityServant::create([
                    'activity_schedule_id' => $schedule->id, 
                    'member_id' => $mId, 
                    'peran' => 'Pendamping'
                ]);
            }
        });

        $this->dispatch('notify', message: 'Jadwal PKS dan Tim Pelayan berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        ActivitySchedule::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Jadwal pelayanan telah dihapus.', type: 'warning');
    }

    public function generateBatch()
    {
        $this->validate([
            'batch_wilayah_id' => 'required', 
            'batch_start_date' => 'required|date', 
            'batch_group_id' => 'required'
        ]);
        
        $families = Family::where('wilayah_id', $this->batch_wilayah_id)->orderBy('kepala_keluarga')->get();
        if ($families->isEmpty()) {
            $this->dispatch('notify', message: 'Wilayah tersebut tidak memiliki data Keluarga.', type: 'error');
            return;
        }

        $group = ServiceGroup::with('officers')->find($this->batch_group_id);
        $pf = $group->officers->first(fn($o) => $o->pivot->peran_default === 'Pembaca Firman');
        $pendampingIds = $group->officers->where('member_id', '!=', $pf?->member_id)->pluck('member_id');
        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();
        
        $currDate = Carbon::parse($this->batch_start_date);
        
        DB::transaction(function() use ($families, $pksType, $currDate, $pf, $pendampingIds) {
            foreach($families as $f) {
                // Cek bentrok tuan rumah sebelum generate otomatis
                $exists = ActivitySchedule::where('family_id', $f->id)
                    ->where('tanggal', $currDate->format('Y-m-d'))
                    ->where('jam_mulai', $this->batch_time)
                    ->exists();

                if (!$exists) {
                    $sch = ActivitySchedule::create([
                        'uuid' => (string) Str::uuid(), 
                        'ref_activity_type_id' => $pksType->id,
                        'ref_wilayah_id' => $this->batch_wilayah_id, 
                        'family_id' => $f->id,
                        'tanggal' => $currDate->format('Y-m-d'), 
                        'jam_mulai' => $this->batch_time,
                        'tema' => '-', 
                        'status' => 'rencana'
                    ]);

                    if($pf) {
                        ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $pf->member_id, 'peran' => 'Pembaca Firman']);
                    }
                    
                    foreach($pendampingIds as $mid) {
                        ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $mid, 'peran' => 'Pendamping']);
                    }
                }
                
                $currDate->addDays(7); // Bergulir tiap minggu
            }
        });

        $this->isBatchModalOpen = false;
        $this->dispatch('notify', message: 'Berhasil membuat jadwal wilayah (Data bentrok otomatis dilewati).', type: 'success');
    }

    public function render()
    {
        $pksTypeId = RefActivityType::where('nama', 'like', '%PKS%')->value('id');

        // Query Jadwal
        $query = ActivitySchedule::with(['family.refWilayah', 'servants.member'])
            ->where('ref_activity_type_id', $pksTypeId)
            ->when($this->filterWilayah, fn($q) => $q->whereHas('family', fn($wf) => $wf->where('wilayah_id', $this->filterWilayah)))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn($q) => $q->whereHas('family', fn($sf) => $sf->where('kepala_keluarga', 'like', "%{$this->search}%")))
            ->whereBetween('tanggal', [$this->filterStartDate, $this->filterEndDate]);

        // Audit Antrian
        $scheduledIds = ActivitySchedule::where('ref_activity_type_id', $pksTypeId)
            ->whereBetween('tanggal', [$this->filterStartDate, $this->filterEndDate])
            ->pluck('family_id');

        $unscheduledFamilies = Family::with(['refWilayah', 'schedules' => fn($q) => $q->where('ref_activity_type_id', $pksTypeId)->latest('tanggal')])
            ->whereNotIn('id', $scheduledIds)
            ->when($this->filterWilayah, fn($q) => $q->where('wilayah_id', $this->filterWilayah))
            ->orderBy('kepala_keluarga')
            ->get();

        $stats = [
            'total' => (clone $query)->count(),
            'terlaksana' => (clone $query)->where('status', 'terlaksana')->count(),
            'rencana' => (clone $query)->where('status', 'rencana')->count(),
            'belum_terjadwal' => $unscheduledFamilies->count()
        ];

        return view('livewire.schedules.pks-scheduler', [
            'schedules' => $query->orderBy('tanggal', 'asc')->paginate(9),
            'unscheduledList' => $unscheduledFamilies,
            'wilayahs' => RefWilayah::orderBy('nama')->get(),
            'groups' => ServiceGroup::where('is_active', true)->get(),
            'staffList' => ChurchOfficer::with(['member', 'position'])->active()->get(),
            'stats' => $stats
        ]);
    }
}