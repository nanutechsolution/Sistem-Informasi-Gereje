<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\RefPosition;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPosition = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($uuid)
    {
        // Cek Role
        if (!in_array(auth()->user()->role ?? '', ['admin', 'bendahara', 'super_admin'])) {
            $this->dispatch('notify', message: 'Akses ditolak!', type: 'error');
            return;
        }

        $officer = ChurchOfficer::where('uuid', $uuid)->first();

        if ($officer) {
            $officer->delete();
            $this->dispatch('notify', message: 'Data pejabat berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $query = ChurchOfficer::query()
            ->with([
                'member.churchPeople', // Load data orang
                'position'             // Load jabatan (ref_positions)
            ]);

        // 1. Search (Nama, NIP, No SK)
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('member.churchPeople', function (Builder $b) {
                    $b->where('full_name', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('nip_gereja', 'like', '%' . $this->search . '%')
                    ->orWhere('nomor_sk', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Jabatan
        if ($this->filterPosition) {
            $query->where('ref_position_id', $this->filterPosition);
        }

        // 3. Filter Status Kepegawaian (Enum)
        if ($this->filterStatus) {
            $query->where('status_kepegawaian', $this->filterStatus);
        }

        return view('livewire.officers.index', [
            'officers' => $query->latest()->paginate(10),
            'positions' => RefPosition::orderBy('nama')->get() // Sesuaikan kolom nama jabatan
        ]);
    }
}
