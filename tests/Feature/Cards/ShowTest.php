<?php

namespace Tests\Feature\Cards;

use App\Models\Card;
use App\Models\Subtype;
use App\Models\Rarity;
use App\Models\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_show_card_returns_successful_response()
    {
        $card = Card::factory()->create();

        $response = $this->get(route('cards.show', $card->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_show_card_displays_correct_data()
    {
        $set = Set::factory()->create();
        $rarity = Rarity::factory()->create();
        $subtype1 = Subtype::factory()->create();
        $subtype2 = Subtype::factory()->create();

        $card = Card::factory()->create([
            'set_id' => $set->id,
            'rarity_id' => $rarity->id,
        ]);

        $card->subtypes()->attach([$subtype1->id, $subtype2->id]);

        $response = $this->get(route('cards.show', $card->id));

        $response->assertSee($card->name);
        $response->assertSee($rarity->name);
        $response->assertSee($set->name);

        $response->assertSee($subtype1->name);
        $response->assertSee($subtype2->name);

        $response->assertSee($card->images['large']);
    }

    /** @test */
    public function test_show_card_not_found()
    {
        $response = $this->get(route('cards.show', 999));

        $response->assertStatus(404);
    }


    /** @test */
    public function test_show_card_displays_no_subtypes_message_if_no_subtypes()
    {
        $card = Card::factory()->create();

        $response = $this->get(route('cards.show', $card->id));

        $response->assertSee('No tiene subtipos');
    }
}
