<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Set;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Sets",
 *     description="Endpoints to manage card sets"
 * )
 */
class SetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sets",
     *     summary="Get all sets",
     *     tags={"Sets"},
     *     @OA\Response(
     *         response=200,
     *         description="Set list obtained successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Set"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Set::all());
    }

    /**
     * @OA\Get(
     *     path="/api/sets/{set}",
     *     summary="Get a specific set",
     *     tags={"Sets"},
     *     @OA\Parameter(
     *         name="set",
     *         in="path",
     *         required=true,
     *         description="Set identificator",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Set obtained correctly",
     *         @OA\JsonContent(ref="#/components/schemas/Set")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Set not found"
     *     )
     * )
     */
    public function show(Set $set)
    {
        return response()->json($set);
    }

    /**
     * @OA\Get(
     *     path="/api/sets/{set}/cards",
     *     summary="Get the cards from a set",
     *     tags={"Sets"},
     *     @OA\Parameter(
     *         name="set",
     *         in="path",
     *         required=true,
     *         description="Set identificator",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of cards from the set obtained successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Set not found"
     *     )
     * )
     */
    public function showCards(Set $set)
    {
        return response()->json($set->cards);
    }
}
