<?php

namespace App\Livewire\Public;

use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout; // Import Layout
use Livewire\Attributes\Title;  // Import Title
use Carbon\Carbon;

#[Layout('components.layouts.web')] // Definisi Layout di sini
#[Title('Agenda Pelayanan | GKS Jemaat Reda Pada')] // Judul Halaman
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
        // Default mulai hari ini jika tidak ada di URL
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
        $query = ActivitySchedule::with(['type', 'family.refWilayah', 'servants.member', 'wilayah'])
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
                  ->orWhereHas('family', fn($fq) => $fq->where('kepala_keluarga', 'like', "%{$this->search}%"))
                  ->orWhereHas('servants.member', fn($sq) => $sq->where('nama', 'like', "%{$this->search}%"));
            });
        }

        return view('livewire.public.schedules', [ // Sesuaikan nama view jika perlu (biasanya public.schedules.index atau public.schedules)
            'schedules' => $query->paginate(9),
            'types' => RefActivityType::all(),
            'wilayahs' => RefWilayah::orderBy('nama')->get()
        ]);
    }
}