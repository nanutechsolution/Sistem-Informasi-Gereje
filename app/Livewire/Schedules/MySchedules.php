<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MySchedules extends Component
{
    use WithPagination;

    // State
    public $activeTab = 'active'; // 'active' atau 'history'
    public $isModalOpen = false;
    public $selectedScheduleId;
    public $nominal_persembahan;
    public $modalTitle;

    public function openCollectionModal($id)
    {
        $schedule = ActivitySchedule::with(['family.members.churchPeople'])->findOrFail($id);
        
        $head = $schedule->family?->members->sortBy('hubungan_keluarga_id')->first();
        $hostName = $head ? ($head->churchPeople->full_name ?? 'Keluarga') : 'Keluarga';

        $this->selectedScheduleId = $id;
        $this->modalTitle = $hostName;
        $this->nominal_persembahan = $schedule->nominal_persembahan > 0 
            ? number_format($schedule->nominal_persembahan, 0, ',', '.') 
            : '';
        
        $this->isModalOpen = true;
    }

    public function saveCollection()
    {
        $this->validate([
            'nominal_persembahan' => 'required'
        ], ['nominal_persembahan.required' => 'Nominal kolekte wajib diisi']);

        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_persembahan);
        
        $schedule = ActivitySchedule::findOrFail($this->selectedScheduleId);
        $schedule->update([
            'nominal_persembahan' => $cleanNominal,
            'status_setoran' => 'pending'
        ]);

        $this->dispatch('notify', message: 'Kolekte berhasil disimpan. Status: Pending.', type: 'success');
        $this->isModalOpen = false;
    }

    /**
     * LOGIKA CEK BENTROK PERIODE (Tuan Rumah)
     * Mengecek apakah keluarga tertentu sudah pernah dijadwalkan dalam rentang 2 bulan.
     */
    public function isFamilyConflict($familyId, $tanggal, $excludeId = null)
    {
        $date = Carbon::parse($tanggal);
        $start = $date->copy()->subMonths(2);
        $end = $date->copy()->addMonths(2);

        return ActivitySchedule::where('family_id', $familyId)
            ->where('id', '!=', $excludeId)
            ->whereBetween('tanggal', [$start, $end])
            ->exists();
    }

    /**
     * LOGIKA CEK BENTROK PERSONIL (Pelayan)
     * Mengecek apakah pelayan tertentu memiliki jadwal lain di hari yang sama.
     */
    public function isServantConflict($memberId, $tanggal, $excludeId = null)
    {
        return ActivitySchedule::where('tanggal', $tanggal)
            ->where('id', '!=', $excludeId)
            ->whereHas('servants', function($q) use ($memberId) {
                $q->where('member_id', $memberId);
            })
            ->exists();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = ActivitySchedule::with([
                'type', 
                'family.wilayah', 
                'family.members.churchPeople',
                'servants.member.churchPeople'
            ]);

        // 1. Filter Berdasarkan Kepemilikan (Role Jemaat/Pelayan)
        $member = null;
        $error_message = null;

        if ($user->church_people_id) {
            $member = Member::where('church_people_id', $user->church_people_id)->first();
        }

        if (!$user->hasRole(['admin', 'super_admin'])) {
            if ($member) {
                $query->where(function($q) use ($member) {
                    $q->whereHas('servants', function($sq) use ($member) {
                        $sq->where('member_id', $member->id);
                    })
                    ->orWhereHas('family.members', function($mq) use ($member) {
                        $mq->where('members.id', $member->id);
                    });
                });
            } else {
                $query->whereRaw('1 = 0');
                $error_message = "Anda belum terhubung sebagai Anggota Jemaat.";
            }
        }

        // 2. LOGIKA PENYARINGAN TAB (STRICT)
        if ($this->activeTab === 'active') {
            $query->where(function($q) {
                $q->where('status', '!=', 'terlaksana')
                  ->orWhere(function($sq) {
                      $sq->where('status', 'terlaksana')
                         ->where('status_setoran', '!=', 'disetor');
                  });
            })->orderBy('tanggal', 'asc');
        } else {
            $query->where('status', 'terlaksana')
                  ->where('status_setoran', 'disetor')
                  ->orderBy('tanggal', 'desc');
        }

        $schedules = $query->paginate(10);

        // 3. AUDIT BENTROK (Untuk indikator visual di UI)
        // Kita tambahkan atribut virtual ke setiap item schedule
        if ($this->activeTab === 'active') {
            $schedules->getCollection()->transform(function($item) use ($member) {
                $item->is_period_clash = $this->isFamilyConflict($item->family_id, $item->tanggal, $item->id);
                $item->is_personal_clash = $member ? $this->isServantConflict($member->id, $item->tanggal, $item->id) : false;
                return $item;
            });
        }

        return view('livewire.schedules.my-schedules', [
            'schedules' => $schedules,
            'myMemberId' => $member ? $member->id : null,
            'error_message' => $error_message
        ]);
    }
}