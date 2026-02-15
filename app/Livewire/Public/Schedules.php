<?php

namespace App\Livewire\Public;

use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.web')]
#[Title('Agenda Pelayanan | GKS Jemaat Reda Pada')]
class Schedules extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $type_id = '';

    #[Url(history: true)]
    public $wilayah_id = '';

    #[Url(history: true)]
    public $start_date = '';

    public function mount()
    {
        if (!$this->start_date) {
            $this->start_date = Carbon::today()->format('Y-m-d');
        }
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedTypeId() { $this->resetPage(); }
    public function updatedWilayahId() { $this->resetPage(); }
    public function updatedStartDate() { $this->resetPage(); }

    public function render()
    {
        // Menggunakan join/relasi yang tepat sesuai struktur churchPeople
        $query = ActivitySchedule::with(['type', 'family.members.churchPeople', 'servants.member.churchPeople', 'wilayah'])
            ->where('status', '!=', 'batal')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc');

        if ($this->start_date) {
            $query->where('tanggal', '>=', $this->start_date);
        }

        if ($this->type_id) {
            $query->where('ref_activity_type_id', $this->type_id);
        }

        if ($this->wilayah_id) {
            $query->where(function($q) {
                $q->where('ref_wilayah_id', $this->wilayah_id)
                  ->orWhereHas('family', fn($fq) => $fq->where('wilayah_id', $this->wilayah_id));
            });
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('tema', 'like', "%{$this->search}%")
                  ->orWhereHas('family.members.churchPeople', fn($mq) => $mq->where('full_name', 'like', "%{$this->search}%"))
                  ->orWhereHas('servants.member.churchPeople', fn($sq) => $sq->where('full_name', 'like', "%{$this->search}%"));
            });
        }

        return view('livewire.public.schedules', [
            'schedules' => $query->paginate(9),
            'types' => RefActivityType::all(),
            'wilayahs' => RefWilayah::orderBy('nama')->get()
        ]);
    }
}