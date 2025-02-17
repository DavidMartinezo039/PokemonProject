<?php

namespace Sets;

use App\Models\Set;

it('returns a successful response on index', function () {
    $response = $this->get(route('sets.index'));

    $response->assertStatus(200);
});

it('returns correct data on index', function () {
    Set::factory(5)->create();

    $response = $this->get(route('sets.index'));

    $response->assertStatus(200);

    foreach (Set::all() as $set) {
        $response->assertSee($set->name);
        $response->assertSee($set->images['logo']);
    }
});

it('shows a message when no sets are available', function () {
    $response = $this->get(route('sets.index'));

    $response->assertSee('No sets available');
});

it('redirects image to the show page', function () {
    $set = Set::factory()->create();

    $response = $this->get(route('sets.index'));

    $response->assertStatus(200);

    $response->assertSeeHtml('<a href="' . route('sets.cards', $set->id) . '" class="set-card">');
    $response = $this->get(route('sets.cards', $set->id));

    $response->assertStatus(200);
});
