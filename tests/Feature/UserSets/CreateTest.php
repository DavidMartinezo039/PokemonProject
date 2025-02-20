<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

test('la vista de creación de user set se carga correctamente', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('user-sets.create'))
        ->assertStatus(200);
});

test('un usuario autenticado puede crear un user set', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $data = [
        'name' => 'Mi Primer Set',
        'description' => 'Un set de prueba',
        'image' => UploadedFile::fake()->image('set.jpg'),
    ];

    $this->actingAs($user)
        ->post(route('user-sets.store'), $data)
        ->assertRedirect(route('user-sets.index'));

    $imagePath = Storage::disk('public')->files('user_sets')[0];

    $this->assertDatabaseHas('user_sets', [
        'name' => 'Mi Primer Set',
        'description' => 'Un set de prueba',
        'image' => $imagePath,
    ]);

    Storage::disk('public')->assertExists($imagePath);
});



