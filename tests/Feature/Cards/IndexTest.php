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
            $response->assertSee($card->name);
            $response->assertSee($card->rarity ? $card->rarity->name : 'No tiene rareza');
        }
    }

    /** @test */
    public function test_index_displays_message_when_no_cards_exist()
    {
        // No crear ninguna carta

        // Realiza una solicitud GET a la ruta 'cards.index'
        $response = $this->get(route('cards.index'));

        // Verifica que se muestra un mensaje indicando que no hay cartas
        $response->assertSee('No hay cartas disponibles.');
    }
}
