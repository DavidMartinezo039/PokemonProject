<?php

use App\Livewire\UserSetSearch;
use App\Models\UserSet;
use Livewire\Livewire;

it('can search user sets by name', function () {

    UserSet::factory()->create(['name' => 'Pikachu Set']);
    UserSet::factory()->create(['name' => 'Charmander Set']);

    Livewire::test(UserSetSearch::class)
        ->set('searchTerm', 'Pikachu')
        ->assertSee('Pikachu Set')
        ->assertDontSee('Charmander Set');
});
