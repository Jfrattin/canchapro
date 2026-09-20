<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partido;
use App\Models\GolPartido;
use App\Models\Persona;
use App\Models\ReclamoGol;
use App\Services\ArbitrajeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArbitroController extends Controller
{
    public function __construct(private ArbitrajeService $arbitrajeService) {}

    /**
     * Partidos asignados al árbitro autenticado (con planteles completos para cargar goles y tarjetas)
     */
    public function misPartidosAsignados(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['partidos' => []]);
        }

        $partidos = Partido::where('arbitro_persona_id', $persona->id)
            ->with([
                'torneo',
                'cancha.sede',
                'equipoLocal.listasBuenaFe.jugadores.persona',
                'equipoVisitante.listasBuenaFe.jugadores.persona',
                'acta.goles.autor',
                'acta.tarjetas.persona',
            ])
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // Formatear partidos con lista de jugadores por equipo
        $resultado = $partidos->map(function ($partido) {
            $jugadoresLocal = collect();
            if ($partido->equipoLocal) {
                foreach ($partido->equipoLocal->listasBuenaFe as $lb) {
                    foreach ($lb->jugadores as $j) {
                        if ($j->persona) {
                            $jugadoresLocal->push([
                                'id' => $j->persona->id,
                                'nombre' => $j->persona->nombre,
                                'apellido' => $j->persona->apellido,
                                'dorsal' => $j->dorsal,
                                'posicion' => $j->posicion,
                            ]);
                        }
                    }
                }
            }

            $jugadoresVisitante = collect();
            if ($partido->equipoVisitante) {
                foreach ($partido->equipoVisitante->listasBuenaFe as $lb) {
                    foreach ($lb->jugadores as $j) {
                        if ($j->persona) {
                            $jugadoresVisitante->push([
                                'id' => $j->persona->id,
                                'nombre' => $j->persona->nombre,
                                'apellido' => $j->persona->apellido,
                                'dorsal' => $j->dorsal,
                                'posicion' => $j->posicion,
                            ]);
                        }
                    }
                }
            }

            return [
                'id' => $partido->id,
                'jornada' => $partido->jornada,
                'torneo' => $partido->torneo ? $partido->torneo->only(['id', 'nombre', 'categoria', 'formato_juego']) : null,
                'cancha' => $partido->cancha ? $partido->cancha->only(['id', 'nombre', 'ubicacion', 'foto_url']) : null,
                'fecha_hora' => $partido->fecha_hora,
                'estado' => $partido->estado,
                'goles_local' => $partido->goles_local,
                'goles_visitante' => $partido->goles_visitante,
                'equipo_local' => [
                    'id' => $partido->equipoLocal->id ?? null,
                    'nombre' => $partido->equipoLocal->nombre ?? 'Local',
                    'jugadores' => $jugadoresLocal->unique('id')->values(),
                ],
                'equipo_visitante' => [
                    'id' => $partido->equipoVisitante->id ?? null,
                    'nombre' => $partido->equipoVisitante->nombre ?? 'Visitante',
                    'jugadores' => $jugadoresVisitante->unique('id')->values(),
                ],
                'acta' => $partido->acta,
            ];
        });

        return response()->json([
            'arbitro' => [
                'id' => $persona->id,
                'nombre' => $persona->nombre,
                'apellido' => $persona->apellido,
            ],
            'partidos' => $resultado,
        ]);
    }

    /**
     * Cierre de Acta Digital por el Árbitro Oficial
     */
    public function cerrarActa(Request $request, Partido $partido): JsonResponse
    {
        $validated = $request->validate([
            'goles_local' => 'required|integer|min:0',
            'goles_visitante' => 'required|integer|min:0',
            'observaciones' => 'nullable|string',
            'goles' => 'nullable|array',
            'tarjetas' => 'nullable|array',
        ]);

        $arbitro = $request->user()->persona;

        $acta = $this->arbitrajeService->cerrarPartido($partido, $arbitro, $validated);

        if ($partido->torneo_id) {
            \Illuminate\Support\Facades\Cache::forget("torneo:{$partido->torneo_id}:tabla");
        }

        return response()->json([
            'acta' => $acta,
            'mensaje' => '🔒 Partido cerrado y acta digital firmada oficialmente.',
        ]);
    }

    /**
     * Capitán reclama un Gol Sin Dueño
     */
    public function reclamarGol(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gol_id' => 'required|uuid|exists:goles_partidos,id',
            'jugador_autor_id' => 'required|uuid|exists:personas,id',
            'capitan_rival_id' => 'required|uuid|exists:personas,id',
        ]);

        $capitan = $request->user()->persona;
        $gol = GolPartido::findOrFail($validated['gol_id']);
        $autor = Persona::findOrFail($validated['jugador_autor_id']);
        $rival = Persona::findOrFail($validated['capitan_rival_id']);

        try {
            $reclamo = $this->arbitrajeService->reclamarGolSinDueño($gol, $capitan, $autor, $rival);
            return response()->json([
                'reclamo' => $reclamo,
                'mensaje' => 'Solicitud de reclamo enviada al Capitán Rival. Tiene 48h para responder.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Capitán Rival responde (Aprueba / Rechaza)
     */
    public function responderReclamo(Request $request, ReclamoGol $reclamo): JsonResponse
    {
        $validated = $request->validate([
            'aprobar' => 'required|boolean',
        ]);

        $reclamoActualizado = $this->arbitrajeService->responderReclamoRival($reclamo, $validated['aprobar']);

        return response()->json([
            'reclamo' => $reclamoActualizado,
            'mensaje' => $validated['aprobar'] ? '🤝 ¡Gol aprobado en Fair Play!' : 'Reclamo rechazado.',
        ]);
    }
}
