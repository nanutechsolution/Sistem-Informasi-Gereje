<?php


namespace App\Livewire\Settings;

use App\Models\RefAccount;
use Livewire\Component;
use Illuminate\Support\Str;

class Accounts extends Component
{
    public $search = '';
    public $isOpen = false;
    public $accountId, $nama, $nomor_rekening, $jenis = 'kas_tunai';

    protected $rules = [
        'nama' => 'required|min:3',
        'jenis' => 'required|in:kas_tunai,bank',
        'nomor_rekening' => 'nullable',
    ];

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $acc = RefAccount::findOrFail($id);
        $this->accountId = $acc->id;
        $this->nama = $acc->nama;
        $this->nomor_rekening = $acc->nomor_rekening;
        $this->jenis = $acc->jenis;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        RefAccount::updateOrCreate(['id' => $this->accountId], [
            'uuid' => (string) Str::uuid(),
            'nama' => $this->nama,
            'nomor_rekening' => $this->nomor_rekening,
            'jenis' => $this->jenis,
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'Dompet berhasil disimpan!', type: 'success');
        $this->isOpen = false;
        $this->resetInput();
    }

    public function toggleStatus($id)
    {
        $acc = RefAccount::findOrFail($id);
        $acc->update(['is_active' => !$acc->is_active]);
        $this->dispatch('notify', message: 'Status akun diperbarui', type: 'success');
    }

    private function resetInput()
    {
        $this->accountId = null;
        $this->nama = '';
        $this->nomor_rekening = '';
        $this->jenis = 'kas_tunai';
    }

    public function render()
    {
        return view('livewire.settings.accounts', [
            'accounts' => RefAccount::where('nama', 'like', '%' . $this->search . '%')->get()
        ]);
    }
}
