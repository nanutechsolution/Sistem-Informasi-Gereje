<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Permissions (Daftar Aksi yang boleh dilakukan)
        $permissions = [
            'view_dashboard',
            'manage_users',       // CRUD User
            'manage_database',    // Jemaat & Keluarga
            'manage_finance',     // Input Transaksi
            'approve_transaction',// Verifikasi Setoran
            'manage_budget',      // Atur RAPB
            'view_reports',       // Lihat Laporan
            'manage_schedules',   // Atur Jadwal
            'input_pks',          // Input Kolekte PKS
            'manage_settings',    // Akses Master Data
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // 3. Buat Roles & Assign Permissions

        // A. ADMIN (Super User)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // B. BENDAHARA (Fokus Keuangan)
        $roleBendahara = Role::firstOrCreate(['name' => 'bendahara']);
        $roleBendahara->givePermissionTo([
            'view_dashboard',
            'manage_finance',
            'approve_transaction',
            'manage_budget',
            'view_reports'
        ]);

        // C. SEKRETARIS (Fokus Data & Jadwal)
        $roleSekretaris = Role::firstOrCreate(['name' => 'sekretaris']);
        $roleSekretaris->givePermissionTo([
            'view_dashboard',
            'manage_database',
            'manage_schedules',
            'view_reports'
        ]);

        // D. PENDETA (Supervisi)
        $rolePendeta = Role::firstOrCreate(['name' => 'pendeta']);
        $rolePendeta->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'approve_transaction', // Pendeta juga bisa verifikasi jika perlu
            'manage_schedules'
        ]);

        // E. MAJELIS / OPERATOR WILAYAH
        $roleMajelis = Role::firstOrCreate(['name' => 'majelis']);
        $roleMajelis->givePermissionTo([
            'view_dashboard',
            'input_pks'
        ]);

        // 4. Assign Role ke User (Opsional: Update user yang sudah ada)
        // Contoh: Set user ID 1 jadi admin
        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}