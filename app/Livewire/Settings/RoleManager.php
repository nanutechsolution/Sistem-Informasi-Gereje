<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $roleId = null;

    // Form Properties
    public $name;
    public $selectedPermissions = [];

    protected $rules = [
        'name' => 'required|min:3|unique:roles,name',
        'selectedPermissions' => 'array'
    ];

    public function create()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Proteksi: Admin utama tidak boleh diubah izinnya secara sembarangan di UI
        if ($role->name === 'admin') {
            $this->dispatch('notify', message: 'Role Admin memiliki akses penuh permanen.', type: 'error');
            return;
        }

        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
        ]);

        $role = Role::updateOrCreate(['id' => $this->roleId], [
            'name' => strtolower($this->name),
            'guard_name' => 'web'
        ]);

        // Sinkronisasi Permission ke Role
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('notify', message: 'Role & Izin berhasil disimpan.', type: 'success');
        $this->isModalOpen = false;

        // Reset cache Spatie agar perubahan langsung terasa
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    function closeModal()
    {
        $this->isModalOpen = false;
    }
    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'admin') {
            $this->dispatch('notify', message: 'Role Admin tidak boleh dihapus!', type: 'error');
            return;
        }

        if ($role->users()->count() > 0) {
            $this->dispatch('notify', message: 'Gagal! Masih ada user yang menggunakan role ini.', type: 'error');
            return;
        }

        $role->delete();
        $this->dispatch('notify', message: 'Role berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $rawPermissions = \Spatie\Permission\Models\Permission::all();
        $groupedPermissions = [
            'Keuangan & Anggaran' => $rawPermissions->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, ['finance', 'budget', 'transaction'])),
            'Database & Jemaat' => $rawPermissions->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, ['database', 'member', 'family'])),
            'Pelayanan & Jadwal' => $rawPermissions->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, ['schedule', 'pks'])),
            'Laporan & Output' => $rawPermissions->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, ['report'])),
            'Sistem & User' => $rawPermissions->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, ['user', 'setting', 'dashboard'])),
        ];

        return view('livewire.settings.role-manager', [
            'roles' => \Spatie\Permission\Models\Role::with('permissions')->where('name', 'like', '%' . $this->search . '%')->paginate(10),
            'groupedPermissions' => $groupedPermissions // <-- Ini yang harus ada
        ]);
    }
}
