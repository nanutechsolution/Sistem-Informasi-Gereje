<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class MySchedules extends Component
{
    use WithPagination;

    public function render()
    {
        // Syarat: Tabel Users harus punya kolom member_id yang terhubung ke tabel Members
        $user = auth()->user();

        // Cari jadwal di mana user ini terdaftar sebagai pelayan (servant)
        // Kita ambil jadwal dari hari ini ke depan (upcoming)
        $schedules = ActivitySchedule::with(['type', 'family', 'wilayah', 'servants'])
            ->whereHas('servants', function ($query) use ($user) {
                // Mencari berdasarkan member_id yang terhubung ke akun login
                $query->where('member_id', $user->member_id);
            })
            ->where('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->paginate(10);
        return view('livewire.schedules.my-schedules', [
            'schedules' => $schedules
        ]);
    }
}
