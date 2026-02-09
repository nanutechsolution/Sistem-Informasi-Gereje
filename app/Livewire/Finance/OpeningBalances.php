<?php

namespace App\Livewire\Finance;

use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\OpeningBalance;
use Livewire\Component;

class OpeningBalances extends Component
{
    public $fiscalYearId;
    public $balances = []; // Array [account_id => nominal]

    public function mount()
    {
        $active = FiscalYear::active();
        if ($active) {
            $this->fiscalYearId = $active->id;
            $this->loadData();
        }
    }

    public function updatedFiscalYearId()
    {
        $this->loadData();
    }

    public function loadData()
    {
        if (!$this->fiscalYearId) return;

        $accounts = RefAccount::where('is_active', true)->get();
        
        $existing = OpeningBalance::where('fiscal_year_id', $this->fiscalYearId)
            ->pluck('nominal', 'ref_account_id');

        foreach ($accounts as $acc) {
            // Format angka tanpa desimal agar rapi di input
            $this->balances[$acc->id] = number_format($existing[$acc->id] ?? 0, 0, '', '');
        }
    }

    public function save()
    {
        if (!$this->fiscalYearId) {
            $this->dispatch('notify', message: 'Pilih Tahun Anggaran dulu!', type: 'error');
            return;
        }

        foreach ($this->balances as $accountId => $nominal) {
            $cleanNominal = (float) str_replace('.', '', $nominal);
            
            OpeningBalance::updateOrCreate(
                [
                    'fiscal_year_id' => $this->fiscalYearId,
                    'ref_account_id' => $accountId,
                ],
                [
                    'nominal' => $cleanNominal
                ]
            );
        }

        $this->dispatch('notify', message: 'Saldo Awal berhasil disimpan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.finance.opening-balances', [
            'fiscalYears' => FiscalYear::orderBy('tahun', 'desc')->get(),
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}
