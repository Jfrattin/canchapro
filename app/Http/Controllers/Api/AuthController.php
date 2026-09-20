<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email',
            'password' => 'required|min:6',
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'dni' => 'required|string',
            'grupo_sanguineo' => 'nullable|string',
            'contacto_emergencia' => 'nullable|string',
            'telefono' => 'nullable|string',
        ]);

        $result = $this->authService->register($validated);
        return response()->json($result, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($validated['email'], $validated['password']);
        return response()->json($result);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'persona' => $user->persona ? $user->persona->load('fichaMedica') : null,
        ]);
    }

    public function subirAptoMedico(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['error' => 'Perfil no encontrado.'], 404);
        }

        $validated = $request->validate([
            'observaciones_medicas' => 'nullable|string',
            'obra_social_prepaga' => 'nullable|string',
        ]);

        $ficha = \App\Models\FichaMedica::firstOrCreate(
            ['persona_id' => $persona->id],
            ['contacto_emergencia' => 'No especificado']
        );

        $ficha->update([
            'observaciones_medicas' => $validated['observaciones_medicas'] ?? 'Certificado presentado por el usuario.',
            'obra_social_prepaga' => $validated['obra_social_prepaga'] ?? $ficha->obra_social_prepaga,
        ]);

        return response()->json([
            'mensaje' => 'Certificado médico enviado. Pendiente de revisión por la Organización.',
            'persona' => $persona->load('fichaMedica'),
        ]);
    }
}
