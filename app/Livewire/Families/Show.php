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
        // Eager load relasi dengan struktur data terbaru (churchPeople)
        $this->family = $family->load([
            'wilayah',
            'members.churchPeople',
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
        // Ambil semua ID anggota yang ada di dalam KK ini
        $memberIds = $this->family->members->pluck('id')->toArray();

        // 1. TANGGUNGAN (Kewajiban atas nama KK dan Anggota Jiwa)
        $familyDues = DuesRegistry::with(['dueType', 'fiscalYear'])
            ->where('assignee_type', Family::class)
            ->where('assignee_id', $this->family->id)
            ->latest()
            ->get();

        $individualDues = DuesRegistry::with(['dueType', 'fiscalYear', 'assignee.churchPeople'])
            ->where('assignee_type', Member::class)
            ->whereIn('assignee_id', $memberIds)
            ->latest()
            ->get();

        // 2. RIWAYAT PKS (Berdasarkan Family ID)
        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();
        $pksHistory = ActivitySchedule::with(['servants.member.churchPeople'])
            ->where('family_id', $this->family->id)
            ->where('ref_activity_type_id', $pksType?->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // 3. RIWAYAT LELANG (DIFILTER KETAT: Hanya anggota KK ini)
        $auctionHistory = Auction::with(['event'])
            ->whereIn('pemenang_member_id', $memberIds) // Filter utama berdasarkan ID member
            ->orWhere(function($q) {
                // Tambahan fallback jika nama diketik manual tapi harus persis nama Kepala Keluarga ini
                $q->where('pemenang_nama', $this->family->kepala_keluarga)
                  ->whereNull('pemenang_member_id'); // Pastikan yang tanpa ID saja yang dicari manual
            })
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