<?php

use App\Models\Set;
use App\Models\Card;
use function Pest\Laravel\getJson;

it('obtiene todos los sets', function () {
    Set::factory()->count(3)->create();

    $response = getJson('/api/sets');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('muestra un set específico', function () {
    $set = Set::factory()->create();

    $response = getJson("/api/sets/{$set->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $set->id,
            'name' => $set->name,
        ]);
});

it('retorna 404 si el set no existe', function () {
    $response = getJson('/api/sets/999');

    $response->assertStatus(404);
});

it('muestra las cartas de un set', function () {
    $set = Set::factory()->create();
    $cards = Card::factory()->count(3)->create(['set_id' => $set->id]);

    $response = getJson("/api/sets/{$set->id}/cards");

    $response->assertStatus(200)
        ->assertJsonCount(3);
});
