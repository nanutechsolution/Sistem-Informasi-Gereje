<?php

namespace App\Livewire\Families;

use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // Filter status (aktif, pindah, dll)

    // Method Hapus dengan Proteksi Role
    public function delete($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak berhak menghapus data.', type: 'error');
            return;
        }

        $family = Family::find($id);
        if ($family) {
            $family->delete(); // Soft delete
            $this->dispatch('notify', message: 'Data Keluarga berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $families = Family::query()
            ->with('refWilayah') // Eager load relasi Master Wilayah agar cepat
            ->when($this->search, function ($q) {
                $q->where('kepala_keluarga', 'like', '%' . $this->search . '%')
                    ->orWhere('nomor_kk', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.families.index', [
            'families' => $families
        ]);
    }
}
