<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\MemberEvent;
use App\Models\RefEventType;
use Livewire\Component;

class Show extends Component
{
    public Member $member;

    // Properti Form Event Baru
    public $event_type_id;
    public $tanggal;
    public $lokasi;
    public $pendeta;
    public $nomor_surat;
    public $keterangan;

    // State untuk Modal Tambah Event (Toggle)
    public $isAddingEvent = false;

    protected $messages = [
        'event_type_id.required' => 'Jenis peristiwa wajib dipilih.',
        'tanggal.required' => 'Tanggal peristiwa wajib diisi.',
    ];

    public function mount(Member $member)
    {
        $this->member = $member;
    }

    public function saveEvent()
    {
        $this->validate([
            'event_type_id' => 'required|exists:ref_event_types,id',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string',
            'pendeta' => 'nullable|string',
            'nomor_surat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan ke tabel member_events
        $this->member->events()->create([
            'event_type_id' => $this->event_type_id,
            'tanggal' => $this->tanggal,
            'lokasi' => $this->lokasi,
            'pendeta' => $this->pendeta,
            'nomor_surat' => $this->nomor_surat,
            'keterangan' => $this->keterangan,
        ]);

        // --- HYBRID UPDATE (Opsional tapi Disarankan) ---
        // Jika event adalah "Baptis Kudus", update flag status_baptis di tabel member
        // Ini menjaga performa pencarian cepat tanpa join berulang
        $eventType = RefEventType::find($this->event_type_id);

        if ($eventType->nama === 'Baptis Kudus') {
            $this->member->update(['status_baptis' => 'Sudah']);
        } elseif ($eventType->nama === 'Sidi (Pengakuan Percaya)') {
            $this->member->update(['status_sidi' => 'Sudah']);
        } elseif ($eventType->nama === 'Pernikahan Gerejawi') {
            $this->member->update(['status_nikah' => 'Sudah']);
        }

        $this->dispatch('notify', message: 'Riwayat baru berhasil ditambahkan!', type: 'success');
        $this->reset(['event_type_id', 'tanggal', 'lokasi', 'pendeta', 'nomor_surat', 'keterangan', 'isAddingEvent']);

        // Refresh data member untuk update tampilan timeline
        $this->member->refresh();
    }

    // Hapus Event
    public function deleteEvent($eventId)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pendeta'])) {
            $this->dispatch('notify', message: 'Hanya Admin/Pendeta yang boleh menghapus sejarah.', type: 'error');
            return;
        }

        MemberEvent::find($eventId)->delete();
        $this->dispatch('notify', message: 'Riwayat berhasil dihapus.', type: 'success');
        $this->member->refresh();
    }

    public function render()
    {
        return view('livewire.members.show', [
            // Ambil jenis event untuk dropdown
            'eventTypes' => RefEventType::orderBy('nama')->get()
        ]);
    }
}
