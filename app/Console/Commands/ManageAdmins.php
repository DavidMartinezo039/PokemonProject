<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManageAdmins extends Command
{
    protected $signature = 'manage:admins';
    protected $description = 'Crear un usuario admin o cambiar el rol de un usuario existente';

    public function handle()
    {
        while (true) {
            $this->info("\nMenú de administración:");
            $this->info("1. Crear un usuario administrador");
            $this->info("2. Cambiar rol de un usuario");
            $this->info("3. Salir");

            $option = $this->ask('Elige una opción (1-3)');

            if ($option == '1') {
                $this->createAdminUser();
            } elseif ($option == '2') {
                $this->toggleUserRole();
            } elseif ($option == '3') {
                $this->info("Saliendo del menú.");
                break;
            } else {
                $this->error("Opción no válida.");
            }
        }
    }

    private function createAdminUser()
    {
        $name = $this->ask('Nombre del usuario');
        $email = $this->ask('Email del usuario');
        $password = $this->secret('Contraseña del usuario');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('admin');

        $this->info("Usuario administrador creado con éxito.");
    }

    private function toggleUserRole()
    {
        $searchTerm = $this->ask('Ingresa el nombre o email del usuario a buscar');

        $users = User::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%")
            ->get();

        if ($users->isEmpty()) {
            $this->error("No se encontraron usuarios con ese criterio.");
            return;
        }

        foreach ($users as $index => $user) {
            $this->info(($index + 1) . ". {$user->name} ({$user->email}) - Rol: " . ($user->hasRole('admin') ? 'Admin' : 'User'));
        }

        $choice = $this->ask('Elige el número del usuario para cambiar su rol o escribe 0 para volver');

        if ($choice == '0') {
            return;
        }

        $index = $choice - 1;

        if (!isset($users[$index])) {
            $this->error("Selección no válida.");
            return;
        }

        $user = $users[$index];

        if ($user->hasRole('admin')) {
            $user->removeRole('admin');
            $user->assignRole('user');
            $this->info("El usuario {$user->name} ahora es 'user'.");
        } else {
            $user->removeRole('user');
            $user->assignRole('admin');
            $this->info("El usuario {$user->name} ahora es 'admin'.");
        }
    }
}
