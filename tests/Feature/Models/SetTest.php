<?php

namespace Tests\Feature\Models;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_cards()
    {
        $set = Set::factory()->create();
        $card1 = Card::factory()->create(['set_id' => $set->id]);
        $card2 = Card::factory()->create(['set_id' => $set->id]);

        $this->assertCount(2, $set->cards);
        $this->assertTrue($set->cards->contains($card1));
        $this->assertTrue($set->cards->contains($card2));
    }

    /** @test */
    public function it_casts_legalities_and_images_to_array()
    {
        $set = Set::factory()->create([
            'legalities' => ['standard' => 'legal', 'expanded' => 'not_legal'],
            'images' => ['small' => 'image.jpg', 'large' => 'image_large.jpg'],
        ]);

        $this->assertIsArray($set->legalities);
        $this->assertIsArray($set->images);
        $this->assertEquals(['standard' => 'legal', 'expanded' => 'not_legal'], $set->legalities);
        $this->assertEquals(['small' => 'image.jpg', 'large' => 'image_large.jpg'], $set->images);
    }

    /** @test */
    public function it_belongs_to_many_cards()
    {
        $set = Set::factory()->create();
        $cards = Card::factory(3)->create(['set_id' => $set->id]);

        $this->assertCount(3, $set->cards);
        $this->assertTrue($set->cards->contains($cards->first()));
    }

    /** @test */
    public function it_has_custom_primary_key()
    {
        $set = Set::create([
            'id' => 'set123',
            'name' => 'Set de prueba',
            'series' => 'Serie 1',
            'printedTotal' => 100,
            'total' => 120,
            'legalities' => ['standard' => 'legal', 'expanded' => 'not_legal'],
            'ptcgoCode' => 'XYZ123',
            'releaseDate' => '2025-01-01',
            'updatedAt' => now(),
            'images' => ['small' => 'image.jpg', 'large' => 'image_large.jpg'],
        ]);

        $this->assertEquals('set123', $set->id);
        $this->assertDatabaseHas('sets', ['id' => 'set123']);
    }
}
