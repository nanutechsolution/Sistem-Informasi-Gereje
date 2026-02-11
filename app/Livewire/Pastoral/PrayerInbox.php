<?php

namespace App\Livewire\Pastoral;

use App\Models\PrayerRequest;
use Livewire\Component;
use Livewire\WithPagination;

class PrayerInbox extends Component
{
    use WithPagination;

    public $filterStatus = 'baru'; // Default tampilkan yang belum didoakan

    public function markAsPrayed($id)
    {
        $prayer = PrayerRequest::findOrFail($id);
        $prayer->update(['status' => 'didoakan']);
        $this->dispatch('notify', message: 'Status diubah: Sedang Didoakan.', type: 'success');
    }

    public function markAsDone($id)
    {
        $prayer = PrayerRequest::findOrFail($id);
        $prayer->update(['status' => 'selesai']);
        $this->dispatch('notify', message: 'Permohonan doa ditandai selesai.', type: 'success');
    }

    public function delete($id)
    {
        PrayerRequest::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Permohonan dihapus.', type: 'success');
    }

    public function render()
    {
        $requests = PrayerRequest::where('status', $this->filterStatus)
            ->latest()
            ->paginate(9);

        $counts = [
            'baru' => PrayerRequest::where('status', 'baru')->count(),
            'didoakan' => PrayerRequest::where('status', 'didoakan')->count(),
            'konseling' => PrayerRequest::where('butuh_konseling', true)->where('status', '!=', 'selesai')->count(),
        ];

        return view('livewire.pastoral.prayer-inbox', [
            'requests' => $requests,
            'counts' => $counts
        ]);
    }
}