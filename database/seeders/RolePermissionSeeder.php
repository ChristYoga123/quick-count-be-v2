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
        $admin = Role::findOrCreate('Admin', 'web');
        // Create Other Roles
        $surveyor = Role::findOrCreate('Surveyor', 'web');
        $spotChecker = Role::findOrCreate('Spot Checker', 'web');
        $enumerator = Role::findOrCreate('Enumerator', 'web');

        // Create Admin Permissions
        $this->createPermissionRoles('Peran', 'web');
        // Create Other Permissions
        $this->createPermissionRoles('TPS', 'web');

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
    }

    private function createPermissionRoles($resources, $guard = 'web')
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
