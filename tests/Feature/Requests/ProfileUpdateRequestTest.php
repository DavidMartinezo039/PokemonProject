<?php

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\actingAs;

it('allows a user to update their profile', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'password' => Hash::make('password123'),
    ]);

    actingAs($user);

    $newName = 'New Name';
    $newEmail = 'new@example.com';

    $requestData = [
        'name' => $newName,
        'email' => $newEmail,
    ];

    $response = $this->patch(route('profile.update'), $requestData);

    $response->assertRedirect(route('profile.edit'));

    $response->assertSessionHas('status', 'profile-updated');

    $user->refresh();
    expect($user->name)->toBe($newName)
        ->and($user->email)->toBe($newEmail);
});
