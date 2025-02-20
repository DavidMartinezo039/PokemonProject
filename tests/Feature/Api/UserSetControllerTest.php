<?php

use App\Models\Card;
use App\Models\User;
use App\Models\UserSet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\actingAs;

test('puede listar todos los user sets', function () {
    $user = User::factory()->create();
    actingAs($user, 'sanctum');
    $userSets = UserSet::factory()->count(3)->create();

    $response = $this->getJson('/api/user-sets');

    $response->assertOk()->assertJsonCount(3);
});

test('puede crear un user set con imagen', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $image = UploadedFile::fake()->image('image.png');

    $data = [
        'name' => 'Mi colección',
        'description' => 'Una colección de prueba',
        'image' => $image,
    ];

    $response = $this->postJson('/api/user-sets', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('user_sets', [
        'name' => 'Mi colección',
        'description' => 'Una colección de prueba',
    ]);

    $userSet = UserSet::latest()->first();
    Storage::disk('public')->assertExists($userSet->image);
});

test('puede crear un user set', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = [
        'name' => 'Mi colección',
        'description' => 'Una colección de prueba',
    ];

    $response = $this->postJson('/api/user-sets', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('user_sets', [
        'name' => 'Mi colección',
        'description' => 'Una colección de prueba',
    ]);
});

test('puede ver un user set específico', function () {
    $userSet = UserSet::factory()->create();
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    $response = $this->getJson("/api/user-sets/{$userSet->id}");

    $response->assertOk()->assertJson(['id' => $userSet->id]);
});

test('puede actualizar un user set', function () {
    $user = User::factory()->create();
    actingAs($user);

    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    $data = ['name' => 'Nuevo nombre'];

    $response = $this->putJson("/api/user-sets/{$userSet->id}", $data);

    $response->assertOk();
    $this->assertDatabaseHas('user_sets', ['id' => $userSet->id, 'name' => 'Nuevo nombre']);
});

test('puede eliminar un user set', function () {
    $user = User::factory()->create();
    actingAs($user);

    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    $response = $this->deleteJson("/api/user-sets/{$userSet->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('user_sets', ['id' => $userSet->id]);
});

test('puede agregar una carta a un user set', function () {
    $userSet = UserSet::factory()->create();
    $user = User::factory()->create();
    actingAs($user, 'sanctum');
    $card = Card::factory()->create();

    $response = $this->postJson("/api/user-sets/{$userSet->id}/card/{$card->id}");

    $response->assertOk();
    $this->assertDatabaseHas('user_set_cards', [
        'user_set_id' => $userSet->id,
        'card_id' => $card->id,
    ]);
});

test('puede eliminar una carta de un user set', function () {
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    $userSet = UserSet::factory()->create();
    $card = Card::factory()->create();
    $userSet->cards()->attach($card->id);

    $response = $this->deleteJson("/api/user-sets/{$userSet->id}/card/{$card->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('user_set_cards', [
        'user_set_id' => $userSet->id,
        'card_id' => $card->id,
    ]);
});
