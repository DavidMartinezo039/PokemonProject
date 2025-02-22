<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\postJson;

it('permite a un usuario autenticado editar su perfil', function () {
    $user = CreateUser();

    $this->actingAs($user, 'sanctum');

    $data = [
        'name' => 'Nuevo nombre',
        'email' => 'nuevoemail@example.com',
        'password' => 'password123',
    ];

    $response = $this->patchJson('/api/profile', $data);

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $user->id,
        'name' => 'Nuevo nombre',
        'email' => 'nuevoemail@example.com',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nuevo nombre',
        'email' => 'nuevoemail@example.com',
    ]);
});

it('permite a un usuario autenticado eliminar su cuenta', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson('/api/profile', [
        'password' => 'password123',
    ]);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);

    $response->assertStatus(200);

    $this->assertNull(User::find($user->id));


    $response = $this->getJson('/api/profile');
    $response->assertStatus(401);
});

it('no permite eliminar la cuenta con una contraseña incorrecta', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson('/api/profile', [
        'password' => 'incorrect_password',
    ]);

    $this->assertDatabaseHas('users', ['id' => $user->id]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('password');
});

it('muestra el perfil del usuario autenticado', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson('/api/profile');

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ]);
});

it('permite a un usuario iniciar sesión con credenciales correctas', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);


    $response = postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ]);


    expect(auth()->check())->toBeTrue();
});

it('no permite iniciar sesión con un dato incorrecta', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Credenciales incorrectas',
        ]);

    expect(auth()->check())->toBeFalse();
});


it('permite registrar un usuario con datos válidos', function () {
    $response = postJson('/api/register', [
        'name' => 'Nuevo Usuario',
        'email' => 'nuevo@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'user' => [
                'name' => 'Nuevo Usuario',
                'email' => 'nuevo@example.com',
            ],
        ])
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            'token'
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'nuevo@example.com',
    ]);
});


it('no permite registrar un usuario sin nombre', function () {
    $response = postJson('/api/register', [
        'email' => 'nuevo@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('name');
});
