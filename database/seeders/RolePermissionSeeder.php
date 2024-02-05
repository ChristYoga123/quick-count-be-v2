<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Dashboard Roles
        $admin = Role::findOrCreate('Admin', 'admin');
        // Create Other Roles
        $surveyor = Role::findOrCreate('Surveyor', 'admin');
        $spotChecker = Role::findOrCreate('Spot Checker', 'admin');
        $enumerator = Role::findOrCreate('Enumerator', 'admin');

        // Create Admin Permissions
        $this->createPermissionRoles('Roles', 'admin');
        // Create Other Permissions

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
    }

    private function createPermissionRoles($resources, $guard = 'admin')
    {
        Permission::findOrCreate($resources . '.' . $guard . '.index', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.show', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.create', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.store', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.edit', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.update', $guard);
        Permission::findOrCreate($resources . '.' . $guard . '.destroy', $guard);
    }
}
