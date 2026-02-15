<?php

namespace App\Livewire\Users;

use App\Models\ChurchPeople;
use App\Models\User;
use App\Models\ChurchPerson;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Create extends Component
{
    // Form Inputs
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';

    // Search Logic
    public $searchMember = '';
    public $selectedMemberId = null;
    public $selectedMemberName = ''; // <--- WAJIB ADA
    public $foundMembers = [];

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|exists:roles,name',
            'selectedMemberId' => 'nullable|exists:church_people,id',
        ];
    }

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

    public function selectMember($id, $fullName, $email = null)
    {
        $this->selectedMemberId = $id;
        $this->selectedMemberName = $fullName; // <--- Set nama untuk ditampilkan
        
        $this->searchMember = '';
        $this->foundMembers = [];

        // Auto-fill form
        $this->name = $fullName;
        if ($email) {
            $this->email = $email;
        }
    }

    public function clearMember()
    {
        $this->selectedMemberId = null;
        $this->selectedMemberName = ''; // <--- Reset nama
        $this->name = '';
        $this->email = '';
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'church_people_id' => $this->selectedMemberId,
            'is_active' => 1,
        ]);

        $user->assignRole($this->role);

        $this->dispatch('notify', message: 'User berhasil dibuat.', type: 'success');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create', [
            'roles' => Role::all()
        ]);
    }
}