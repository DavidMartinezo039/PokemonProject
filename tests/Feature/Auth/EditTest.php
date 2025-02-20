<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('permite a un usuario autenticado eliminar su cuenta', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->actingAs($user);

    $response = $this->get(route('profile.edit'));
    $response->assertOk();

    $deleteResponse = $this->from(route('profile.edit'))->delete(route('profile.destroy'), [
        'password' => 'password123',
    ]);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);

    $deleteResponse->assertRedirect('/');

    $this->assertGuest();
});

it('no permite eliminar usuario con contraseña incorrecta', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->actingAs($user);

    $deleteResponse = $this->from(route('profile.edit'))->delete(route('profile.destroy'), [
        'password' => 'incorrecta',
    ]);

    $this->assertDatabaseHas('users', ['id' => $user->id]);

    $this->assertAuthenticatedAs($user);

    $deleteResponse->assertRedirect(route('profile.edit'));
    $deleteResponse->assertSessionHasErrors(['password']);
});
