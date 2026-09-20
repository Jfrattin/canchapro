<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EncuentroCasual;
use App\Models\EncuentroCasualJugador;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class EncuentroCasualController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = EncuentroCasual::with(['creador', 'equipoRival', 'jugadores.persona'])
            ->where('estado', '!=', 'CANCELADO');

        if ($request->has('deporte') && !empty($request->deporte) && $request->deporte !== 'TODOS') {
            $query->where('deporte', $request->deporte);
        }

        $encuentros = $query->orderBy('fecha_hora', 'asc')->get();

        return response()->json($encuentros);
    }

    public function store(Request $request): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['error' => 'Debes completar tu perfil para crear un partidito.'], 400);
        }

        $validated = $request->validate([
            'titulo' => 'required|string',
            'deporte' => 'nullable|string',
            'cancha_nombre' => 'required|string',
            'ubicacion' => 'required|string',
            'fecha_hora' => 'required|date',
            'formato' => 'required|string',
            'modalidad' => 'required|in:JUGADORES_SUELTOS,DESAFIO_EQUIPOS',
            'max_jugadores' => 'nullable|integer|min:2',
            'precio_total' => 'nullable|numeric|min:0',
            'precio_por_jugador' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $deporte = strtoupper($validated['deporte'] ?? 'FUTBOL');
        $formato = strtoupper($validated['formato']);

        $defaultMax = 10;
        if (in_array($formato, ['DOBLES', 'BEACH 2X2'])) $defaultMax = 4;
        elseif ($formato === 'SINGLES') $defaultMax = 2;
        elseif ($formato === '3X3') $defaultMax = 6;
        elseif ($formato === 'F7' || $formato === 'H7') $defaultMax = 14;
        elseif ($formato === 'F8') $defaultMax = 16;
        elseif ($formato === 'F11' || $formato === 'H11') $defaultMax = 22;
        elseif ($formato === '6X6') $defaultMax = 12;

        $maxJugadores = $validated['max_jugadores'] ?? $defaultMax;
        $precioTotal = $validated['precio_total'] ?? 0;
        $precioPorJugador = $validated['precio_por_jugador'] ?? ($maxJugadores > 0 ? round($precioTotal / $maxJugadores, 2) : 0);

        $encuentro = EncuentroCasual::create([
            'creador_persona_id' => $persona->id,
            'titulo' => $validated['titulo'],
            'deporte' => $deporte,
            'cancha_nombre' => $validated['cancha_nombre'],
            'ubicacion' => $validated['ubicacion'],
            'fecha_hora' => $validated['fecha_hora'],
            'formato' => $formato,
            'modalidad' => $validated['modalidad'],
            'max_jugadores' => $maxJugadores,
            'precio_total' => $precioTotal,
            'precio_por_jugador' => $precioPorJugador,
            'estado' => 'ABIERTO',
            'share_token' => Str::random(16),
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        // Inscribir automáticamente al creador en el Equipo 1
        EncuentroCasualJugador::create([
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $persona->id,
            'equipo_num' => 1,
            'asistencia_confirmada' => true,
        ]);

        return response()->json([
            'encuentro' => $encuentro->load(['creador', 'jugadores.persona']),
            'mensaje' => '¡Partidito casual creado con éxito! Comparte el link con tus amigos.',
        ], 201);
    }

    public function showByToken(string $token): JsonResponse
    {
        $encuentro = EncuentroCasual::with(['creador', 'equipoRival', 'jugadores.persona'])
            ->where('share_token', $token)
            ->first();

        if (!$encuentro) {
            return response()->json(['error' => 'Encuentro casual no encontrado o enlace expirado.'], 404);
        }

        return response()->json($encuentro);
    }

    public function unirse(Request $request, string $token): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['error' => 'Debes completar tu perfil para unirte al partidito.'], 400);
        }

        $encuentro = EncuentroCasual::where('share_token', $token)->first();
        if (!$encuentro) {
            return response()->json(['error' => 'Encuentro casual no encontrado.'], 404);
        }

        if ($encuentro->estado === 'CANCELADO') {
            return response()->json(['error' => 'Este partido ha sido cancelado.'], 400);
        }

        $validated = $request->validate([
            'equipo_num' => 'nullable|integer|in:1,2',
        ]);

        $equipoNum = $validated['equipo_num'] ?? 1;

        // Verificar si ya está anotado
        $existente = EncuentroCasualJugador::where('encuentro_casual_id', $encuentro->id)
            ->where('persona_id', $persona->id)
            ->first();

        if ($existente) {
            return response()->json([
                'mensaje' => 'Ya estás inscripto en este encuentro casual.',
                'encuentro' => $encuentro->load(['creador', 'equipoRival', 'jugadores.persona']),
            ]);
        }

        $cantJugadores = $encuentro->jugadores()->count();
        if ($cantJugadores >= $encuentro->max_jugadores) {
            return response()->json(['error' => 'Lo sentimos, las vacantes para este partidito ya están completas.'], 400);
        }

        EncuentroCasualJugador::create([
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $persona->id,
            'equipo_num' => $equipoNum,
            'asistencia_confirmada' => true,
        ]);

        if (($cantJugadores + 1) >= $encuentro->max_jugadores) {
            $encuentro->update(['estado' => 'CONFIRMADO']);
        }

        return response()->json([
            'mensaje' => "¡Te has unido exitosamente al partidito '{$encuentro->titulo}'!",
            'encuentro' => $encuentro->load(['creador', 'equipoRival', 'jugadores.persona']),
        ]);
    }

    public function desafiar(Request $request, string $token): JsonResponse
    {
        $persona = $request->user()->persona;
        if (!$persona) {
            return response()->json(['error' => 'Perfil no encontrado.'], 400);
        }

        $encuentro = EncuentroCasual::where('share_token', $token)->first();
        if (!$encuentro) {
            return response()->json(['error' => 'Encuentro no encontrado.'], 404);
        }

        $validated = $request->validate([
            'equipo_id' => 'nullable|uuid|exists:equipos,id',
            'equipo_nombre' => 'nullable|string',
        ]);

        $equipoRival = null;
        if (!empty($validated['equipo_id'])) {
            $equipoRival = Equipo::find($validated['equipo_id']);
        }

        $nombreRival = $equipoRival ? $equipoRival->nombre : ($validated['equipo_nombre'] ?? "Equipo de {$persona->nombre}");

        $encuentro->update([
            'equipo_rival_id' => $equipoRival ? $equipoRival->id : null,
            'equipo_rival_nombre' => $nombreRival,
            'modalidad' => 'DESAFIO_EQUIPOS',
            'estado' => 'CONFIRMADO',
        ]);

        // Registrar al usuario como jugador del Equipo 2
        EncuentroCasualJugador::firstOrCreate([
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $persona->id,
        ], [
            'equipo_num' => 2,
            'asistencia_confirmada' => true,
        ]);

        return response()->json([
            'mensaje' => "⚔️ ¡Desafío aceptado! {$nombreRival} jugará contra el equipo de {$encuentro->creador->nombre}.",
            'encuentro' => $encuentro->load(['creador', 'equipoRival', 'jugadores.persona']),
        ]);
    }

    public function destroy(Request $request, EncuentroCasual $encuentro): JsonResponse
    {
        $user = $request->user();
        $persona = $user->persona;
        $esAdmin = in_array($user->role, ['super_admin', 'organizador']);

        if (!$esAdmin && (!$persona || $encuentro->creador_persona_id !== $persona->id)) {
            return response()->json(['error' => 'Solo el creador del encuentro o un administrador puede eliminarlo.'], 403);
        }

        $encuentro->delete();
        return response()->json(['mensaje' => 'Partidito casual eliminado correctamente.']);
    }

    public function expulsarJugador(Request $request, EncuentroCasual $encuentro, string $personaId): JsonResponse
    {
        $user = $request->user();
        $persona = $user->persona;
        $esAdmin = in_array($user->role, ['super_admin', 'organizador']);

        if (!$esAdmin && (!$persona || $encuentro->creador_persona_id !== $persona->id)) {
            return response()->json(['error' => 'Solo el creador del encuentro o un administrador puede expulsar jugadores.'], 403);
        }

        $jugadorInscripto = EncuentroCasualJugador::where('encuentro_casual_id', $encuentro->id)
            ->where('persona_id', $personaId)
            ->first();

        if (!$jugadorInscripto) {
            return response()->json(['error' => 'El jugador no se encuentra inscripto en este partidito.'], 404);
        }

        $jugadorInscripto->delete();

        $cantRestantes = $encuentro->jugadores()->count();
        if ($cantRestantes < $encuentro->max_jugadores && $encuentro->estado === 'CONFIRMADO') {
            $encuentro->update(['estado' => 'ABIERTO']);
        }

        return response()->json([
            'mensaje' => 'Jugador eliminado del partidito.',
            'encuentro' => $encuentro->load(['creador', 'equipoRival', 'jugadores.persona']),
        ]);
    }
}
