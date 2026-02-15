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
    public $editId = null; 
    
    // Filters
    public $search = '';
    public $filterWilayah = '';
    public $filterStartDate;
    public $filterEndDate;

    // Form Manual
    public $family_id, $tanggal, $jam_mulai = '16:00', $tema;
    public $selected_pf_id, $selected_pendamping_ids = [];
    public $service_group_id;

    // Form Batch
    public $batch_wilayah_id, $batch_start_date, $batch_time = '16:00', $batch_group_id, $batch_tema;

    // Search Helpers
    public $searchFamily = '', $selectedFamilyLabel = '', $foundFamilies = []; 

    // Clash Detection
    public $clashWarning = null;

    protected $queryString = ['search', 'filterWilayah', 'filterStartDate', 'filterEndDate'];

    public function mount()
    {
        $this->filterStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = Carbon::now()->addMonths(2)->endOfMonth()->format('Y-m-d');
        $this->tanggal = date('Y-m-d');
    }

    // PENTING: Pastikan method ini PUBLIC
    public function create()
    {
        $this->reset(['editId', 'family_id', 'selectedFamilyLabel', 'tema', 'selected_pf_id', 'selected_pendamping_ids', 'clashWarning', 'searchFamily', 'foundFamilies']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function checkConflicts()
    {
        $this->clashWarning = null;
        if (!$this->family_id || !$this->tanggal) return;

        $date = Carbon::parse($this->tanggal);
        
        // 1. Cek Jeda 2 Bulan (60 Hari) untuk Keluarga
        $familyClash = ActivitySchedule::where('family_id', $this->family_id)
            ->where('id', '!=', $this->editId)
            ->whereBetween('tanggal', [$date->copy()->subDays(60), $date->copy()->addDays(60)])
            ->first();

        if ($familyClash) {
            $this->clashWarning = "BENTROK: Keluarga ini sudah terjadwal pada " . $familyClash->tanggal->format('d M Y') . ". Minimal jeda adalah 2 bulan.";
            return;
        }

        // 2. Cek Bentrok Pelayan Firman
        if ($this->selected_pf_id) {
            $pfClash = ActivitySchedule::where('tanggal', $this->tanggal)
                ->where('id', '!=', $this->editId)
                ->whereHas('servants', fn($q) => $q->where('member_id', $this->selected_pf_id))
                ->first();
            
            if ($pfClash) {
                $this->clashWarning = "BENTROK: Pelayan Firman sudah memiliki tugas lain di tanggal ini.";
            }
        }
    }

    public function updatedTanggal() { $this->checkConflicts(); }
    public function updatedSelectedPfId() { $this->checkConflicts(); }

    public function updatedSearchFamily($value)
    {
        if (strlen($value) < 2) { $this->foundFamilies = []; return; }
        $this->foundFamilies = Family::with(['wilayah', 'members.churchPeople'])
            ->where('nomor_kk', 'like', "%{$value}%")
            ->orWhereHas('members.churchPeople', fn($q) => $q->where('full_name', 'like', "%{$value}%"))
            ->limit(5)->get();
    }

    public function selectFamily($id, $label)
    {
        $this->family_id = $id;
        $this->selectedFamilyLabel = $label;
        $this->foundFamilies = [];
        $this->checkConflicts();
    }

    public function updatedServiceGroupId($id)
    {
        if ($id) {
            $group = ServiceGroup::with('officers')->find($id);
            if ($group) {
                $pf = $group->officers->first(fn($o) => in_array($o->pivot->peran_default, ['Pembaca Firman', 'Ketua'])) ?? $group->officers->first();
                $this->selected_pf_id = $pf?->member_id;
                $this->selected_pendamping_ids = $group->officers->where('member_id', '!=', $this->selected_pf_id)->pluck('member_id')->toArray();
                $this->checkConflicts();
            }
        }
    }

    public function save()
    {
        $this->checkConflicts();
        if ($this->clashWarning) {
            $this->addError('family_id', 'Simpan dibatalkan karena jadwal bentrok.');
            return;
        }

        $this->validate(['family_id' => 'required', 'tanggal' => 'required', 'selected_pf_id' => 'required']);

        $pksType = RefActivityType::firstOrCreate(['nama' => 'Ibadah PKS'], ['uuid' => (string) Str::uuid()]);

        DB::transaction(function () use ($pksType) {
            $schedule = ActivitySchedule::updateOrCreate(['id' => $this->editId], [
                'uuid' => $this->editId ? ActivitySchedule::find($this->editId)->uuid : (string) Str::uuid(),
                'ref_activity_type_id' => $pksType->id,
                'family_id' => $this->family_id,
                'tanggal' => $this->tanggal,
                'jam_mulai' => $this->jam_mulai,
                'tema' => $this->tema ?: 'Ibadah PKS',
                'status' => 'rencana'
            ]);

            $schedule->servants()->delete();
            ActivityServant::create(['activity_schedule_id' => $schedule->id, 'member_id' => $this->selected_pf_id, 'peran' => 'Pembaca Firman']);
            foreach ($this->selected_pendamping_ids as $mId) {
                ActivityServant::create(['activity_schedule_id' => $schedule->id, 'member_id' => $mId, 'peran' => 'Pendamping']);
            }
        });

        $this->dispatch('notify', message: 'Jadwal berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function generateBatch()
    {
        $this->validate([
            'batch_wilayah_id' => 'required',
            'batch_start_date' => 'required',
            'batch_group_id' => 'required'
        ]);

        $families = Family::where('wilayah_id', $this->batch_wilayah_id)->get();
        $pksType = RefActivityType::firstOrCreate(['nama' => 'Ibadah PKS'], ['uuid' => (string) Str::uuid()]);
        $group = ServiceGroup::with('officers')->find($this->batch_group_id);
        
        $pf = $group->officers->first(fn($o) => in_array($o->pivot->peran_default, ['Pembaca Firman', 'Ketua'])) ?? $group->officers->first();
        $pendampingIds = $group->officers->where('id', '!=', $pf->id)->pluck('member_id');

        $currDate = Carbon::parse($this->batch_start_date);
        $count = 0;

        DB::transaction(function() use ($families, $pksType, $currDate, $pf, $pendampingIds, &$count) {
            foreach($families as $f) {
                // Skip jika sudah melayani dalam 60 hari terakhir
                $hasServed = ActivitySchedule::where('family_id', $f->id)
                    ->whereBetween('tanggal', [$currDate->copy()->subDays(60), $currDate->copy()->addDays(60)])
                    ->exists();

                if (!$hasServed) {
                    $sch = ActivitySchedule::create([
                        'uuid' => (string) Str::uuid(), 
                        'ref_activity_type_id' => $pksType->id,
                        'family_id' => $f->id,
                        'tanggal' => $currDate->format('Y-m-d'), 
                        'jam_mulai' => $this->batch_time,
                        'tema' => $this->batch_tema ?: 'Ibadah PKS', 
                        'status' => 'rencana'
                    ]);

                    ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $pf->member_id, 'peran' => 'Pembaca Firman']);
                    foreach($pendampingIds as $mid) {
                        ActivityServant::create(['activity_schedule_id' => $sch->id, 'member_id' => $mid, 'peran' => 'Pendamping']);
                    }
                    $count++;
                }
                $currDate->addDays(7);
            }
        });

        $this->isBatchModalOpen = false;
        $this->dispatch('notify', message: "$count Jadwal massal berhasil dibuat.", type: 'success');
    }

    public function delete($id)
    {
        ActivitySchedule::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Jadwal dihapus.', type: 'success');
    }

    public function render()
    {
        $pksType = RefActivityType::where('nama', 'like', '%Ibadah PKS%')->first();
        
        $query = ActivitySchedule::with(['family.wilayah', 'family.members.churchPeople', 'servants.member.churchPeople'])
            ->where('ref_activity_type_id', $pksType?->id ?? 0)
            ->whereBetween('tanggal', [$this->filterStartDate, $this->filterEndDate])
            ->when($this->filterWilayah, fn($q) => $q->whereHas('family', fn($f) => $f->where('wilayah_id', $this->filterWilayah)))
            ->when($this->search, fn($q) => $q->whereHas('family.members.churchPeople', fn($m) => $m->where('full_name', 'like', "%{$this->search}%")));

        return view('livewire.schedules.pks-scheduler', [
            'schedules' => $query->orderBy('tanggal', 'asc')->paginate(12),
            'wilayahs' => RefWilayah::all(),
            'groups' => ServiceGroup::where('is_active', true)->get(),
            'staffList' => ChurchOfficer::with('member.churchPeople')->where('is_active', true)->get()
        ]);
    }
}