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
