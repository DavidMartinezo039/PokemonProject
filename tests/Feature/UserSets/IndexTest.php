<?php

use App\Models\User;
use App\Models\UserSet;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('allows an authenticated user to see their sets', function () {
    $user = User::factory()->create();
    $sets = UserSet::factory()->count(3)->for($user)->create();

    actingAs($user)
        ->get(route('user-sets.index'))
        ->assertStatus(200)
        ->assertSee($sets->first()->name);
});

it('does not allow unauthenticated users to access the sets index', function () {
    get(route('user-sets.index'))->assertRedirect(route('login'));
});

it('shows the create set button for authenticated users', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('user-sets.index'))
        ->assertSee('+');
});
