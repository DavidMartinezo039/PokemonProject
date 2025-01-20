<?php

namespace Tests\Feature\UserSets;

use App\Models\UserSet;
use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSetsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_add_card_to_user_set()
    {
        User::factory()->create(['id' => 1]);

        $userSet = UserSet::create([
            'name' => 'Set de prueba',
            'user_id' => 1,
            'card_count' => 0,
        ]);

        $card = Card::factory()->create();

        $response = $this->postJson("/user-sets/{$userSet->id}/add-card/{$card->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Carta añadida correctamente al set',
        ]);

        $userSet->refresh();
        $this->assertEquals(1, $userSet->card_count);
    }

    /** @test */
    public function test_remove_card_from_user_set()
    {
        User::factory()->create(['id' => 1]);

        $userSet = UserSet::create([
            'name' => 'Set de prueba',
            'user_id' => 1,
            'card_count' => 1,
        ]);

        $card = Card::factory()->create();

        $userSet->cards()->attach($card->id);

        $response = $this->postJson("/user-sets/{$userSet->id}/remove-card/{$card->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Carta eliminada correctamente del set',
        ]);

        $userSet->refresh();
        $this->assertEquals(0, $userSet->card_count);
    }
}
