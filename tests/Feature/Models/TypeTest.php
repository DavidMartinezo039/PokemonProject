<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_many_cards()
    {
        // Crear un Type
        $type = Type::factory()->create();

        $cards = Card::factory(3)->create();

        $type->cards()->attach($cards);

        $this->assertCount(3, $type->cards);
        $this->assertTrue($type->cards->contains($cards->first()));
    }

    /** @test */
    public function it_handles_empty_cards()
    {
        $type = Type::factory()->create();

        $this->assertCount(0, $type->cards);
    }

    /** @test */
    public function it_creates_a_type_and_associates_cards()
    {
        $type = Type::factory()->create();
        $cards = Card::factory(2)->create();

        $type->cards()->attach($cards);

        $this->assertDatabaseHas('types', ['id' => $type->id]);
        $this->assertCount(2, $type->cards);
        $this->assertTrue($type->cards->contains($cards->first()));
    }
}
