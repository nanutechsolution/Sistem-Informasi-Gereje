<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\OfficerPayroll;

class PayrollSlip extends Component
{
    public $payroll;

    /**
     * $uuid -> officer_payroll uuid
     */
    public function mount($uuid)
    {
        // Ambil payroll lengkap dengan relasi
        $this->payroll = OfficerPayroll::with([
            'officer.member.churchPerson',
            'officer.position',
            'items.component',
            'payments.transaction.account'
        ])->where('id', $uuid)
            ->firstOrFail();

        // Keamanan: admin, bendahara, atau pemilik payroll
        $user = auth()->user();
        if (
            !$user->hasAnyRole(['admin', 'super_admin', 'bendahara'])
            && $user->id !== optional($this->payroll->officer->member)->user_id
        ) {
            abort(403);
        }
    }

    /**
     * Hitung total bayar dari item
     */
    public function getTotalPaidProperty()
    {
        return $this->payroll->items->sum('nominal_bayar');
    }

    /**
     * Hitung sisa yang belum dibayar
     */
    public function getRemainingProperty()
    {
        return $this->payroll->items->sum('nominal_snapshot') - $this->totalPaid;
    }

    /**
     * Status virtual: draft / cicil / paid
     */
    public function getStatusLabelProperty()
    {
        $totalNominal = $this->payroll->items->sum('nominal_snapshot');
        $totalPaid    = $this->payroll->items->sum('nominal_bayar');

        if ($totalPaid >= $totalNominal) return 'paid';
        if ($totalPaid > 0) return 'cicil';
        return 'draft';
    }

    public function render()
    {
        return view('livewire.finance.payroll-slip')
            ->layout('layouts.app');
    }
}
