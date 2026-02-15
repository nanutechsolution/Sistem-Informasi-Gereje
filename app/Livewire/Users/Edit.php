<?php

namespace App\Livewire\Users;

use App\Models\ChurchPeople;
use App\Models\User;
use App\Models\ChurchPerson; // Import Model Jemaat
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public $name, $email, $role, $password;

    // Search Logic Jemaat
    public $searchMember = '';
    public $selectedMemberId = null;
    public $selectedMemberName = '';
    public $foundMembers = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Ambil role pertama
        $this->role = $user->roles->first()?->name;

        // Load data jemaat jika sudah terhubung
        $this->selectedMemberId = $user->church_people_id;
        if ($user->churchPerson) {
            $this->selectedMemberName = $user->churchPerson->full_name;
        }
    }

    // Logic pencarian sama dengan Create
    public function updatedSearchMember($value)
    {
        if (strlen($value) < 3) {
            $this->foundMembers = [];
            return;
        }

        $this->foundMembers = ChurchPeople::where('full_name', 'like', "%{$value}%")
            ->orWhere('nik', 'like', "%{$value}%")
            ->limit(5)
            ->get();
    }

    public function selectMember($id, $fullName)
    {
        $this->selectedMemberId = $id;
        $this->selectedMemberName = $fullName;
        
        // Jika nama user masih kosong/default, bisa di-update (opsional)
        // $this->name = $fullName; 

        $this->searchMember = '';
        $this->foundMembers = [];
    }

    public function clearMember()
    {
        $this->selectedMemberId = null;
        $this->selectedMemberName = '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6', // Password boleh kosong
            'selectedMemberId' => 'nullable|exists:church_people,id',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'church_people_id' => $this->selectedMemberId,
        ];

        // Update password HANYA jika diisi
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