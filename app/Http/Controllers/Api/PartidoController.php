<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partido;
use App\Models\Equipo;
use App\Services\AsistenciaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PartidoController extends Controller
{
    public function __construct(private AsistenciaService $asistenciaService) {}

    /**
     * Devuelve "Tu Próximo Partido" para el jugador/capitán logueado
     */
    public function proximoPartido(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;

        if (!$persona) {
            return response()->json(['mensaje' => 'No hay ficha de persona asociada'], 404);
        }

        // Buscar equipos donde juega o es capitán
        $equiposIds = $persona->listaFeInscripciones()->with('listaBuenaFe')->get()->pluck('listaBuenaFe.equipo_id')->unique();

        $partido = Partido::where(function ($q) use ($equiposIds) {
                $q->whereIn('equipo_local_id', $equiposIds)
                  ->orWhereIn('equipo_visitante_id', $equiposIds);
            })
            ->where('estado', 'PROGRAMADO')
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->with(['equipoLocal', 'equipoVisitante', 'cancha.sede', 'arbitro', 'asistencias.persona'])
            ->first();

        if (!$partido) {
            return response()->json(['mensaje' => 'No tienes partidos programados próximamente']);
        }

        // Buscar si esta persona ya confirmó o rechazó asistencia para este partido
        $miAsistencia = $partido->asistencias()->where('persona_id', $persona->id)->first();

        $data = $partido->toArray();
        $data['mi_asistencia'] = $miAsistencia ? [
            'estado_asistencia' => $miAsistencia->estado_asistencia, // CONFIRMADO_ASISTE, CONFIRMADO_NO_ASISTE
            'respondido' => in_array($miAsistencia->estado_asistencia, ['CONFIRMADO_ASISTE', 'CONFIRMADO_NO_ASISTE']),
            'asiste' => $miAsistencia->estado_asistencia === 'CONFIRMADO_ASISTE',
            'fecha_confirmacion' => $miAsistencia->fecha_confirmacion,
        ] : [
            'estado_asistencia' => 'PENDIENTE',
            'respondido' => false,
            'asiste' => false,
        ];

        return response()->json($data);
    }

    /**
     * Listar notificaciones del usuario (avisos de bajas para el capitán)
     */
    public function misNotificaciones(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json([]);
        }

        $notificaciones = \App\Models\Notificacion::where('persona_id', $persona->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return response()->json($notificaciones);
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarNotificacionLeida(Request $request, \App\Models\Notificacion $notificacion): JsonResponse
    {
        $persona = $request->user()->persona;
        if ($notificacion->persona_id === $persona->id) {
            $notificacion->update(['leida' => true]);
        }

        return response()->json(['mensaje' => 'Notificación leída']);
    }

    /**
     * El jugador marca asistencia: [ ✅ Asistiré / ❌ No podré ir ]
     */
    public function marcarAsistencia(Request $request, Partido $partido): JsonResponse
    {
        $validated = $request->validate([
            'equipo_id' => 'required|uuid|exists:equipos,id',
            'asistira' => 'required|boolean',
        ]);

        $persona = $request->user()->persona;
        $equipo = Equipo::findOrFail($validated['equipo_id']);

        $asistencia = $this->asistenciaService->marcarAsistencia($partido, $persona, $equipo, $validated['asistira']);

        return response()->json([
            'asistencia' => $asistencia,
            'mensaje' => $validated['asistira'] ? '¡Asistencia confirmada!' : 'Has informado que no podrás asistir.',
        ]);
    }

    /**
     * El Capitán confirma los 11 titulares definitivos
     */
    public function confirmarConvocatoria11(Request $request, Partido $partido): JsonResponse
    {
        $validated = $request->validate([
            'equipo_id' => 'required|uuid|exists:equipos,id',
            'jugadores_ids' => 'required|array|size:11',
            'jugadores_ids.*' => 'uuid|exists:personas,id',
        ]);

        $equipo = Equipo::findOrFail($validated['equipo_id']);

        try {
            $resultado = $this->asistenciaService->confirmarConvocatoria11($partido, $equipo, $validated['jugadores_ids']);
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
