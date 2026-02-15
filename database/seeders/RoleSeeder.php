<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        // ======================
        // 1. PERMISSIONS
        // ======================
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_database',
            'manage_finance',
            'approve_transaction',
            'manage_budget',
            'view_reports',
            'manage_schedules',
            'input_pks',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard
            ]);
        }

        // ======================
        // 2. ROLES
        // ======================

        // SUPER ADMIN (Full Access)
        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => $guard
        ]);
        $superAdmin->syncPermissions(Permission::all());

        // ADMIN
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard
        ]);
        $admin->syncPermissions(Permission::all());

        // BENDAHARA
        $bendahara = Role::firstOrCreate([
            'name' => 'bendahara',
            'guard_name' => $guard
        ]);
        $bendahara->syncPermissions([
            'view_dashboard',
            'manage_finance',
            'approve_transaction',
            'manage_budget',
            'view_reports'
        ]);

        // SEKRETARIS
        $sekretaris = Role::firstOrCreate([
            'name' => 'sekretaris',
            'guard_name' => $guard
        ]);
        $sekretaris->syncPermissions([
            'view_dashboard',
            'manage_database',
            'manage_schedules',
            'view_reports'
        ]);

        // PENDETA
        $pendeta = Role::firstOrCreate([
            'name' => 'pendeta',
            'guard_name' => $guard
        ]);
        $pendeta->syncPermissions([
            'view_dashboard',
            'view_reports',
            'approve_transaction',
            'manage_schedules'
        ]);

        // MAJELIS / OPERATOR
        $majelis = Role::firstOrCreate([
            'name' => 'majelis',
            'guard_name' => $guard
        ]);
        $majelis->syncPermissions([
            'view_dashboard',
            'input_pks'
        ]);
    }
}
