<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\ActivityServant;
use App\Models\Member;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ServantManager extends Component
{
    public ActivitySchedule $schedule;
    
    // Properti Form Tim
    public $member_id, $peran = '';
    
    // Properti Kolekte (Khusus PKS)
    public $nominal_persembahan;
    
    // Search Helper
    public $searchMember = '', $foundMembers = [], $selectedMemberName = '';

    protected $messages = [
        'member_id.required' => 'Silakan cari dan pilih jemaat terlebih dahulu.',
        'peran.required' => 'Peran pelayanan wajib diisi (misal: Liturgos).',
    ];

    public function mount(ActivitySchedule $schedule)
    {
        $this->schedule = $schedule->load(['servants.member', 'type', 'wilayah', 'family']);
        // Format nominal saat load agar ada pemisah ribuan
        $this->nominal_persembahan = number_format($this->schedule->nominal_persembahan, 0, ',', '.');
    }

    // --- LOGIKA PENCARIAN JEMAAT ---
    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get()->toArray() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
        $this->foundMembers = [];
    }

    // --- MANAJEMEN PELAYAN ---
    public function addServant()
    {
        $this->validate(['member_id' => 'required', 'peran' => 'required']);

        // Cek duplikasi: Orang yang sama dengan peran yang sama di jadwal yang sama
        $exists = ActivityServant::where('activity_schedule_id', $this->schedule->id)
            ->where('member_id', $this->member_id)
            ->where('peran', $this->peran)
            ->exists();

        if ($exists) {
            $this->dispatch('notify', message: 'Jemaat ini sudah terdaftar dengan peran tersebut.', type: 'error');
            return;
        }

        ActivityServant::create([
            'activity_schedule_id' => $this->schedule->id,
            'member_id' => $this->member_id,
            'peran' => $this->peran,
        ]);

        $this->reset(['member_id', 'peran', 'selectedMemberName']);
        $this->schedule->refresh();
        $this->dispatch('notify', message: 'Pelayan berhasil ditambahkan.', type: 'success');
    }

    public function removeServant($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pendeta', 'sekretaris'])) {
            $this->dispatch('notify', message: 'Anda tidak memiliki izin menghapus.', type: 'error');
            return;
        }

        ActivityServant::findOrFail($id)->delete();
        $this->schedule->refresh();
        $this->dispatch('notify', message: 'Pelayan dihapus dari jadwal.', type: 'warning');
    }

    // --- MANAJEMEN KOLEKTE (PKS) ---
    public function saveCollection()
    {
        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_persembahan);
        
        $this->schedule->update([
            'nominal_persembahan' => $cleanNominal,
            'status_setoran' => 'pending' // Reset ke pending agar diverifikasi ulang oleh Bendahara
        ]);

        $this->dispatch('notify', message: 'Data persembahan berhasil dicatat.', type: 'success');
    }

    public function render()
    {
        return view('livewire.schedules.servant-manager');
    }
}