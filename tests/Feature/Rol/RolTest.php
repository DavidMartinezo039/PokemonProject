<?php

use App\Models\User;
use App\Models\UserSet;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

it('can create roles and permissions', function () {
    $permission = Permission::create(['name' => 'view own sets']);
    expect(Permission::where('name', 'view own sets')->exists())->toBeTrue();

    $role = Role::create(['name' => 'user']);
    expect(Role::where('name', 'user')->exists())->toBeTrue();

    $role->givePermissionTo($permission);
    expect($role->hasPermissionTo('view own sets'))->toBeTrue();
});

it('assigns a role to a user', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);

    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue();
});

it('grants permissions through roles', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'user']);
    $permission = Permission::create(['name' => 'edit own sets']);

    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->can('edit own sets'))->toBeTrue();
});

it('allows an admin to see all user sets', function () {
    $admin = User::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $adminRole = Role::create(['name' => 'admin']);
    $admin->assignRole('admin');

    UserSet::factory()->create(['user_id' => $user1->id]);
    UserSet::factory()->create(['user_id' => $user2->id]);

    $this->actingAs($admin)
        ->get(route('user-sets.index'))
        ->assertStatus(200)
        ->assertViewHas('userSets', fn ($sets) => $sets->count() === 2);
});
