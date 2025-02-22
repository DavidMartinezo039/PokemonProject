<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Endpoints para gestionar el perfil del usuario"
 * )
 */
class ProfileController extends Controller
{
    /**
     * Obtener el perfil del usuario autenticado.
     *
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Obtener el perfil del usuario autenticado",
     *     tags={"Profile"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil del usuario obtenido correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function show()
    {
        return response()->json(Auth::user());
    }

    /**
     * Actualizar el perfil del usuario autenticado.
     *
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Actualizar el perfil del usuario autenticado",
     *     tags={"Profile"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada no válidos"
     *     )
     * )
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));

        return response()->json($user);
    }

    /**
     * Eliminar la cuenta del usuario autenticado.
     *
     * @OA\Delete(
     *     path="/api/profile",
     *     summary="Eliminar la cuenta del usuario",
     *     tags={"Profile"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Contraseña incorrecta"
     *     )
     * )
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => ['password' => [__('The password is incorrect')]]
            ], 422);
        }

        $user->tokens()->forceDelete();
        $user->forceDelete();

        Auth::forgetGuards();

        return response()->json(['message' => __('User deleted successfully')]);
    }

    /**
     * Iniciar sesión y obtener el usuario autenticado.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => __('Incorrect credentials')], 401);
    }


    /**
     * Registrar un nuevo usuario.
     *
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de validación incorrectos"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = $user->createToken('user')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
