<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Type;

it('belongs to many cards', function () {
    $type = Type::factory()->create();
    $cards = Card::factory(3)->create();

    $type->cards()->attach($cards);

    expect($type->cards)->toHaveCount(3);
    expect($type->cards->contains($cards->first()))->toBeTrue();
});

it('handles empty cards', function () {
    $type = Type::factory()->create();

    expect($type->cards)->toHaveCount(0);
});

it('creates a type and associates cards', function () {
    $type = Type::factory()->create();
    $cards = Card::factory(2)->create();

    $type->cards()->attach($cards);

    expect(\DB::table('types')->where('id', $type->id)->exists())->toBeTrue();
    expect($type->cards)->toHaveCount(2);
    expect($type->cards->contains($cards->first()))->toBeTrue();
});
