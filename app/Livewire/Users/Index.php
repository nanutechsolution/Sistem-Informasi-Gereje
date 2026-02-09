<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    // Properti untuk filter (wire:model)
    public $search = '';
    public $roleFilter = '';

    public function delete($id)
    {
        $user = User::find($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }

        $user->delete();
            $this->dispatch('notify', message: 'Data pengguna berhasil dihapus.', type: 'success');

    }

    public function render()
    {
        $query = User::latest();

        // Logika Pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Logika Filter Role
        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        return view('livewire.users.index', [
            'users' => $query->get()
        ]);
    }
}
