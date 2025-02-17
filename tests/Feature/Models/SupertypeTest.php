<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Supertype;

it('has many cards', function () {
    $supertype = Supertype::factory()->create();
    $cards = Card::factory(3)->create(['supertype_id' => $supertype->id]);

    expect($supertype->cards)->toHaveCount(3);
    expect($supertype->cards->contains($cards->first()))->toBeTrue();
});

it('handles empty cards', function () {
    $supertype = Supertype::factory()->create();

    expect($supertype->cards)->toHaveCount(0);
});

it('creates a supertype and associates cards', function () {
    $supertype = Supertype::factory()->create();
    $cards = Card::factory(2)->create(['supertype_id' => $supertype->id]);

    expect(\DB::table('supertypes')->where('id', $supertype->id)->exists())->toBeTrue();
    expect($supertype->cards)->toHaveCount(2);
    expect($supertype->cards->contains($cards->first()))->toBeTrue();
});
