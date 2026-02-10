<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';

    public function delete($id)
    {
        // Cegah hapus diri sendiri
        if ($id == Auth::id()) {
            $this->dispatch('notify', message: 'Anda tidak bisa menghapus akun sendiri!', type: 'error');
            return;
        }

        $user = User::findOrFail($id);
        
        // Hapus personil terkait (jika ada) - Opsional, tergantung kebijakan
        // $user->member()->update(['user_id' => null]);
        
        $user->delete();
        $this->dispatch('notify', message: 'User berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $query = User::with('roles')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        if ($this->filterRole) {
            $query->role($this->filterRole);
        }

        return view('livewire.users.index', [
            'users' => $query->latest()->paginate(10),
            'roles' => Role::all() // Untuk filter dropdown
        ]);
    }
}