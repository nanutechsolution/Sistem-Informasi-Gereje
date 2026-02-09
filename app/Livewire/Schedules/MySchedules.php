<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class MySchedules extends Component
{
    use WithPagination;

    // State Modal Kolekte
    public $isModalOpen = false;
    public $selectedScheduleId;
    public $nominal_persembahan;
    public $modalTitle;

    public function openCollectionModal($id)
    {
        $schedule = ActivitySchedule::with('family')->findOrFail($id);
        
        // Hanya izinkan input jika PKS (ID 2 atau cek nama)
        // Disini kita asumsikan PKS perlu input
        
        $this->selectedScheduleId = $id;
        $this->modalTitle = $schedule->family->kepala_keluarga ?? $schedule->tema;
        
        // Tampilkan nominal yang sudah ada (jika mau edit)
        $this->nominal_persembahan = number_format($schedule->nominal_persembahan, 0, ',', '.');
        
        $this->isModalOpen = true;
    }

    public function saveCollection()
    {
        $this->validate(['nominal_persembahan' => 'required']);

        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_persembahan);
        
        $schedule = ActivitySchedule::findOrFail($this->selectedScheduleId);
        $schedule->update([
            'nominal_persembahan' => $cleanNominal,
            'status_setoran' => 'pending' // Status Pending menunggu verifikasi Bendahara
        ]);

        $this->dispatch('notify', message: 'Kolekte berhasil disimpan. Harap setor fisik ke Bendahara.', type: 'success');
        $this->isModalOpen = false;
    }

    public function render()
    {
        $user = auth()->user();
        
        // Query dasar: Jadwal dari 1 bulan lalu s/d masa depan
        $query = ActivitySchedule::with(['type', 'family', 'wilayah', 'servants'])
            ->where('tanggal', '>=', Carbon::today()->subDays(30))
            ->orderBy('tanggal', 'asc'); // Urutkan dari yang terdekat

        // LOGIC FILTER:
        // Jika BUKAN admin, hanya tampilkan jadwal di mana user tersebut terdaftar sebagai pelayan.
        // Jika ADMIN, tampilkan semua jadwal (bypass filter ini).
        if ($user->role !== 'admin') {
            $query->whereHas('servants', function($q) use ($user) {
                $q->where('member_id', $user->member_id);
            });
        }

        return view('livewire.schedules.my-schedules', [
            'schedules' => $query->paginate(10)
        ]);
    }
}