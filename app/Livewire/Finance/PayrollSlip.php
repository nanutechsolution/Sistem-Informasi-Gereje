<?php

namespace App\Livewire\Finance;

use App\Models\Payroll;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class PayrollSlip extends Component
{
    public $payroll;

    public function mount($uuid)
    {
        $this->payroll = Payroll::with(['officer.member', 'officer.position', 'transaction.account'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Keamanan: Hanya admin, bendahara, atau pemilik gaji yang bisa melihat
        if (!in_array(auth()->user()->role, ['admin', 'bendahara']) && auth()->id() !== $this->payroll->officer->member->user_id) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.finance.payroll-slip')->layout('layouts.app');
    }
}