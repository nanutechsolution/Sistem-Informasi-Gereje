<?php

namespace App\Livewire\Settings;

use App\Models\RefAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Accounts extends Component
{
    use WithPagination;

    // State UI
    public $search = '';
    public $isOpen = false;
    public $accountId = null;

    // Properti Form
    public $nama, $jenis = 'kas_tunai', $nomor_rekening, $is_active = true;

    protected function rules()
    {
        return [
            'nama' => [
                'required', 
                'min:3', 
                'max:50',
                Rule::unique('ref_accounts', 'nama')->ignore($this->accountId)
            ],
            'jenis' => 'required|in:kas_tunai,bank',
            'nomor_rekening' => 'nullable|max:30',
            'is_active' => 'boolean',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $account = RefAccount::findOrFail($id);
        $this->accountId = $id;
        $this->nama = $account->nama;
        $this->jenis = $account->jenis;
        $this->nomor_rekening = $account->nomor_rekening;
        $this->is_active = $account->is_active;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        RefAccount::updateOrCreate(['id' => $this->accountId], [
            'uuid' => $this->accountId ? RefAccount::find($this->accountId)->uuid : (string) Str::uuid(),
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'nomor_rekening' => $this->jenis === 'bank' ? $this->nomor_rekening : null,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('notify', 
            message: $this->accountId ? 'Akun berhasil diperbarui.' : 'Akun baru berhasil ditambahkan.', 
            type: 'success'
        );

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $account = RefAccount::findOrFail($id);
        $account->update(['is_active' => !$account->is_active]);
        
        $this->dispatch('notify', message: 'Status akun berhasil diubah.', type: 'success');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->accountId = null;
        $this->nama = '';
        $this->jenis = 'kas_tunai';
        $this->nomor_rekening = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.settings.accounts', [
            'accounts' => RefAccount::where('nama', 'like', '%' . $this->search . '%')
                ->orderBy('is_active', 'desc')
                ->orderBy('nama', 'asc')
                ->paginate(10)
        ]);
    }
}