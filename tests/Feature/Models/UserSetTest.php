<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserSet;

it('a user set belongs to a user', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    expect($userSet->user)->toBeInstanceOf(User::class);
    expect($userSet->user->id)->toBe($user->id);
});

it('can create user sets with factory', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    expect(\DB::table('user_sets')->where([
        'id' => $userSet->id,
        'name' => $userSet->name,
        'description' => $userSet->description,
        'image' => $userSet->image,
        'user_id' => $user->id,
    ])->exists())->toBeTrue();
});

it('can retrieve user sets for a user', function () {
    $user = User::factory()->create();
    $userSets = UserSet::factory(3)->create(['user_id' => $user->id]);

    expect($user->userSets)->toHaveCount(3);
    expect($user->userSets->contains($userSets->first()))->toBeTrue();
});

it('deleting a user deletes its user sets', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    $user->delete();

    expect(\DB::table('user_sets')->where('id', $userSet->id)->exists())->toBeFalse();
});
