<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\MemberEvent;
use App\Models\RefEventType;
use App\Models\DuesRegistry;
use App\Models\Family;
use Livewire\Component;

class Show extends Component
{
    public Member $member;
    public $activeTab = 'peristiwa';

    // Properti Form Event
    public $event_type_id, $tanggal, $lokasi, $pendeta, $nomor_surat, $keterangan;
    public $isAddingEvent = false;

    public function mount(Member $member)
    {
        // Eager load data profil dan relasi tanggungan
        $this->member = $member->load([
            'family.refWilayah',
            'refHubunganKeluarga',
            'refPekerjaan',
            'events.eventType'
        ]);
        $this->tanggal = date('Y-m-d');
    }

    // --- TAB LOGIC ---
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- EVENT LOGIC (Existing) ---
    public function saveEvent()
    {
        $this->validate([
            'event_type_id' => 'required|exists:ref_event_types,id',
            'tanggal' => 'required|date',
        ]);

        $eventType = \App\Models\RefEventType::find($this->event_type_id);
        $event = $this->member->events()->create([
            'event_type_id' => $this->event_type_id,
            'tanggal' => $this->tanggal,
            'lokasi' => $this->lokasi,
            'pendeta' => $this->pendeta,
            'nomor_surat' => $this->nomor_surat,
            'keterangan' => $this->keterangan,
        ]);

        // 🔥 LOGIC OTOMATIS STATUS MEMBER
        if ($eventType->kode === 'MENINGGAL') {
            $this->member->update([
                'status_keanggotaan' => 'meninggal',
                'tanggal_meninggal' => $this->tanggal,
                'is_active' => 0,
            ]);
        }


        if ($eventType->kode === 'MUTASI_KELUAR') {
            $this->member->update([
                'status_keanggotaan' => 'pindah',
                'is_active' => 0,
            ]);
        }

        $this->dispatch('notify', message: 'Riwayat peristiwa disimpan.', type: 'success');

        $this->reset(['event_type_id', 'tanggal', 'lokasi', 'pendeta', 'nomor_surat', 'keterangan', 'isAddingEvent']);

        $this->member->refresh();
    }


    public function render()
    {
        // Ambil Tanggungan Personal (Atas Nama Sendiri)
        $personalDues = DuesRegistry::with(['dueType', 'fiscalYear', 'logs'])
            ->where('assignee_type', Member::class)
            ->where('assignee_id', $this->member->id)
            ->get();

        // Ambil Tanggungan Keluarga (Atas Nama KK)
        $familyDues = collect();
        if ($this->member->family_id) {
            $familyDues = DuesRegistry::with(['dueType', 'fiscalYear', 'logs'])
                ->where('assignee_type', Family::class)
                ->where('assignee_id', $this->member->family_id)
                ->get();
        }

        return view('livewire.members.show', [
            'eventTypes' => RefEventType::orderBy('nama')->get(),
            'personalDues' => $personalDues,
            'familyDues' => $familyDues
        ]);
    }
}
