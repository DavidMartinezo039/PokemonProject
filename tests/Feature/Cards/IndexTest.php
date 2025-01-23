<?php

namespace Cards;

use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_index_returns_successful_response()
    {
        $response = $this->get(route('cards.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_index_displays_cards_correctly()
    {
        $cards = Card::factory(5)->create();

        $response = $this->get(route('cards.index'));

        $response->assertStatus(200);
        foreach ($cards as $card) {
            $response->assertSee($card->images['small']);
        }
    }

    /** @test */
    public function test_index_displays_message_when_no_cards_exist()
    {
        $response = $this->get(route('cards.index'));

        $response->assertSee('No hay cartas disponibles.');
    }
}
