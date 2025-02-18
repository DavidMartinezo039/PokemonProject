<?php

use App\Models\UserSet;
use App\Models\Card;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('creates and stores a user set', function () {
    $user = CreateUser();

    actingAs($user);

    $response = $this->post('/user-sets', [
        'name' => 'Nuevo Set',
        'description' => 'Nuevo Set',
    ]);

    $response->assertRedirect(route('user-sets.index'));

    $this->assertDatabaseHas('user_sets', [
        'name' => 'Nuevo Set',
        'description' => 'Nuevo Set',
        'user_id' => $user->id,
    ]);
});

it('updates a user set', function () {
    $user = CreateUser();

    actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set Inicial',
        'description' => 'Set Inicial',
        'user_id' => $user->id,
    ]);

    $response = $this->put(route('user-sets.update', $userSet->id), ['name' => 'Set Editado']);
    $response->assertRedirect(route('user-sets.index'));
    $response->assertSessionHas('success', 'Set actualizado con éxito');

    $this->assertDatabaseHas('user_sets', [
        'id' => $userSet->id,
        'name' => 'Set Editado',
        'description' => 'Set Inicial',
    ]);

    $response = $this->put(route('user-sets.update', $userSet->id), ['description' => 'Set Editado']);
    $response->assertRedirect(route('user-sets.index'));
    $response->assertSessionHas('success', 'Set actualizado con éxito');

    $this->assertDatabaseHas('user_sets', [
        'id' => $userSet->id,
        'name' => 'Set Editado',
        'description' => 'Set Editado',
    ]);
});

it('deletes a user set', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set a eliminar',
        'user_id' => $user->id,
    ]);

    $response = $this->delete("/user-sets/{$userSet->id}");

    $response->assertRedirect(route('user-sets.index'));
    $response->assertSessionHas('success', 'Set eliminado con éxito');

    $this->assertDatabaseMissing('user_sets', [
        'id' => $userSet->id,
    ]);
});

it('adds a card to a user set', function () {
    $user = User::factory()->create(['id' => 1]);

    $this->actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set de prueba',
        'user_id' => 1,
        'card_count' => 0,
    ]);

    $card = Card::factory()->create();

    $response = $this->post("/user-sets/{$userSet->id}/card/{$card->id}");

    $response->assertRedirect(route('user-sets.cards', $userSet->id));
    $response->assertSessionHas('success', 'Carta añadida correctamente al set');

    $this->assertDatabaseHas('user_set_cards', [
        'user_set_id' => $userSet->id,
        'card_id' => $card->id,
    ]);

    $userSet->refresh();
    expect($userSet->card_count)->toBe(1);

    $response = $this->get(route('user-sets.cards', $userSet->id));
    $response->assertSee($card->image);
});

it('removes a card from a user set', function () {
    $user = User::factory()->create(['id' => 1]);

    $this->actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set de prueba',
        'user_id' => 1,
        'card_count' => 1,
    ]);

    $card = Card::factory()->create();

    $userSet->cards()->attach($card->id);

    $response = $this->delete("/user-sets/{$userSet->id}/card/{$card->id}");

    $response->assertRedirect(route('user-sets.cards', $userSet->id));
    $response->assertSessionHas('success', 'Carta eliminada correctamente del set');

    $userSet->refresh();
    expect($userSet->card_count)->toBe(0);
});

it('handles errors when adding a card to a user set', function () {
    $user = CreateUser();

    actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set de prueba',
        'user_id' => 1,
        'card_count' => 0,
    ]);

    $response = $this->post("/user-sets/{$userSet->id}/card/999");

    $response->assertRedirect(route('user-sets.cards', $userSet->id));
    $response->assertSessionHas('error', 'La carta no existe.');

    $card = Card::factory()->create();

    $response = $this->post("/user-sets/999/card/{$card->id}");

    $response->assertStatus(404);
    $response->assertSee('El set solicitado no fue encontrado o no tienes permiso para verlo.');

    $userSet->cards()->attach($card->id);

    $response = $this->post("/user-sets/{$userSet->id}/card/{$card->id}");
    $response = $this->post("/user-sets/{$userSet->id}/card/{$card->id}");

    $response->assertRedirect(route('user-sets.cards', $userSet->id));
    $response->assertSessionHas('message', 'La carta ya está en este set.');
});

it('handles errors when removing a card from a user set', function () {
    $user = CreateUser();

    actingAs($user);

    $userSet = UserSet::create([
        'name' => 'Set de prueba',
        'user_id' => 1,
        'card_count' => 0,
    ]);

    $response = $this->delete("/user-sets/{$userSet->id}/card/999");

    $response->assertRedirect(route('user-sets.cards', $userSet->id));
    $response->assertSessionHas('message', 'La carta no se encuentra en el set o no existe.');

    $card = Card::factory()->create();

    $response = $this->delete("/user-sets/999/card/{$card->id}");

    $response->assertStatus(404);
    $response->assertSee('El set solicitado no fue encontrado o no tienes permiso para verlo.');
});
