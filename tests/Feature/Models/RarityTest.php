<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Rarity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RarityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_cards()
    {
        $rarity = Rarity::factory()->create();
        $cards = Card::factory(3)->create(['rarity_id' => $rarity->id]);

        $this->assertCount(3, $rarity->cards);
        $this->assertTrue($rarity->cards->contains($cards->first()));
    }

    /** @test */
    public function a_rarity_can_be_created_with_a_name()
    {
        $rarity = Rarity::factory()->create(['name' => 'Rare']);

        $this->assertEquals('Rare', $rarity->name);
        $this->assertDatabaseHas('rarities', ['name' => 'Rare']);
    }

    /** @test */
    public function it_prevents_duplicate_rarity_names()
    {
        Rarity::factory()->create(['name' => 'Rare']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Rarity::factory()->create(['name' => 'Rare']);
    }
}
