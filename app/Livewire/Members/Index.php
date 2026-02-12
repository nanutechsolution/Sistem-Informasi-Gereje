<?php
namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\RefWilayah;
use App\Models\RefPekerjaan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    // Filter & Tabs
    public $search = '';
    public $wilayahFilter = '';
    public $pekerjaanFilter = '';
    public $genderFilter = '';
    public $statusTab = 'aktif'; // Default tab

    protected $queryString = [
        'search' => ['except' => ''],
        'statusTab' => ['except' => 'aktif'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function setTab($status) { 
        $this->statusTab = $status; 
        $this->resetPage(); 
    }

    public function delete($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK', type: 'error');
            return;
        }

        $member = Member::find($id);
        if ($member) {
            $member->delete();
            $this->dispatch('notify', message: 'Data berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $query = Member::with(['family.refWilayah', 'refHubunganKeluarga', 'refPekerjaan'])
            ->where('status_keanggotaan', $this->statusTab)
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->wilayahFilter) {
            $query->whereHas('family', fn($q) => $q->where('wilayah_id', $this->wilayahFilter));
        }

        if ($this->pekerjaanFilter) {
            $query->where('pekerjaan_id', $this->pekerjaanFilter);
        }

        if ($this->genderFilter) {
            $query->where('jenis_kelamin', $this->genderFilter);
        }

        return view('livewire.members.index', [
            'members' => $query->paginate(15),
            'refWilayahs' => RefWilayah::orderBy('nama')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}