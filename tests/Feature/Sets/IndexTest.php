<?php

namespace Sets;

use App\Models\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_index_returns_successful_response()
    {
        $response = $this->get(route('sets.index'));

        $response->assertStatus(200);
    }

    public function test_index_returns_correct_data()
    {
        Set::factory(5)->create();

        $response = $this->get(route('sets.index'));

        $response->assertStatus(200);

        foreach (Set::all() as $set) {
            $response->assertSee($set->name);

            $response->assertSee($set->images['logo']);
        }
    }

    public function test_index_shows_message_when_no_sets_are_available()
    {
        $response = $this->get(route('sets.index'));

        $response->assertSee('No sets available');
    }

    public function test_image_redirects_to_show_page()
    {
        $set = Set::factory()->create();

        $response = $this->get(route('sets.index'));

        $response->assertStatus(200);

        $response->assertSeeHtml('<a href="' . route('sets.show', $set->id) . '">');
        $response = $this->get(route('sets.show', $set->id));

        $response->assertStatus(200);
    }

}
