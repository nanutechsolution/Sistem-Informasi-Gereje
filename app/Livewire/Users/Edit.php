<?php

namespace App\Livewire\Users;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public $name, $email, $role, $password;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        // Ambil role pertama (karena sistem kita 1 user = 1 role utama)
        $this->role = $user->roles->first()?->name;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Update password hanya jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        // Sync Role Spatie
        $this->user->syncRoles([$this->role]);

        $this->dispatch('notify', message: 'Data user diperbarui.', type: 'success');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'roles' => Role::all()
        ]);
    }
}