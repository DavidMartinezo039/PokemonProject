<?php

namespace Sets;

use App\Models\Set;

it('returns a successful response on show', function () {
    $set = Set::factory()->create();

    $response = $this->get(route('sets.show', $set->id));

    $response->assertStatus(200);
});

it('displays correct set details on show', function () {
    $set = Set::factory()->create();

    $response = $this->get(route('sets.show', $set->id));

    $response->assertSee($set->name);
    $response->assertSee($set->releaseDate);
    $response->assertSee($set->series);
    $response->assertSee($set->printedTotal);
    $response->assertSee($set->total);
    $response->assertSee($set->ptcgoCode);

    $response->assertSee($set->images['logo']);
    $response->assertSee($set->images['symbol']);
});

it('returns 404 for a nonexistent set', function () {
    $nonExistentId = 999;

    $response = $this->get(route('sets.show', $nonExistentId));

    $response->assertStatus(404);
});
