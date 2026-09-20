<?php

namespace App\Services;

use App\Models\User;
use App\Models\Persona;
use App\Models\FichaMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthService
{
    /**
     * Registro con Patrón Trinidad + Evaluación de Lazy Linking por DNI
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $dni = preg_replace('/[^0-9]/', '', $data['dni']);

            // 1. Crear Usuario (Credenciales)
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? 'jugador',
            ]);

            // 2. Crear Persona (Datos Civiles del Patrón Trinidad)
            $persona = Persona::create([
                'user_id' => $user->id,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'dni' => $dni,
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'telefono' => $data['telefono'] ?? null,
            ]);

            // 3. Crear Ficha Médica (Pendiente de Aprobación por Admin)
            $ficha = FichaMedica::create([
                'persona_id' => $persona->id,
                'grupo_sanguineo' => $data['grupo_sanguineo'] ?? 'O+',
                'apto_fisico_aprobado' => $data['apto_fisico_aprobado'] ?? false,
                'fecha_vencimiento' => Carbon::now()->addYear(),
                'contacto_emergencia' => $data['contacto_emergencia'] ?? 'No especificado',
                'obra_social_prepaga' => $data['obra_social_prepaga'] ?? 'Particular',
            ]);

            return [
                'user' => $user,
                'persona' => $persona->load('fichaMedica'),
                'token' => 'jwt_token_' . $user->id,
            ];
        });
    }

    public function login(string $emailOrDni, string $password): array
    {
        $user = User::where('email', $emailOrDni)->first();

        // Si no encontró por email, busca por DNI en Persona
        if (!$user) {
            $dni = preg_replace('/[^0-9]/', '', $emailOrDni);
            $persona = Persona::where('dni', $dni)->first();
            if ($persona && $persona->user) {
                $user = $persona->user;
            }
        }

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        return [
            'user' => $user,
            'persona' => $user->persona ? $user->persona->load('fichaMedica') : null,
            'token' => 'jwt_token_' . $user->id,
        ];
    }
}
