<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_set_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $userSet = UserSet::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $userSet->user);
        $this->assertEquals($user->id, $userSet->user->id);
    }

    /** @test */
    public function it_can_create_user_sets_with_factory()
    {
        $user = User::factory()->create();
        $userSet = UserSet::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('user_sets', [
            'id' => $userSet->id,
            'name' => $userSet->name,
            'description' => $userSet->description,
            'image' => $userSet->image,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_retrieve_user_sets_for_a_user()
    {
        $user = User::factory()->create();
        $userSets = UserSet::factory(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->userSets);
        $this->assertTrue($user->userSets->contains($userSets->first()));
    }

    /** @test */
    public function deleting_a_user_deletes_its_user_sets()
    {
        $user = User::factory()->create();
        $userSet = UserSet::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('user_sets', ['id' => $userSet->id]);
    }
}
