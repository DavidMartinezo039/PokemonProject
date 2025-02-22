<?php

use App\Models\Card;
use App\Models\Set;

it('returns a successful response when showing cards', function () {
    $set = Set::factory()->create();
    $cards = Card::factory(5)->create(['set_id' => $set->id]);

    $response = $this->get(route('sets.cards', $set));

    $response->assertStatus(200);
});

it('displays the correct cards', function () {
    $set = Set::factory()->create();
    $cards = Card::factory(3)->create(['set_id' => $set->id]);

    $response = $this->get(route('sets.cards', $set));

    foreach ($cards as $card) {
        $response->assertSee($card->name);
        $response->assertSee($card->images['small']);
    }
});

it('displays a message when no cards are available', function () {
    $set = Set::factory()->create();

    $response = $this->get(route('sets.cards', $set));

    $response->assertStatus(200);
    $response->assertSee(__('No cards available'));
});

it('returns a 404 for a nonexistent set', function () {
    $nonExistentSetId = 999;

    $response = $this->get(route('sets.cards', $nonExistentSetId));

    $response->assertStatus(404);
});
