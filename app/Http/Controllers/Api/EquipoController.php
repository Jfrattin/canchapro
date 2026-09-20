<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EquipoService;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EquipoController extends Controller
{
    public function __construct(private EquipoService $equipoService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'color_primario' => 'nullable|string',
            'color_secundario' => 'nullable|string',
        ]);

        $persona = $request->user()->persona;
        $equipo = $this->equipoService->crearEquipo($persona, $validated);

        return response()->json([
            'equipo' => $equipo,
            'invite_url' => $equipo->invite_url,
            'mensaje' => 'Equipo creado exitosamente. Comparte el link con tus jugadores.',
        ], 201);
    }

    public function joinByLink(Request $request, string $token): JsonResponse
    {
        $persona = $request->user()->persona;
        $datos = $request->only(['dorsal', 'posicion']);

        try {
            $result = $this->equipoService->unirsePorInviteLink($token, $persona, $datos);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function showRoster(Equipo $equipo): JsonResponse
    {
        $listas = $equipo->listasBuenaFe()->with(['jugadores.persona.fichaMedica', 'torneo'])->get();
        return response()->json([
            'equipo' => $equipo,
            'listas_fe' => $listas,
        ]);
    }

    /**
     * Obtiene el equipo activo del usuario, sus compañeros de lista de buena fe y capitán
     */
    public function miEquipo(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['mensaje' => 'No tienes perfil de persona asociado.'], 404);
        }

        // Buscar primero si es capitán de algún equipo
        $equipo = $persona->equiposCapitan()->latest()->first();

        // Si no es capitán, buscar en qué equipo está inscripto como jugador
        if (!$equipo) {
            $inscripcion = $persona->listaFeInscripciones()->with('listaBuenaFe.equipo')->first();
            if ($inscripcion && $inscripcion->listaBuenaFe) {
                $equipo = $inscripcion->listaBuenaFe->equipo;
            }
        }

        if (!$equipo) {
            return response()->json([
                'equipo' => null,
                'mensaje' => 'Aún no perteneces a ningún equipo. Crea tu equipo o únete con un link de invitación.'
            ]);
        }

        // Cargar capitán
        $equipo->load('capitan');

        // Cargar todas las listas de buena fe donde juega el equipo y sus compañeros
        $listas = $equipo->listasBuenaFe()->with(['torneo', 'jugadores.persona.fichaMedica'])->get();

        // Consolidar jugadores únicos del plantel
        $plantel = [];
        $idsRegistrados = [];

        // Asegurar que el capitán figure en el plantel
        if ($equipo->capitan) {
            $plantel[] = [
                'persona_id' => $equipo->capitan->id,
                'nombre' => $equipo->capitan->nombre,
                'apellido' => $equipo->capitan->apellido,
                'dni' => $equipo->capitan->dni,
                'rol' => 'Capitán',
                'dorsal' => 10,
                'posicion' => 'Delantero',
                'apto_medico' => $equipo->capitan->fichaMedica ? $equipo->capitan->fichaMedica->apto_fisico_aprobado : false,
                'es_tu_usuario' => $equipo->capitan->id === $persona->id,
            ];
            $idsRegistrados[] = $equipo->capitan->id;
        }

        foreach ($listas as $lista) {
            foreach ($lista->jugadores as $j) {
                if (!in_array($j->persona_id, $idsRegistrados) && $j->persona) {
                    $plantel[] = [
                        'persona_id' => $j->persona->id,
                        'nombre' => $j->persona->nombre,
                        'apellido' => $j->persona->apellido,
                        'dni' => $j->persona->dni,
                        'rol' => ($j->persona->id === $equipo->capitan_persona_id) ? 'Capitán' : 'Jugador',
                        'dorsal' => $j->dorsal,
                        'posicion' => $j->posicion,
                        'apto_medico' => $j->persona->fichaMedica ? $j->persona->fichaMedica->apto_fisico_aprobado : false,
                        'es_tu_usuario' => $j->persona->id === $persona->id,
                    ];
                    $idsRegistrados[] = $j->persona_id;
                }
            }
        }

        // Historial y fixture de partidos del equipo
        $partidosEquipo = \App\Models\Partido::where(function ($q) use ($equipo) {
                $q->where('equipo_local_id', $equipo->id)
                  ->orWhere('equipo_visitante_id', $equipo->id);
            })
            ->with(['equipoLocal', 'equipoVisitante', 'cancha.sede', 'torneo'])
            ->orderBy('fecha_hora', 'desc')
            ->get()
            ->map(function ($p) use ($equipo) {
                $esLocal = $p->equipo_local_id === $equipo->id;
                $rival = $esLocal ? $p->equipoVisitante : $p->equipoLocal;
                $golesFavor = $esLocal ? $p->goles_local : $p->goles_visitante;
                $golesContra = $esLocal ? $p->goles_visitante : $p->goles_local;

                $resultado = 'PENDIENTE';
                if (in_array($p->estado, ['FINALIZADO_EN_REVISION', 'CERRADO'])) {
                    if ($golesFavor > $golesContra) $resultado = 'VICTORIA';
                    elseif ($golesFavor === $golesContra) $resultado = 'EMPATE';
                    else $resultado = 'DERROTA';
                }

                return [
                    'id' => $p->id,
                    'torneo_nombre' => $p->torneo ? $p->torneo->nombre : 'Torneo',
                    'jornada' => $p->jornada,
                    'fecha_hora' => $p->fecha_hora,
                    'cancha_nombre' => $p->cancha ? $p->cancha->nombre : 'Cancha',
                    'cancha_ubicacion' => $p->cancha ? ($p->cancha->ubicacion ?: ($p->cancha->sede ? $p->cancha->sede->direccion : 'Predio Central')) : 'Predio',
                    'condicion' => $esLocal ? 'LOCAL' : 'VISITANTE',
                    'rival_nombre' => $rival ? $rival->nombre : 'Rival',
                    'goles_favor' => $golesFavor,
                    'goles_contra' => $golesContra,
                    'estado' => $p->estado,
                    'resultado' => $resultado,
                ];
            });

        return response()->json([
            'equipo' => $equipo,
            'es_capitan' => $equipo->capitan_persona_id === $persona->id,
            'plantel' => $plantel,
            'total_jugadores' => count($plantel),
            'torneos' => $listas->pluck('torneo')->filter()->values(),
            'historial_partidos' => $partidosEquipo,
        ]);
    }

    /**
     * Devuelve las estadísticas individuales del jugador logueado
     */
    public function misEstadisticas(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['mensaje' => 'No se encontró perfil civil.'], 404);
        }

        // Partidos jugados/convocados
        $partidosJugados = \App\Models\AsistenciaPartido::where('persona_id', $persona->id)
            ->where('estado_asistencia', 'CONFIRMADO_ASISTE')
            ->count();

        // Goles anotados
        $golesAnotados = \App\Models\GolPartido::where('autor_persona_id', $persona->id)
            ->where('es_gol_en_contra', false)
            ->count();

        // Tarjetas recibidas
        $tarjetasAmarillas = \App\Models\TarjetaPartido::where('persona_id', $persona->id)
            ->where('tipo_tarjeta', 'AMARILLA')
            ->count();

        $tarjetasRojas = \App\Models\TarjetaPartido::where('persona_id', $persona->id)
            ->where('tipo_tarjeta', 'ROJA')
            ->count();

        // Asistencias confirmadas
        $asistenciasConfirmadas = \App\Models\AsistenciaPartido::where('persona_id', $persona->id)
            ->where('estado_asistencia', 'CONFIRMADO_ASISTE')
            ->count();

        $inasistencias = \App\Models\AsistenciaPartido::where('persona_id', $persona->id)
            ->where('estado_asistencia', 'CONFIRMADO_NO_ASISTE')
            ->count();

        return response()->json([
            'persona' => [
                'nombre' => $persona->nombre,
                'apellido' => $persona->apellido,
                'dni' => $persona->dni,
                'ficha_medica' => $persona->fichaMedica,
            ],
            'estadisticas' => [
                'partidos_jugados' => $partidosJugados,
                'goles_totales' => $golesAnotados,
                'tarjetas_amarillas' => $tarjetasAmarillas,
                'tarjetas_rojas' => $tarjetasRojas,
                'asistencias_si' => $asistenciasConfirmadas,
                'asistencias_no' => $inasistencias,
                'promedio_gol' => $partidosJugados > 0 ? round($golesAnotados / $partidosJugados, 2) : 0,
            ]
        ]);
    }
}
