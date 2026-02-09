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

    /**
     * Pesan Error Bahasa Indonesia (UX Production)
     */
    protected $messages = [
        'member_id.required' => 'Pilih jemaat yang bertugas.',
        'peran.required' => 'Isi peran pelayanan (contoh: Liturgos).',
        'nominal_persembahan.numeric' => 'Nominal harus berupa angka.',
    ];

    public function mount(ActivitySchedule $schedule)
    {
        $this->schedule = $schedule->load(['servants.member', 'type', 'wilayah', 'family']);
        // Load data kolekte jika sudah ada
        $this->nominal_persembahan = number_format($schedule->nominal_persembahan, 0, ',', '.');
    }

    /**
     * Menyimpan data persembahan (Audit Ready)
     */
    public function saveCollection()
    {
        // Bersihkan format titik ribuan
        $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_persembahan);
        
        $this->schedule->update([
            'nominal_persembahan' => $cleanNominal,
            'status_setoran' => 'pending' // Default ke pending untuk verifikasi Bendahara di hari Minggu
        ]);

        $this->dispatch('notify', message: 'Kolekte berhasil diperbarui.', type: 'success');
        $this->schedule->refresh();
    }

    /**
     * Pencarian Jemaat Real-time
     */
    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")
                ->limit(5)
                ->get()
                ->toArray() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->reset(['searchMember', 'foundMembers']);
    }

    /**
     * Tambah Anggota Tim Pelayan
     */
    public function addServant()
    {
        $this->validate(['member_id' => 'required', 'peran' => 'required']);

        // Cek duplikasi peran pada jadwal yang sama
        $exists = ActivityServant::where('activity_schedule_id', $this->schedule->id)
            ->where('member_id', $this->member_id)
            ->where('peran', $this->peran)
            ->exists();

        if ($exists) {
            $this->dispatch('notify', message: 'Jemaat sudah terdaftar dengan peran tersebut.', type: 'warning');
            return;
        }

        ActivityServant::create([
            'activity_schedule_id' => $this->schedule->id,
            'member_id' => $this->member_id,
            'peran' => $this->peran,
        ]);

        $this->reset(['member_id', 'peran', 'selectedMemberName']);
        $this->schedule->refresh();
        $this->dispatch('notify', message: 'Tim pelayan diperbarui.', type: 'success');
    }

    /**
     * Hapus Anggota Tim (Hanya Admin/Pendeta/Sekretaris)
     */
    public function removeServant($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pendeta', 'sekretaris'])) {
            $this->dispatch('notify', message: 'Anda tidak memiliki izin menghapus penugasan.', type: 'error');
            return;
        }

        ActivityServant::findOrFail($id)->delete();
        $this->schedule->refresh();
        $this->dispatch('notify', message: 'Pelayan dihapus.', type: 'warning');
    }

    public function render()
    {
        return view('livewire.schedules.servant-manager');
    }
}