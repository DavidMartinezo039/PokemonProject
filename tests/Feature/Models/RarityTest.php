<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Rarity;

it('has many cards', function () {
    $rarity = Rarity::factory()->create();
    $cards = Card::factory(3)->create(['rarity_id' => $rarity->id]);

    expect($rarity->cards)->toHaveCount(3);
    expect($rarity->cards->contains($cards->first()))->toBeTrue();
});

it('a rarity can be created with a name', function () {
    $rarity = Rarity::factory()->create(['name' => 'Rare']);

    expect($rarity->name)->toEqual('Rare');
    expect(\DB::table('rarities')->where('name', 'Rare')->exists())->toBeTrue();
});

it('prevents duplicate rarity names', function () {
    Rarity::factory()->create(['name' => 'Rare']);

    expect(fn() => Rarity::factory()->create(['name' => 'Rare']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
