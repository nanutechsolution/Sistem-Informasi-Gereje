<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\RefWilayah;
use App\Models\RefPekerjaan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination;

    // Filter & Search
    public $search = '';
    public $wilayahFilter = '';
    public $pekerjaanFilter = '';
    public $genderFilter = '';
    public $statusTab = 'aktif'; // Default tab: aktif

    // Reset halaman saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingWilayahFilter() { $this->resetPage(); }
    public function updatingPekerjaanFilter() { $this->resetPage(); }
    public function updatingGenderFilter() { $this->resetPage(); }
    public function updatingStatusTab() { $this->resetPage(); }

    public function setTab($status) 
    { 
        $this->statusTab = $status; 
    }

    public function delete($uuid)
    {
        // Cek Role (Sesuaikan dengan sistem role Anda)
        $userRole = Auth::user()->role ?? ''; 
        if (!in_array($userRole, ['admin', 'pendeta', 'super_admin'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak memiliki izin.', type: 'error');
            return;
        }

        // Cari berdasarkan UUID
        $member = Member::where('uuid', $uuid)->first();
        
        if ($member) {
            // Kita hanya menghapus status keanggotaan (Member), 
            // Data orang (ChurchPeople) TETAP ADA sebagai master data.
            $member->delete();
            
            $this->dispatch('notify', message: 'Data keanggotaan berhasil dihapus (Data orang tetap ada di Master).', type: 'success');
        }
    }

    public function render()
    {
        $query = Member::query()
            ->with([
                'churchPeople',         // Data Orang (Nama, NIK, Gender)
                'family.wilayah',       // Data Wilayah via Keluarga
                'refHubunganKeluarga',  // Status di Keluarga
                'refPekerjaan'          // Data Pekerjaan
            ])
            ->where('status_keanggotaan', $this->statusTab);

        // 1. Search: Cari Nama/NIK di tabel church_people
        if ($this->search) {
            $query->whereHas('churchPeople', function (Builder $q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Wilayah: Cari via Family
        if ($this->wilayahFilter) {
            $query->whereHas('family', function (Builder $q) {
                $q->where('wilayah_id', $this->wilayahFilter);
            });
        }

        // 3. Filter Gender: Cari via ChurchPeople
        if ($this->genderFilter) {
            $query->whereHas('churchPeople', function (Builder $q) {
                $q->where('gender', $this->genderFilter);
            });
        }

        // 4. Filter Pekerjaan
        if ($this->pekerjaanFilter) {
            $query->where('pekerjaan_id', $this->pekerjaanFilter);
        }

        return view('livewire.members.index', [
            'members' => $query->latest()->paginate(10),
            'refWilayahs' => RefWilayah::orderBy('nama')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}