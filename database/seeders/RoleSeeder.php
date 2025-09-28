<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $perms = [
            'borrow.request',
            'borrow.view',
            'borrow.manage',

            'items.view',
            'items.manage',

            'users.manage',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $rolesWithPermissions = [
            'student' => [
                'borrow.request',
                'borrow.view',
                'items.view',
            ],
            'teacher' => [
                'borrow.request',
                'borrow.view',
                'items.view',
            ],
            'admin' => $perms,
        ];

        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }
    }
}