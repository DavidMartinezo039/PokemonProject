<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserSet;

it('has many user sets', function () {
    $user = User::factory()->create();

    $userSets = UserSet::factory(3)->create(['user_id' => $user->id]);

    expect($user->userSets)->toHaveCount(3);
    expect($user->userSets->contains($userSets->first()))->toBeTrue();
});

it('handles empty user sets', function () {
    $user = User::factory()->create();

    expect($user->userSets)->toHaveCount(0);
});

it('creates a user and associates user sets', function () {
    $user = User::factory()->create();
    $userSets = UserSet::factory(2)->create(['user_id' => $user->id]);

    expect(\DB::table('users')->where('id', $user->id)->exists())->toBeTrue();
    expect($user->userSets)->toHaveCount(2);
    expect($user->userSets->contains($userSets->first()))->toBeTrue();
});
