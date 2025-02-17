<?php

namespace Cards;

use App\Models\Card;

it('returns a successful response for the index', function () {
    $response = $this->get(route('cards.index'));

    $response->assertStatus(200);
});

it('displays cards correctly on the index', function () {
    $cards = Card::factory(5)->create();

    $response = $this->get(route('cards.index'));

    $response->assertStatus(200);
    foreach ($cards as $card) {
        $response->assertSee($card->images['small']);
    }
});

it('displays a message when no cards exist', function () {
    $response = $this->get(route('cards.index'));

    $response->assertSee('No hay cartas disponibles.');
});
