<?php

namespace App\Http\Controllers\Api;

use App\Events\UserSetUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSetRequest;
use App\Jobs\GenerateUserSetPdf;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @OA\Info(
 *     title="Poket Project API",
 *     description="API for managing user sets, see all cards and all sets of pokemon.",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="hello@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *   @OA\Components(
 *     @OA\Schema(
 *         schema="UserSet",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="image", type="string"),
 *         @OA\Property(property="user_id", type="integer"),
 *         @OA\Property(
 *             property="cards",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Card")
 *         )
 *     ),
 *     @OA\Schema(
 *         schema="Card",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string")
 *     ),
 *     @OA\Response(
 *         response="UserSetResponse",
 *         description="Set de usuario encontrado",
 *         @OA\JsonContent(ref="#/components/schemas/UserSet")
 *     ),
 *     @OA\Response(
 *         response="UserSetListResponse",
 *         description="Lista de sets de usuario",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/UserSet")
 *         )
 *     ),
 *     @OA\Schema(
 *          schema="User",
 *          type="object",
 *          properties={
 *              @OA\Property(property="id", type="integer", example=1),
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *              @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
 *              @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
 *          }
 *      ),
 *      @OA\Schema(
 *          schema="Set",
 *          type="object",
 *          properties={
 *              @OA\Property(property="id", type="integer", example=1),
 *              @OA\Property(property="name", type="string", example="Set of Pokémon Cards"),
 *              @OA\Property(property="description", type="string", example="A special set containing rare Pokémon cards."),
 *              @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
 *              @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
 *          }
 *      ),
 *     @OA\Schema(
 *          schema="UserSetRequest",
 *          type="object",
 *          required={"name"},
 *          @OA\Property(property="name", type="string", description="El nombre del set", example="Mi Set de Pokémon"),
 *          @OA\Property(property="description", type="string", description="Descripción del set", example="Un set especial con cartas raras."),
 *          @OA\Property(property="image", type="string", description="Imagen del set", example="https://example.com/image.jpg")
 *      ),
 * )
 */

class UserSetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user_sets",
     *     summary="Get all user sets",
     *     tags={"UserSets"},
     *     @OA\Response(
     *         response=200,
     *         description="List of user sets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserSet")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = auth()->user();

        $userSets = UserSet::where('user_id', $user->id)->get();

        return response()->json($userSets);
    }

    /**
     * @OA\Post(
     *     path="/api/user_sets",
     *     summary="Create a new user set",
     *     tags={"UserSets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserSetRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User set created",
     *         @OA\JsonContent(ref="#/components/schemas/UserSet")
     *     )
     * )
     */
    public function store(UserSetRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');
        } else {
            $defaultImages = [
                'user_sets/predetermined/default1.png',
                'user_sets/predetermined/default2.png'
            ];

            $imagePath = $defaultImages[array_rand($defaultImages)];
        }
        $userSet = UserSet::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return response()->json($userSet, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/user_sets/{id}",
     *     summary="Get a user set by ID",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user set",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User set details",
     *         @OA\JsonContent(ref="#/components/schemas/UserSet")
     *     ),
     *     @OA\Response(response=404, description="User set not found")
     * )
     */
    public function show(UserSet $userSet)
    {
        return response()->json($userSet->load('cards'));
    }

    /**
     * @OA\Put(
     *     path="/api/user_sets/{id}",
     *     summary="Update a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user set",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserSetRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated user set",
     *         @OA\JsonContent(ref="#/components/schemas/UserSet")
     *     )
     * )
     */
    public function update(UserSetRequest $request, $id)
    {
        $userSet = UserSet::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');
            $userSet->image = $imagePath;
        }

        $userSet->update($validated);
        return response()->json($userSet);
    }

    /**
     * @OA\Delete(
     *     path="/api/user_sets/{id}",
     *     summary="Delete a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user set",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User set deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Set eliminado con éxito")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $userSet = UserSet::findOrFail($id);

        $userSet->delete();
        return response()->json(['message' => 'Set eliminado con éxito']);
    }

    /**
     * @OA\Post(
     *     path="/api/user_sets/{userSetId}/add_card/{cardId}",
     *     summary="Add a card to a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSetId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="cardId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Carta añadida correctamente al set")
     *         )
     *     )
     * )
     */
    public function addCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $card = Card::findOrFail($cardId);

        if (!$userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->attach($cardId, ['order_number' => $userSet->cards()->max('order_number') + 1]);
            $userSet->increment('card_count');
        }

        return response()->json(['message' => 'Carta añadida correctamente al set']);
    }

    /**
     * @OA\Delete(
     *     path="/api/user_sets/{userSetId}/remove_card/{cardId}",
     *     summary="Remove a card from a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSetId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="cardId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card removed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Carta eliminada correctamente del set")
     *         )
     *     )
     * )
     */
    public function removeCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $card = Card::findOrFail($cardId);

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');
        }

        return response()->json(['message' => 'Carta eliminada correctamente del set']);
    }
}
