<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Set;

it('has many cards', function () {
    $set = Set::factory()->create();
    $card1 = Card::factory()->create(['set_id' => $set->id]);
    $card2 = Card::factory()->create(['set_id' => $set->id]);

    expect($set->cards)->toHaveCount(2);
    expect($set->cards->contains($card1))->toBeTrue();
    expect($set->cards->contains($card2))->toBeTrue();
});

it('casts legalities and images to array', function () {
    $set = Set::factory()->create([
        'legalities' => ['standard' => 'legal', 'expanded' => 'not_legal'],
        'images' => ['small' => 'image.jpg', 'large' => 'image_large.jpg'],
    ]);

    expect($set->legalities)->toBeArray();
    expect($set->images)->toBeArray();
    expect($set->legalities)->toEqual(['standard' => 'legal', 'expanded' => 'not_legal']);
    expect($set->images)->toEqual(['small' => 'image.jpg', 'large' => 'image_large.jpg']);
});

it('belongs to many cards', function () {
    $set = Set::factory()->create();
    $cards = Card::factory(3)->create(['set_id' => $set->id]);

    expect($set->cards)->toHaveCount(3);
    expect($set->cards->contains($cards->first()))->toBeTrue();
});

it('has custom primary key', function () {
    $set = Set::create([
        'id' => 'set123',
        'name' => 'Set de prueba',
        'series' => 'Serie 1',
        'printedTotal' => 100,
        'total' => 120,
        'legalities' => ['standard' => 'legal', 'expanded' => 'not_legal'],
        'ptcgoCode' => 'XYZ123',
        'releaseDate' => '2025-01-01',
        'updatedAt' => now(),
        'images' => ['small' => 'image.jpg', 'large' => 'image_large.jpg'],
    ]);

    expect($set->id)->toEqual('set123');
    expect(\DB::table('sets')->where('id', 'set123')->exists())->toBeTrue();
});
