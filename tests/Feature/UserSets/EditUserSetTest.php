<?php

use App\Models\User;
use App\Models\UserSet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use function Pest\Laravel\actingAs;


it('actualiza el nombre del set', function () {
    // Crea un set de prueba
    $userSet = UserSet::factory()->create([
        'name' => 'Set Original',
        'description' => 'Descripción original',
    ]);

    // Define los nuevos datos
    $newName = 'Nuevo Set';

    // Realiza la petición para actualizar el set
    $response = $this->actingAs($userSet->user)
        ->put(route('user-sets.update', $userSet->id), [
            'name' => $newName,
            'description' => 'Descripción actualizada',
        ]);

    // Verifica que el nombre se haya actualizado en la base de datos
    $userSet->refresh();
    expect($userSet->name)->toBe($newName);

    // Verifica que la redirección sea a la lista de sets
    $response->assertRedirect(route('user-sets.index'));
    $response->assertSessionHas('success', __('Set successfully updated'));
});

it('updates a user set successfully with image', function () {
    // Crear un usuario y autenticarlo
    $user = CreateUser();

    $userSet = UserSet::factory()->create([
        'name' => 'Set Original',
        'description' => 'Descripción original',
        'image' => 'user_sets/old.jpg',
        'user_id' => $user->id,
    ]);

    $image1 = UploadedFile::fake()->image('image.jpg');

    $data = [
        'name' => 'Updated Set with Image',
        'description' => 'Description with image.',
        'image' => $image1,
    ];

    actingAs($user);


    // Hacer una solicitud PUT para actualizar el conjunto con imagen
    $response = $this->put(route('user-sets.update', $userSet->id), $data);

    $response->assertRedirect(route('user-sets.index'));

    $userSet->refresh();
    $imagePath = $userSet->image;

    expect($imagePath)->toMatch('/user_sets\/[a-zA-Z0-9]{40}\.jpg$/');
});



it('no actualiza el set si los datos no son válidos', function () {
    $user = CreateUser();

    actingAs($user);

    $userSet = UserSet::factory()->create();

    $response = $this->put(route('user-sets.update', $userSet->id), [
            'image' => 'lobro.pdf',
        ]);

    $userSet->refresh();
    expect($userSet->name)->not->toBe('');

    // Verifica que el error sea mostrado
    $response->assertSessionHasErrors('image');
});
