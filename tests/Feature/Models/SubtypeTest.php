<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Subtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubtypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_many_cards()
    {
        $subtype = Subtype::factory()->create();

        $cards = Card::factory(3)->create();

        $subtype->cards()->attach($cards);

        $this->assertCount(3, $subtype->cards);
        $this->assertTrue($subtype->cards->contains($cards->first()));
    }

    /** @test */
    public function it_handles_empty_cards()
    {
        $subtype = Subtype::factory()->create();

        $this->assertCount(0, $subtype->cards);
    }

    /** @test */
    public function it_creates_a_subtype_and_associates_cards()
    {
        $subtype = Subtype::factory()->create();
        $cards = Card::factory(2)->create();

        $subtype->cards()->attach($cards);

        $this->assertDatabaseHas('subtypes', ['id' => $subtype->id]);
        $this->assertCount(2, $subtype->cards);
        $this->assertTrue($subtype->cards->contains($cards->first()));
    }
}
