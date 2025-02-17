<?php

namespace Tests\Feature\Cards;

use App\Models\Card;
use App\Models\Subtype;
use App\Models\Rarity;
use App\Models\Set;

it('returns a successful response when showing a card', function () {
    $card = Card::factory()->create();

    $response = $this->get(route('cards.show', $card->id));

    $response->assertStatus(200);
});

it('displays correct card data when showing a card', function () {
    $set = Set::factory()->create();
    $rarity = Rarity::factory()->create();
    $subtype1 = Subtype::factory()->create();
    $subtype2 = Subtype::factory()->create();

    $card = Card::factory()->create([
        'set_id' => $set->id,
        'rarity_id' => $rarity->id,
    ]);

    $card->subtypes()->attach([$subtype1->id, $subtype2->id]);

    $response = $this->get(route('cards.show', $card->id));

    $response->assertSee($card->name);
    $response->assertSee($rarity->name);
    $response->assertSee($set->name);
    $response->assertSee($subtype1->name);
    $response->assertSee($subtype2->name);
    $response->assertSee($card->images['large']);
});

it('returns a 404 when the card is not found', function () {
    $response = $this->get(route('cards.show', 999));

    $response->assertStatus(404);
});
