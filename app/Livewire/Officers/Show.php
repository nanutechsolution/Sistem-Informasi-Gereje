<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use Livewire\Component;

class Show extends Component
{
    public ChurchOfficer $officer;

    public function mount(ChurchOfficer $officer)
    {
        // Eager load data dinamis
        $this->officer = $officer->load([
            'member', 
            'position', 
            'salaryComponents.budgetPost', // Load pos anggaran per komponen
            'histories.user'
        ]);
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