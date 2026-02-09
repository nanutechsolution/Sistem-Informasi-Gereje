<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\RefWilayah; // Jika ingin filter wilayah
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $wilayahFilter = ''; // ID Wilayah dari tabel master

    public function delete($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak berhak menghapus data jemaat.', type: 'error');
            return;
        }

        $member = Member::find($id);
        if ($member) {
            $member->delete();
            $this->dispatch('notify', message: 'Data jemaat berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $query = Member::with(['family.refWilayah', 'refHubunganKeluarga', 'refPekerjaan']) // Eager load relasi baru
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        // Filter Wilayah via Relasi Family -> RefWilayah
        if ($this->wilayahFilter) {
            $query->whereHas('family', function ($q) {
                $q->where('wilayah_id', $this->wilayahFilter);
            });
        }

        return view('livewire.members.index', [
            'members' => $query->paginate(15),
            'refWilayahs' => \App\Models\RefWilayah::orderBy('nama')->get() // Data dropdown filter
        ]);
    }
}
