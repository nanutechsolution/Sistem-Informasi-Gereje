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

    // Fix: Reset halaman ke 1 saat mengetik search atau ganti filter
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            $this->dispatch('notify', message: 'Anda tidak bisa menghapus akun sendiri!', type: 'error');
            return;
        }

        $user = User::findOrFail($id);
        $user->delete();
        
        $this->dispatch('notify', message: 'User berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $query = User::with(['roles', 'churchPerson'])
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        if ($this->filterRole) {
            $query->role($this->filterRole);
        }

        return view('livewire.users.index', [
            'users' => $query->latest()->paginate(10),
            'roles' => Role::all()
        ]);
    }
}