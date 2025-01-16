<?php

namespace Sets;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_show_returns_successful_response()
    {
        $set = Set::factory()->create();

        $response = $this->get(route('sets.show', $set->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_show_displays_correct_set_details()
    {
        $set = Set::factory()->create();

        $response = $this->get(route('sets.show', $set->id));

        $response->assertSee($set->name);
        $response->assertSee($set->releaseDate);
        $response->assertSee($set->series);
        $response->assertSee($set->printedTotal);
        $response->assertSee($set->total);
        $response->assertSee($set->ptcgoCode);

        $response->assertSee($set->images['logo']);
        $response->assertSee($set->images['symbol']);
    }

    /** @test */
    public function test_show_returns_404_for_nonexistent_set()
    {
        $nonExistentId = 999;

        $response = $this->get(route('sets.show', $nonExistentId));

        $response->assertStatus(404);
    }

}
