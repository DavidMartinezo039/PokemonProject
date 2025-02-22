<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Endpoints para gestionar cartas"
 * )
 */
class CardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cards",
     *     summary="List all cards",
     *     tags={"Cards"},
     *     @OA\Response(
     *         response=200,
     *         description="List of cards obtained successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Card::all());
    }

    /**
     * @OA\Get(
     *     path="/api/cards/{card}",
     *     summary="Get a card",
     *     tags={"Cards"},
     *     @OA\Parameter(
     *         name="card",
     *         in="path",
     *         required=true,
     *         description="Card identifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully Obtained Card",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found"
     *     )
     * )
     */
    public function show(Card $card): JsonResponse
    {
        return response()->json($card);
    }
}
