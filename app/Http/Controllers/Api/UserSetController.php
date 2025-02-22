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
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="UserSet",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="image", type="string"),
 *         @OA\Property(property="user_id", type="integer"),
 *         @OA\Property(property="card_count", type="integer"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time"),
 *         @OA\Property(
 *             property="cards",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Card")
 *         )
 *     ),
 *     @OA\Schema(
 *         schema="Card",
 *         type="object",
 *         @OA\Property(property="id", type="string", description="Card identifier"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="supertype_id", type="integer"),
 *         @OA\Property(property="level", type="string", nullable=true),
 *         @OA\Property(property="hp", type="string", nullable=true),
 *         @OA\Property(property="evolvesFrom", type="string", nullable=true),
 *         @OA\Property(property="evolvesTo", type="array", @OA\Items(type="string"), nullable=true),
 *         @OA\Property(property="rules", type="array", @OA\Items(type="string"), nullable=true),
 *         @OA\Property(property="ancientTrait", type="string", nullable=true),
 *         @OA\Property(property="abilities", type="array", @OA\Items(type="object"), nullable=true),
 *         @OA\Property(
 *             property="attacks",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="cost", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="text", type="string"),
 *                 @OA\Property(property="damage", type="string"),
 *                 @OA\Property(property="convertedEnergyCost", type="integer")
 *             )
 *         ),
 *         @OA\Property(
 *              property="weaknesses",
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(property="type", type="string"),
 *                  @OA\Property(property="value", type="string")
 *              )
 *         ),
 *         @OA\Property(property="resistances", type="array", @OA\Items(type="object"), nullable=true),
 *         @OA\Property(property="retreatCost", type="array", @OA\Items(type="string"), nullable=true),
 *         @OA\Property(property="convertedRetreatCost", type="integer"),
 *         @OA\Property(property="set_id", type="string"),
 *         @OA\Property(property="number", type="string"),
 *         @OA\Property(property="artist", type="string"),
 *         @OA\Property(property="rarity_id", type="integer"),
 *         @OA\Property(property="flavorText", type="string"),
 *         @OA\Property(property="nationalPokedexNumbers", type="array", @OA\Items(type="integer")),
 *         @OA\Property(
 *             property="legalities",
 *             type="object",
 *             @OA\Property(property="unlimited", type="string")
 *         ),
 *         @OA\Property(property="regulationMark", type="string", nullable=true),
 *         @OA\Property(
 *             property="images",
 *             type="object",
 *             @OA\Property(property="large", type="string"),
 *             @OA\Property(property="small", type="string")
 *         ),
 *         @OA\Property(
 *             property="tcgplayer",
 *             type="object",
 *             @OA\Property(property="url", type="string"),
 *             @OA\Property(
 *                 property="prices",
 *                 type="object",
 *                 @OA\Property(
 *                     property="holofoil",
 *                     type="object",
 *                     @OA\Property(property="low", type="number"),
 *                     @OA\Property(property="mid", type="number"),
 *                     @OA\Property(property="high", type="number"),
 *                     @OA\Property(property="market", type="number"),
 *                     @OA\Property(property="directLow", type="number", nullable=true)
 *                 )
 *             ),
 *             @OA\Property(property="updatedAt", type="string", format="date")
 *         ),
 *         @OA\Property(
 *             property="cardmarket",
 *             type="object",
 *             @OA\Property(property="url", type="string"),
 *             @OA\Property(
 *                 property="prices",
 *                 type="object",
 *                 @OA\Property(property="avg1", type="number"),
 *                 @OA\Property(property="avg7", type="number"),
 *                 @OA\Property(property="avg30", type="number"),
 *                 @OA\Property(property="lowPrice", type="number"),
 *                 @OA\Property(property="trendPrice", type="number"),
 *                 @OA\Property(property="germanProLow", type="number"),
 *                 @OA\Property(property="lowPriceExPlus", type="number"),
 *                 @OA\Property(property="reverseHoloLow", type="number"),
 *                 @OA\Property(property="suggestedPrice", type="number"),
 *                 @OA\Property(property="reverseHoloAvg1", type="number"),
 *                 @OA\Property(property="reverseHoloAvg7", type="number"),
 *                 @OA\Property(property="reverseHoloSell", type="number"),
 *                 @OA\Property(property="averageSellPrice", type="number"),
 *                 @OA\Property(property="reverseHoloAvg30", type="number"),
 *                 @OA\Property(property="reverseHoloTrend", type="number")
 *             ),
 *             @OA\Property(property="updatedAt", type="string", format="date")
 *         ),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
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
 *              @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
 *              @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
 *              @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
 *          }
 *      ),
 *      @OA\Schema(
 *          schema="Set",
 *          type="object",
 *          @OA\Property(property="id", type="string", description="Set identifier"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="series", type="string"),
 *          @OA\Property(property="printedTotal", type="integer"),
 *          @OA\Property(property="total", type="integer"),
 *          @OA\Property(
 *              property="legalities",
 *              type="object",
 *              @OA\Property(property="unlimited", type="string")
 *          ),
 *          @OA\Property(property="ptcgoCode", type="string"),
 *          @OA\Property(property="releaseDate", type="string", format="date"),
 *          @OA\Property(property="updatedAt", type="string", format="date-time"),
 *          @OA\Property(
 *              property="images",
 *              type="object",
 *              @OA\Property(property="logo", type="string"),
 *              @OA\Property(property="symbol", type="string")
 *          ),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time")
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
 * @OA\Tag(
 *       name="UserSet",
 *       description="Endpoints to manage user sets"
 * )
 */
class UserSetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user-sets",
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
     *     path="/api/user-sets",
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
     *     path="/api/user-sets/{userSet}",
     *     summary="See a user set and cards of the set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSet",
     *         in="path",
     *         description="User set identifier",
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
     *     path="/api/user-sets/{userSet}",
     *     summary="Update a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSet",
     *         in="path",
     *         description="User set identifier",
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
    public function update(UserSetRequest $request, UserSet $userSet)
    {
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
     *     path="/api/user-sets/{userSet}",
     *     summary="Delete a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSet",
     *         in="path",
     *         description="User set identifier",
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
    public function destroy(UserSet $userSet)
    {
        $userSet->delete();
        return response()->json(['message' => 'Set eliminado con éxito']);
    }

    /**
     * @OA\Post(
     *     path="/api/user-sets/{userSet}/add-card/{card}",
     *     summary="Add a card to a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSet",
     *         in="path",
     *         description="User set identifier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="card",
     *         in="path",
     *         description="Card identifier",
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
    public function addCard(UserSet $userSet, Card $card)
    {
        if (!$userSet->cards()->where('card_id', $card->id)->exists()) {
            $userSet->cards()->attach($card->id, ['order_number' => $userSet->cards()->max('order_number') + 1]);
            $userSet->increment('card_count');
        }

        return response()->json(['message' => 'Carta añadida correctamente al set']);
    }

    /**
     * @OA\Delete(
     *     path="/api/user-sets/{userSet}/remove-card/{card}",
     *     summary="Remove a card from a user set",
     *     tags={"UserSets"},
     *     @OA\Parameter(
     *         name="userSet",
     *         in="path",
     *         description="User set identifier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="card",
     *         in="path",
     *         description="Card identifier",
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
    public function removeCard(UserSet $userSet, Card $card)
    {
        if ($userSet->cards()->where('card_id', $card->id)->exists()) {
            $userSet->cards()->detach($card->id);
            $userSet->decrement('card_count');
        }

        return response()->json(['message' => 'Carta eliminada correctamente del set']);
    }
}
