<?php

namespace App\Livewire\Families;

use App\Models\Family;
use App\Models\Member;
use App\Models\DuesRegistry;
use App\Models\ActivitySchedule;
use App\Models\Auction;
use App\Models\RefActivityType;
use Livewire\Component;

class Show extends Component
{
    public Family $family;
    public $activeTab = 'anggota'; 

    public function mount(Family $family)
    {
        $this->family = $family->load([
            'refWilayah',
            'members.refHubunganKeluarga',
            'members.refPekerjaan'
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        // 1. TANGGUNGAN (Uang/Barang)
        $familyDues = DuesRegistry::with(['dueType', 'fiscalYear'])
            ->where('assignee_type', Family::class)
            ->where('assignee_id', $this->family->id)
            ->latest()->get();

        $memberIds = $this->family->members->pluck('id');
        $individualDues = DuesRegistry::with(['dueType', 'fiscalYear', 'assignee'])
            ->where('assignee_type', Member::class)
            ->whereIn('assignee_id', $memberIds)
            ->latest()->get();

        // 2. RIWAYAT PKS (Pelaksanaan & Persembahan)
        $pksTypeId = RefActivityType::where('nama', 'like', '%PKS%')->value('id');
        $pksHistory = ActivitySchedule::where('family_id', $this->family->id)
            ->where('ref_activity_type_id', $pksTypeId)
            ->orderBy('tanggal', 'desc')
            ->get();

        // 3. RIWAYAT LELANG (Anggota Keluarga)
        $auctionHistory = Auction::with(['event'])
            ->whereIn('pemenang_member_id', $memberIds)
            ->orWhere('pemenang_nama', 'like', '%' . $this->family->kepala_keluarga . '%')
            ->latest()
            ->get();

        return view('livewire.families.show', [
            'familyDues' => $familyDues,
            'individualDues' => $individualDues,
            'pksHistory' => $pksHistory,
            'auctionHistory' => $auctionHistory
        ]);
    }
}