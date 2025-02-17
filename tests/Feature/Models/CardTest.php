<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Set;
use App\Models\Supertype;
use App\Models\Rarity;
use App\Models\Type;
use App\Models\Subtype;
use App\Models\User;
use App\Models\UserSet;

it('belongs to a set', function () {
    $set = Set::factory()->create();
    $card = Card::factory()->create(['set_id' => $set->id]);

    expect($card->set)->toBeInstanceOf(Set::class);
    expect($card->set->id)->toEqual($set->id);
});

it('belongs to a supertype', function () {
    $supertype = Supertype::factory()->create();
    $card = Card::factory()->create(['supertype_id' => $supertype->id]);

    expect($card->supertype)->toBeInstanceOf(Supertype::class);
    expect($card->supertype->id)->toEqual($supertype->id);
});

it('belongs to a rarity', function () {
    $rarity = Rarity::factory()->create();
    $card = Card::factory()->create(['rarity_id' => $rarity->id]);

    expect($card->rarity)->toBeInstanceOf(Rarity::class);
    expect($card->rarity->id)->toEqual($rarity->id);
});

it('belongs to many types', function () {
    $card = Card::factory()->create();
    $types = Type::factory(3)->create();

    $card->types()->attach($types);

    expect($card->types)->toHaveCount(3);
    expect($card->types->contains($types->first()))->toBeTrue();
});

it('belongs to many subtypes', function () {
    $card = Card::factory()->create();
    $subtypes = Subtype::factory(2)->create();

    $card->subtypes()->attach($subtypes);

    expect($card->subtypes)->toHaveCount(2);
    expect($card->subtypes->contains($subtypes->first()))->toBeTrue();
});

it('handles empty types and subtypes', function () {
    $card = Card::factory()->create();

    expect($card->types)->toHaveCount(0);
    expect($card->subtypes)->toHaveCount(0);
});

it('deleting a card does not affect related sets or supertypes', function () {
    $set = Set::factory()->create();
    $supertype = Supertype::factory()->create();
    $card = Card::factory()->create(['set_id' => $set->id, 'supertype_id' => $supertype->id]);

    $card->delete();

    expect(\DB::table('sets')->where('id', $set->id)->exists())->toBeTrue();
    expect(\DB::table('supertypes')->where('id', $supertype->id)->exists())->toBeTrue();
});

it('belongs to many user sets', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    $card = Card::factory()->create();
    $userSet->cards()->attach($card);

    expect($userSet->cards)->toHaveCount(1);
    expect($userSet->cards->contains($card))->toBeTrue();
    expect($card->userSets->contains($userSet))->toBeTrue();
});

it('associates a card to a user set', function () {
    $userSet = UserSet::factory()->create();
    $card = Card::factory()->create();

    $userSet->cards()->attach($card);

    expect($userSet->cards->contains($card))->toBeTrue();
    expect($userSet->cards()->count())->toEqual(1);
});
