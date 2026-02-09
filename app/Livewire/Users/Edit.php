<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component
{
    public User $user; // Menyimpan model User yang sedang diedit

    public $name;
    public $email;
    public $role;
    public $password; // Opsional, hanya diisi jika ingin ganti password

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'name.min' => 'Nama harus memiliki minimal 3 karakter.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email tidak valid (contoh: nama@email.com).',
        'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        'role.required' => 'Wajib memilih peran pengguna.',
        'role.in' => 'Pilihan peran tidak valid.',
        'password.min' => 'Password baru minimal harus 6 karakter.',
    ];

    // Method ini jalan otomatis saat komponen dimuat
    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function update()
    {
        // 1. Validasi
        $rules = [
            'name' => 'required|min:3',
            // Validasi unik email, KECUALI untuk user ini sendiri
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'role' => 'required|in:admin,pendeta,majelis,bendahara,sekretaris,operator',
        ];

        // Jika password diisi, validasi min 6 karakter
        if (!empty($this->password)) {
            $rules['password'] = 'min:6';
        }

        $this->validate($rules);

        // 2. Siapkan data update
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // Hanya update password jika kolom diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // 3. Eksekusi Update
        $this->user->update($data);

        // 4. Redirect
        $this->dispatch('notify', message: 'Data personil berhasil diperbarui!', type: 'success');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
