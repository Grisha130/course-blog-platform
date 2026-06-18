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
            'restore-users',

            'manage-categories',
            'manage-tags',
            'manage-contacts',

            'create-courses',
            'edit-courses',
            'block-courses',        
            'unblock-courses',      
            'delete-courses',       
            'restore-courses',      
            'force-delete-courses', 

            'create-blogs',
            'edit-blogs',
            'block-blogs',         
            'unblock-blogs',        
            'delete-blogs',
            'restore-blogs',
            'force-delete-blogs',  
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
            'manage-contacts',
            'view-users',
        ]);

        $editor->givePermissionTo([
            'create-courses',
            'edit-courses',
            'block-courses',
            'unblock-courses',
            'delete-courses',
            'restore-courses',
            'force-delete-courses',

            'create-blogs',
            'edit-blogs',
            'block-blogs',
            'unblock-blogs',
            'delete-blogs',
            'restore-blogs',
            'force-delete-blogs',
        ]);
    }
}
