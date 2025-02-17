<?php

use App\Livewire\UserSetSearch;
use App\Models\UserSet;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('can search user sets by name', function () {
    $user = CreateUser();

    UserSet::factory()->create(['name' => 'Pikachu Set', 'user_id' => $user->id]);
    UserSet::factory()->create(['name' => 'Charmander Set', 'user_id' => $user->id]);

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Pikachu')
        ->assertSee('Pikachu Set')
        ->assertDontSee('Charmander Set');
});

it('admin can search all user sets by name', function () {
    $user = CreateUser('admin');
    $user2 = CreateUser();

    UserSet::factory()->create(['name' => 'Pikachu Set', 'user_id' => $user->id]);
    UserSet::factory()->create(['name' => 'Charmander Set', 'user_id' => $user2->id]);

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Set')
        ->assertSee('Pikachu Set')
        ->assertSee('Charmander Set');
});

it('can search user sets using the search method', function () {
    $user = CreateUser();

    UserSet::factory()->create(['name' => 'Pikachu Set', 'user_id' => $user->id]);
    UserSet::factory()->create(['name' => 'Charmander Set', 'user_id' => $user->id]);

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Pikachu')
        ->call('search')
        ->assertSee('Pikachu Set')
        ->assertDontSee('Charmander Set');
});


it('does not show sets from other users when the user does not have permission', function () {
    $user = CreateUser();
    $otherUser = CreateUser();

    UserSet::factory()->create(['name' => 'Pikachu Set', 'user_id' => $otherUser->id]);

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Pikachu')
        ->assertDontSee('Pikachu Set');
});

it('shows no sets available when no sets match', function () {
    $user = CreateUser();

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Bulbasaur')
        ->assertSee('No sets available');
});

it('searches user sets by partial name', function () {
    $user = CreateUser();

    UserSet::factory()->create(['name' => 'Pikachu Set', 'user_id' => $user->id]);
    UserSet::factory()->create(['name' => 'Charmander Set', 'user_id' => $user->id]);

    actingAs($user);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Pika')
        ->assertSee('Pikachu Set')
        ->assertDontSee('Charmander Set');
});
