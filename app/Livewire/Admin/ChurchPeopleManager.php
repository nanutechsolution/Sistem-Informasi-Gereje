<?php

namespace App\Livewire\Admin;

use App\Models\ChurchPeople;
use Livewire\Component;
use Livewire\WithPagination;

class ChurchPeopleManager extends Component
{
    use WithPagination;

    public $search = '', $isOpen = false, $editId = null;
    
    // Properti form
    public $nik, $full_name, $gender = 'L', $place_of_birth, $date_of_birth;
    public $phone, $email, $address, $is_baptized = false, $is_sidi = false;

    // Aturan validasi
    protected $rules = [
        'full_name' => 'required|min:3',
        'nik' => 'nullable|numeric|digits:16',
        'gender' => 'required|in:L,P',
        'email' => 'nullable|email',
        'date_of_birth' => 'nullable|date',
    ];

    // Pesan validasi dalam Bahasa Indonesia
    protected $messages = [
        'full_name.required' => 'Nama lengkap wajib diisi.',
        'full_name.min' => 'Nama minimal harus 3 karakter.',
        'nik.numeric' => 'NIK harus berupa angka.',
        'nik.digits' => 'NIK harus berjumlah 16 digit.',
        'email.email' => 'Format alamat email tidak valid.',
        'gender.required' => 'Jenis kelamin wajib dipilih.',
        'date_of_birth.date' => 'Format tanggal lahir tidak valid.',
    ];

    public function create()
    {
        $this->reset(['editId', 'nik', 'full_name', 'place_of_birth', 'date_of_birth', 'phone', 'email', 'address', 'is_baptized', 'is_sidi']);
        $this->resetErrorBag();
        $this->gender = 'L';
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetErrorBag();
        $person = ChurchPeople::findOrFail($id);
        $this->editId = $person->id;
        $this->nik = $person->nik;
        $this->full_name = $person->full_name;
        $this->gender = $person->gender;
        $this->place_of_birth = $person->place_of_birth;
        $this->date_of_birth = $person->date_of_birth?->format('Y-m-d');
        $this->phone = $person->phone;
        $this->email = $person->email;
        $this->address = $person->address;
        $this->is_baptized = $person->is_baptized;
        $this->is_sidi = $person->is_sidi;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        ChurchPeople::updateOrCreate(['id' => $this->editId], [
            'nik' => $this->nik,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_baptized' => $this->is_baptized,
            'is_sidi' => $this->is_sidi,
        ]);

        $this->isOpen = false;
        $this->dispatch('notify', message: 'Data jemaat berhasil disimpan', type: 'success');
    }

    public function delete($id)
    {
        ChurchPeople::find($id)->delete();
        $this->dispatch('notify', message: 'Data jemaat berhasil dihapus', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.church-people-manager', [
            'people' => ChurchPeople::where('full_name', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10)
        ]);
    }
}