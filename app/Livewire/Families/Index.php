<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\RefWilayah; // Pastikan import model Wilayah
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $wilayahFilter = ''; // Tambahan filter wilayah

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingWilayahFilter() { $this->resetPage(); }

    public function delete($uuid)
    {
        // Cek Role
        $userRole = Auth::user()->role ?? '';
        if (!in_array($userRole, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak berhak menghapus data.', type: 'error');
            return;
        }

        $family = Family::where('uuid', $uuid)->first();
        if ($family) {
            $family->delete();
            $this->dispatch('notify', message: 'Data Keluarga berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $query = Family::query()
            ->with([
                'wilayah', 
                // Eager load anggota, diurutkan biar Kepala Keluarga (urutan 1) muncul duluan
                'members' => function($q) {
                    $q->join('ref_hubungan_keluargas', 'members.hubungan_keluarga_id', '=', 'ref_hubungan_keluargas.id')
                      ->orderBy('ref_hubungan_keluargas.urutan', 'asc')
                      ->select('members.*'); // Select members agar tidak bentrok id
                },
                'members.churchPeople' // Ambil nama orangnya
            ])
            ->withCount('members'); // Hitung jumlah anggota otomatis

        // Filter Search (Nomor KK atau Nama Anggota Keluarga)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('alamat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('members.churchPeople', function(Builder $b) {
                      $b->where('full_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('wilayah_id', $this->wilayahFilter);
        }

        return view('livewire.families.index', [
            'families' => $query->latest()->paginate(10),
            'refWilayahs' => RefWilayah::orderBy('nama')->get(),
        ]);
    }
}