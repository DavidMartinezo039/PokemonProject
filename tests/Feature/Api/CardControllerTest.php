<?php

use App\Models\Card;
use function Pest\Laravel\getJson;

it('obtiene todas las cartas', function () {
    Card::factory()->count(3)->create();

    $response = getJson('/api/cards');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('muestra una carta específica', function () {
    $card = Card::factory()->create();

    $response = getJson("/api/cards/{$card->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $card->id,
            'name' => $card->name,
        ]);
});

it('retorna 404 si la carta no existe', function () {
    $response = getJson('/api/cards/999');

    $response->assertStatus(404);
});
