<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Cancha;
use App\Models\Sede;
use App\Models\Sponsor;
use App\Models\FranjaHoraria;
use App\Models\User;
use App\Models\Persona;
use App\Models\Partido;
use App\Services\FixtureGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminTorneoController extends Controller
{
    public function __construct(private FixtureGeneratorService $fixtureService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'sede_id' => 'required|uuid|exists:sedes,id',
            'categoria' => 'required|string',
            'descripcion' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'foto_url' => 'nullable|string',
            'formato_juego' => 'required|in:F5,F7,F8,F11',
            'max_equipos' => 'required|integer|min:2',
            'canchas_ids' => 'required|array',
            'canchas_ids.*' => 'uuid|exists:canchas,id',
        ]);

        $torneo = Torneo::create($validated);
        $torneo->canchas()->sync($validated['canchas_ids']);

        return response()->json([
            'torneo' => $torneo->load('canchas'),
            'mensaje' => 'Torneo publicado exitosamente por el Super Admin.',
        ], 201);
    }

    public function storeCancha(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'sede_id' => 'nullable|uuid|exists:sedes,id',
            'descripcion' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'foto_url' => 'nullable|string',
            'tipo_formato' => 'required|in:F5,F7,F8,F11',
            'superficie' => 'required|string',
            'precio_por_hora' => 'nullable|numeric',
            'tiene_iluminacion' => 'nullable|boolean',
            'es_techada' => 'nullable|boolean',
        ]);

        if (empty($validated['sede_id'])) {
            $sede = Sede::first();
            if (!$sede) {
                $sede = Sede::create([
                    'nombre' => 'Complejo Deportivo Central',
                    'direccion' => 'Av. Principal 1234',
                    'ciudad' => 'Buenos Aires',
                ]);
            }
            $validated['sede_id'] = $sede->id;
        }

        $cancha = Cancha::create($validated);

        return response()->json([
            'cancha' => $cancha->load('sede'),
            'mensaje' => 'Cancha creada exitosamente.',
        ], 201);
    }

    public function destroyCancha(Cancha $cancha): JsonResponse
    {
        $cancha->delete();
        return response()->json([
            'mensaje' => 'Cancha eliminada correctamente.',
        ]);
    }

    public function storeSponsor(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'marca' => 'required|string',
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'link_destino' => 'nullable|string',
            'posicion' => 'nullable|in:header,feed,footer,popup',
        ]);

        $sponsor = Sponsor::create($validated);

        return response()->json([
            'sponsor' => $sponsor,
            'mensaje' => 'Sponsor / Publicidad registrada exitosamente.',
        ], 201);
    }

    public function destroySponsor(Sponsor $sponsor): JsonResponse
    {
        $sponsor->delete();
        return response()->json([
            'mensaje' => 'Sponsor / Propaganda eliminada exitosamente.',
        ]);
    }

    public function addHorarios(Request $request, Torneo $torneo): JsonResponse
    {
        $validated = $request->validate([
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'duracion_partido_minutos' => 'nullable|integer',
        ]);

        $franja = FranjaHoraria::create(array_merge($validated, ['torneo_id' => $torneo->id]));
        return response()->json($franja, 201);
    }

    public function sortearFixture(Torneo $torneo): JsonResponse
    {
        try {
            $resultado = $this->fixtureService->sortearYGenerarFixture($torneo);
            return response()->json([
                'mensaje' => '🎉 ¡Sorteo completado y Fixture generado con éxito!',
                'resultado' => $resultado,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Listado de usuarios con personas para asignación de roles / árbitros
     */
    public function usuariosIndex(): JsonResponse
    {
        $usuarios = User::with('persona.fichaMedica')->orderBy('created_at', 'desc')->get();
        return response()->json($usuarios);
    }

    /**
     * Actualiza el rol de un usuario (ej: super_admin, arbitro, capitan, jugador)
     */
    public function actualizarRol(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:super_admin,organizador,capitan,jugador,arbitro',
        ]);

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'mensaje' => "Rol actualizado a {$validated['role']} con éxito.",
            'user' => $user->load('persona'),
        ]);
    }

    /**
     * Listado de partidos programados del sistema para asignar árbitros
     */
    public function partidosProgramados(): JsonResponse
    {
        $partidos = Partido::with(['torneo', 'cancha', 'equipoLocal', 'equipoVisitante', 'arbitro'])
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return response()->json($partidos);
    }

    /**
     * Asignar árbitro oficial a un partido
     */
    public function asignarArbitroPartido(Request $request, Partido $partido): JsonResponse
    {
        $validated = $request->validate([
            'arbitro_persona_id' => 'nullable|uuid|exists:personas,id',
        ]);

        $partido->update([
            'arbitro_persona_id' => $validated['arbitro_persona_id'] ?? null,
        ]);

        return response()->json([
            'mensaje' => 'Designación arbitral guardada exitosamente.',
            'partido' => $partido->load(['arbitro', 'equipoLocal', 'equipoVisitante', 'cancha']),
        ]);
    }
}
