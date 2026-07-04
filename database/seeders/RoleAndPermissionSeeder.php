<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-users',
            'manage-user-roles',
            'block-users',
            'delete-users',
            'view-deleted-users',
            'restore-users',
            'force-delete-users',

            'manage-categories',
            'manage-tags',
            
            'manage-courses',
            'manage-blogs',

        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        $superAdmin = Role::findOrCreate('Super Admin', 'api');
        $admin      = Role::findOrCreate('Admin', 'api');
        $editor     = Role::findOrCreate('Editor', 'api');


        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'manage-categories',
            'manage-tags',
        ]);

        $editor->givePermissionTo([
            'manage-courses',
            'manage-blogs',
        ]);
    }
}
