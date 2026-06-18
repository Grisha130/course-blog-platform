<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
 
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name'     => 'Super',
            'lastname' => 'Admin',
            'email'    => 'superadmin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $superAdmin->assignRole(Role::findByName('Super Admin', 'api'));
 
        $admin = User::factory()->create([
            'name'     => 'Admin',
            'lastname' => 'User',
            'email'    => 'admin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $admin->assignRole(Role::findByName('Admin', 'api'));
 
        $editor = User::factory()->create([
            'name'     => 'Editor',
            'lastname' => 'User',
            'email'    => 'editor@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $editor->assignRole(Role::findByName('Editor', 'api')); 
 
        User::factory(10)->create()->each(function (User $user) {
        });
    }
}