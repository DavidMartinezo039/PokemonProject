<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Subtype;

it('belongs to many cards', function () {
    $subtype = Subtype::factory()->create();
    $cards = Card::factory(3)->create();

    $subtype->cards()->attach($cards);

    expect($subtype->cards)->toHaveCount(3);
    expect($subtype->cards->contains($cards->first()))->toBeTrue();
});

it('handles empty cards', function () {
    $subtype = Subtype::factory()->create();

    expect($subtype->cards)->toHaveCount(0);
});

it('creates a subtype and associates cards', function () {
    $subtype = Subtype::factory()->create();
    $cards = Card::factory(2)->create();

    $subtype->cards()->attach($cards);

    expect(\DB::table('subtypes')->where('id', $subtype->id)->exists())->toBeTrue();
    expect($subtype->cards)->toHaveCount(2);
    expect($subtype->cards->contains($cards->first()))->toBeTrue();
});
