<?php

namespace Tests\Feature\UserSets;

use App\Models\UserSet;
use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSetsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_create_user_set()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/user-sets', [
            'name' => 'Nuevo Set',
        ]);

        $response->assertRedirect(route('user-sets.index'));
        $response->assertSessionHas('message', 'Set creado con éxito');

        $this->assertDatabaseHas('user_sets', [
            'name' => 'Nuevo Set',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function test_edit_user_set()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $userSet = UserSet::create([
            'name' => 'Set Inicial',
            'user_id' => $user->id,
        ]);

        $response = $this->put("/user-sets/{$userSet->id}", [
            'name' => 'Set Editado',
        ]);

        $response->assertRedirect(route('user-sets.index'));
        $response->assertSessionHas('success', 'Set actualizado con éxito');

        // Verificar que el UserSet fue actualizado en la base de datos
        $this->assertDatabaseHas('user_sets', [
            'id' => $userSet->id,
            'name' => 'Set Editado',
        ]);
    }

    /** @test */
    public function test_delete_user_set()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $userSet = UserSet::create([
            'name' => 'Set a eliminar',
            'user_id' => $user->id,
        ]);

        $response = $this->delete("/user-sets/{$userSet->id}");

        $response->assertRedirect(route('user-sets.index'));
        $response->assertSessionHas('success', 'Set eliminado con éxito');

        // Verificar que el UserSet fue eliminado de la base de datos
        $this->assertDatabaseMissing('user_sets', [
            'id' => $userSet->id,
        ]);
    }

    /** @test */
    public function test_add_card_to_user_set()
    {
        $user = User::factory()->create(['id' => 1]);

        $this->actingAs($user);

        $userSet = UserSet::create([
            'name' => 'Set de prueba',
            'user_id' => 1,
            'card_count' => 0,
        ]);

        $card = Card::factory()->create();

        // Realizamos la solicitud POST para añadir la carta al set
        $response = $this->post("/user-sets/{$userSet->id}/add-card/{$card->id}");


        // Verificamos que la redirección se realiza a la vista del set
        $response->assertRedirect(route('user-sets.show', $userSet->id));

        // Verificamos que el mensaje de éxito está presente en la sesión
        $response->assertSessionHas('success', 'Carta añadida correctamente al set');

        // Verificamos que la carta fue añadida al set en la tabla intermedia
        $this->assertDatabaseHas('user_set_cards', [
            'user_set_id' => $userSet->id,
            'card_id' => $card->id,
        ]);

        // Verificamos que el contador del set se incrementó
        $userSet->refresh();
        $this->assertEquals(1, $userSet->card_count);

        // Verificamos que la carta se muestra en la vista del set
        $response = $this->get(route('user-sets.show', $userSet->id));
        $response->assertSee($card->name); // Cambia 'name' por un atributo relevante de la carta
    }



    /** @test */
    public function test_remove_card_from_user_set()
    {
        $user = User::factory()->create(['id' => 1]);

        $this->actingAs($user);

        $userSet = UserSet::create([
            'name' => 'Set de prueba',
            'user_id' => 1,
            'card_count' => 1,
        ]);

        $card = Card::factory()->create();

        $userSet->cards()->attach($card->id);  // Asociar la carta al set

        $response = $this->post("/user-sets/{$userSet->id}/remove-card/{$card->id}");

        $response->assertRedirect(route('user-sets.show', $userSet->id));

        // Verificar que la respuesta sea la correcta
        $response->assertSessionHas('success', 'Carta eliminada correctamente al set');

        // Verificar que el set ha sido actualizado correctamente
        $userSet->refresh();
        $this->assertEquals(0, $userSet->card_count);  // Asegurarse de que el contador se ha decrementado
    }

}
