<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

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

        $this->call([
            CardSupportDataSeeder::class,   // Seeder para las tablas de apoyo a las cartas
            CardSeeder::class,              // Seeder para las cartas
        ]);
    }
}
