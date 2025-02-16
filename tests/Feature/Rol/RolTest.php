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
    $admin = CreateUser('admin');
    $user1 = CreateUser();
    $user2 = CreateUser();

    UserSet::factory()->create(['name' => 'lo', 'user_id' => $user1->id]);
    UserSet::factory()->create(['name' => 'veo','user_id' => $user2->id]);

    $this->actingAs($admin)
        ->get(route('user-sets.index'))
        ->assertStatus(200)
        ->assertSee('lo')
        ->assertSee('veo');
});
