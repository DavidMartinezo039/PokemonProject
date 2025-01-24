<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Supertype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupertypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_cards()
    {
        $supertype = Supertype::factory()->create();

        $cards = Card::factory(3)->create(['supertype_id' => $supertype->id]);

        $this->assertCount(3, $supertype->cards);
        $this->assertTrue($supertype->cards->contains($cards->first()));
    }

    /** @test */
    public function it_handles_empty_cards()
    {
        $supertype = Supertype::factory()->create();

        $this->assertCount(0, $supertype->cards);
    }

    /** @test */
    public function it_creates_a_supertype_and_associates_cards()
    {
        $supertype = Supertype::factory()->create();
        $cards = Card::factory(2)->create(['supertype_id' => $supertype->id]);

        $this->assertDatabaseHas('supertypes', ['id' => $supertype->id]);
        $this->assertCount(2, $supertype->cards);
        $this->assertTrue($supertype->cards->contains($cards->first()));
    }
}
