<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Set;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Sets",
 *     description="Endpoints para gestionar sets de cartas"
 * )
 */
class SetController extends Controller
{
    /**
     * Obtener todos los sets.
     *
     * @OA\Get(
     *     path="/api/sets",
     *     summary="Obtener todos los sets",
     *     tags={"Sets"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de sets obtenida correctamente",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Set"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Set::all());
    }

    /**
     * Obtener un set específico.
     *
     * @OA\Get(
     *     path="/api/sets/{id}",
     *     summary="Obtener un set específico",
     *     tags={"Sets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del set",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Set obtenido correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Set")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Set no encontrado"
     *     )
     * )
     */
    public function show(Set $set)
    {
        return response()->json($set);
    }

    /**
     * Obtener todas las cartas de un set específico.
     *
     * @OA\Get(
     *     path="/api/sets/{id}/cards",
     *     summary="Obtener las cartas de un set",
     *     tags={"Sets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del set",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cartas del set obtenida correctamente",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Set no encontrado"
     *     )
     * )
     */
    public function showCards(Set $set)
    {
        return response()->json($set->cards);
    }
}
