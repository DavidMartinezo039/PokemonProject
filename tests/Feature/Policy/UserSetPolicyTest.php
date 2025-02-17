<?php

use App\Providers\AuthServiceProvider;
use App\Models\User;
use App\Models\UserSet;
use App\Policies\UserSetPolicy;
use Illuminate\Support\Facades\Gate;

test('AuthServiceProvider se registra correctamente', function () {
    $provider = new AuthServiceProvider(app());
    $provider->boot();

    expect(true)->toBeTrue();
});

it('registers the UserSetPolicy', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);

    expect(Gate::getPolicyFor($userSet))->toBeInstanceOf(UserSetPolicy::class);
});

it('allows an admin to view any user set', function () {
    $admin = CreateUser('admin');
    $policy = new UserSetPolicy();

    expect($policy->viewAny($admin))->toBeTrue();
});

it('denies a regular user from viewing any user set', function () {
    $user = CreateUser();
    $policy = new UserSetPolicy();

    expect($policy->viewAny($user))->toBeFalse();
});

it('allows an admin to view a specific user set', function () {
    $admin = CreateUser('admin');
    $userSet = UserSet::factory()->create();
    $policy = new UserSetPolicy();

    expect($policy->view($admin, $userSet))->toBeTrue();
});

it('allows the owner of the user set to view it', function () {
    $user = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user->id]);
    $policy = new UserSetPolicy();

    expect($policy->view($user, $userSet))->toBeTrue();
});

it('denies other users from viewing a user set that does not belong to them', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $userSet = UserSet::factory()->create(['user_id' => $user1->id]);
    $policy = new UserSetPolicy();

    expect($policy->view($user2, $userSet))->toBeFalse();
});
