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
     * Obtener todas las cartas.
     *
     * @OA\Get(
     *     path="/api/cards",
     *     summary="Lista todas las cartas",
     *     tags={"Cards"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cartas obtenida correctamente",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Card::all());
    }

    /**
     * Obtener una carta específica.
     *
     * @OA\Get(
     *     path="/api/cards/{id}",
     *     summary="Obtener una carta por su ID",
     *     tags={"Cards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta obtenida correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carta no encontrada"
     *     )
     * )
     */
    public function show(Card $card): JsonResponse
    {
        return response()->json($card);
    }
}
