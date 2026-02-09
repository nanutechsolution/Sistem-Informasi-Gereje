<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $role = 'operator'; // Default role
    public $password = '';

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'name.min' => 'Nama harus memiliki minimal 3 karakter.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'role.required' => 'Wajib memilih peran pengguna.',
        'password.required' => 'Password wajib diisi untuk pengguna baru.',
        'password.min' => 'Password minimal 6 karakter.',
    ];
    public function save()
    {
        // 1. Validasi
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,pendeta,majelis,bendahara,sekretaris,operator',
            'password' => 'required|min:6',
        ]);

        // 2. Simpan ke Database
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        // 3. Pesan sukses & Redirect
        $this->dispatch('notify', message: 'Personil baru berhasil didaftarkan!', type: 'success');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
