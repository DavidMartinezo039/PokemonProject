<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123'),
        ]);

        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'hola@example.com',
            'password' => Hash::make('123'),
        ]);

        Permission::create(['name' => 'view own sets']);
        Permission::create(['name' => 'edit own sets']);
        Permission::create(['name' => 'delete own sets']);
        Permission::create(['name' => 'view all sets']);
        Permission::create(['name' => 'edit all sets']);
        Permission::create(['name' => 'delete all sets']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['view all sets', 'edit all sets', 'delete all sets']);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view own sets', 'edit own sets', 'delete own sets']);

        $user = User::find(1);
        $user->assignRole('admin');

        $user = User::find(2);
        $user->assignRole('user');
    }
}
