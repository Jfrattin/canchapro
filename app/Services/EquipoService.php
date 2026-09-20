<?php

namespace App\Services;

use App\Models\Equipo;
use App\Models\Persona;
use App\Models\ListaBuenaFe;
use App\Models\ListaFeJugador;
use App\Models\Torneo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EquipoService
{
    /**
     * Crear un nuevo equipo y designar al Capitán
     */
    public function crearEquipo(Persona $capitan, array $data): Equipo
    {
        return DB::transaction(function () use ($capitan, $data) {
            $equipo = Equipo::create([
                'nombre' => $data['nombre'],
                'capitan_persona_id' => $capitan->id,
                'color_primario' => $data['color_primario'] ?? '#00ff88',
                'color_secundario' => $data['color_secundario'] ?? '#00e5ff',
                'logo_url' => $data['logo_url'] ?? null,
            ]);

            // Actualizar el rol del usuario a 'capitan'
            if ($capitan->user && $capitan->user->role !== 'super_admin') {
                $capitan->user->update(['role' => 'capitan']);
            }

            return $equipo;
        });
    }

    /**
     * Unirse a un equipo mediante el Link de Invitación (/join/{token})
     */
    public function unirsePorInviteLink(string $token, Persona $persona, array $datosJugador = []): array
    {
        return DB::transaction(function () use ($token, $persona, $datosJugador) {
            $equipo = Equipo::where('invite_token', $token)->first();

            if (!$equipo) {
                throw new \Exception("El link de invitación no es válido o ha expirado.");
            }

            // Buscar si el equipo está inscripto en algún torneo, si no, crear lista previa
            $listasFe = $equipo->listasBuenaFe;
            if ($listasFe->isEmpty()) {
                $defaultLista = ListaBuenaFe::firstOrCreate([
                    'torneo_id' => null,
                    'equipo_id' => $equipo->id,
                ]);
                $listasFe = collect([$defaultLista]);
            }

            $agregadoEnListas = 0;

            foreach ($listasFe as $listaFe) {
                // Verificar que no supere el cupo máximo de 20 jugadores
                if ($listaFe->jugadores()->count() >= 20) {
                    continue;
                }

                // Inscribir al jugador en la Lista de Buena Fe
                ListaFeJugador::firstOrCreate(
                    [
                        'lista_buena_fe_id' => $listaFe->id,
                        'persona_id' => $persona->id,
                    ],
                    [
                        'dorsal' => $datosJugador['dorsal'] ?? rand(2, 99),
                        'posicion' => $datosJugador['posicion'] ?? 'Mediocampista',
                        'estado_habilitacion' => 'HABILITADO',
                    ]
                );
                $agregadoEnListas++;
            }

            return [
                'equipo' => $equipo,
                'persona' => $persona,
                'listas_incorporadas' => $agregadoEnListas,
                'mensaje' => "¡Te has unido exitosamente a {$equipo->nombre}!",
            ];
        });
    }

    /**
     * Inscribir equipo a un Torneo
     */
    public function inscribirATorneo(Equipo $equipo, Torneo $torneo): ListaBuenaFe
    {
        return DB::transaction(function () use ($equipo, $torneo) {
            if (!$torneo->tieneCuposDisponibles()) {
                throw new \Exception("El torneo no tiene cupos disponibles.");
            }

            $listaFe = ListaBuenaFe::firstOrCreate([
                'torneo_id' => $torneo->id,
                'equipo_id' => $equipo->id,
            ]);

            // Auto-inscribir al Capitán en la Lista de Buena Fe
            ListaFeJugador::firstOrCreate(
                [
                    'lista_buena_fe_id' => $listaFe->id,
                    'persona_id' => $equipo->capitan_persona_id,
                ],
                [
                    'dorsal' => 10,
                    'posicion' => 'Delantero',
                    'estado_habilitacion' => 'HABILITADO',
                ]
            );

            // Si se llenó el cupo del torneo, actualizar estado
            if ($torneo->listasBuenaFe()->count() >= $torneo->max_equipos) {
                $torneo->update(['estado' => 'CUPOS_COMPLETOS']);
            }

            return $listaFe;
        });
    }
}
