<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\RefPosition;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'aktif';
    public $filterPosition = '';

    public function delete($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            $this->dispatch('notify', message: 'Akses ditolak!', type: 'error');
            return;
        }

        ChurchOfficer::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Data personil berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $query = ChurchOfficer::with(['member', 'position', 'salaryComponents']) // Load komponen gaji
            ->when($this->search, function ($q) {
                $q->whereHas('member', fn($mq) => $mq->where('nama', 'like', '%' . $this->search . '%'))
                    ->orWhere('nip_gereja', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterPosition, fn($q) => $q->where('ref_position_id', $this->filterPosition));

        // Logic filter status
        if ($this->filterStatus === 'aktif') {
            $query->active();
        } elseif ($this->filterStatus === 'non-aktif') {
            $query->where('is_active', false);
        }

        return view('livewire.officers.index', [
            'officers' => $query->latest()->paginate(10),
            'positions' => RefPosition::orderBy('urutan')->get()
        ]);
    }
}
