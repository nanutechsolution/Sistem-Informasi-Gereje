<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Member;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Create extends Component
{
    public $name, $email, $password, $password_confirmation;
    public $role; // Slug role yang dipilih
    
    // Opsional: Link ke Data Jemaat
    public $searchMember = '', $selectedMemberId, $selectedMemberName;
    public $foundMembers = [];

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|exists:roles,name',
        ];
    }

    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->selectedMemberId = $id;
        $this->selectedMemberName = $name;
        $this->name = $name; // Auto-fill nama user
        $this->searchMember = ''; $this->foundMembers = [];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'member_id' => $this->selectedMemberId, // Link ke Data Jemaat
        ]);

        // Assign Role Spatie
        $user->assignRole($this->role);

        $this->dispatch('notify', message: 'User baru berhasil dibuat!', type: 'success');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create', [
            'roles' => Role::all()
        ]);
    }
}