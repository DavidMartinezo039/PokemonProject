<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_user_sets()
    {
        $user = User::factory()->create();

        $userSets = UserSet::factory(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->userSets);
        $this->assertTrue($user->userSets->contains($userSets->first()));
    }

    /** @test */
    public function it_handles_empty_user_sets()
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->userSets);
    }

    /** @test */
    public function it_creates_a_user_and_associates_user_sets()
    {
        $user = User::factory()->create();
        $userSets = UserSet::factory(2)->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertCount(2, $user->userSets);
        $this->assertTrue($user->userSets->contains($userSets->first()));
    }
}
