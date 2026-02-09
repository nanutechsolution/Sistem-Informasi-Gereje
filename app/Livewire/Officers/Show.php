<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\OfficerHistory;
use Livewire\Component;

class Show extends Component
{
    public ChurchOfficer $officer;

    public function mount(ChurchOfficer $officer)
    {
        // Load relasi agar performa cepat
        $this->officer = $officer->load(['member', 'position', 'histories.user']);
    }

    public function toggleStatus()
    {
        $this->officer->update(['is_active' => !$this->officer->is_active]);
        $this->dispatch('notify', message: 'Status kepegawaian berhasil diubah.', type: 'success');
    }

    public function render()
    {
        return view('livewire.officers.show');
    }
}