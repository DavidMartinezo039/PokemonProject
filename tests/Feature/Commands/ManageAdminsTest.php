<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

it('can create an admin user', function () {
    CreateUser('admin');
    CreateUser();

    $this->artisan('manage:admins')
        ->expectsQuestion('Elige una opción (1-3)', '1')
        ->expectsQuestion('Nombre del usuario', 'Admin User')
        ->expectsQuestion('Email del usuario', 'admin@example.com')
        ->expectsQuestion('Contraseña del usuario', 'secret')
        ->expectsOutput('Usuario administrador creado con éxito.')
        ->expectsQuestion('Elige una opción (1-3)', '3')
        ->assertExitCode(0);

    $user = User::where('email', 'admin@example.com')->first();
    expect($user)->not()->toBeNull()
        ->and(Hash::check('secret', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue();
});

it('can search and select a user to change role', function () {
    $user1 = User::factory()->create(['name' => 'User One']);
    $role = Role::create(['name' => 'user']);
    $user1->assignRole($role);

    $user2 = User::factory()->create(['name' => 'Admin One']);
    $role = Role::create(['name' => 'admin']);
    $user2->assignRole($role);

    $this->artisan('manage:admins')
        ->expectsQuestion('Elige una opción (1-3)', '2')
        ->expectsQuestion('Ingresa el nombre o email del usuario a buscar', 'User One')
        ->expectsQuestion('Elige el número del usuario para cambiar su rol o escribe 0 para volver', '1')
        ->expectsOutput('El usuario User One ahora es admin.')
        ->expectsQuestion('Elige una opción (1-3)', '3')
        ->assertExitCode(0);

    $user1->refresh();
    expect($user1->hasRole('admin'))->toBeTrue();
});
