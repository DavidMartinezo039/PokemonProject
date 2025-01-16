<?php

namespace Sets;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowCardsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_show_cards_returns_successful_response()
    {
        $set = Set::factory()->create();
        $cards = Card::factory(5)->create(['set_id' => $set->id]);

        $response = $this->get(route('sets.cards', $set, $cards));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_show_cards_displays_correct_cards()
    {
        $set = Set::factory()->create();
        $cards = Card::factory(3)->create(['set_id' => $set->id]);

        $response = $this->get(route('sets.cards', $set, $cards));

        foreach ($cards as $card) {
            $response->assertSee($card->name);
            $response->assertSee($card->images['small']);
        }
    }

    /** @test */
    public function test_show_cards_displays_no_cards_message_when_empty()
    {
        $set = Set::factory()->create();

        $response = $this->get(route('sets.cards', $set));

        $response->assertStatus(200);
        $response->assertSee('No hay cartas disponibles.');
    }

    /** @test */
    public function test_show_cards_returns_404_for_nonexistent_set()
    {
        $nonExistentSetId = 999;

        $response = $this->get(route('sets.cards', $nonExistentSetId));

        $response->assertStatus(404);
    }
}
