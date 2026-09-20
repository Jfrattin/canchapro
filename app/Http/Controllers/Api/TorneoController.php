<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\TarjetaPartido;
use App\Services\EquipoService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TorneoController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private EquipoService $equipoService) {}

    public function index(Request $request): JsonResponse
    {
        $query = Torneo::with(['sede', 'canchas', 'equipos.personas']);

        if ($request->has('deporte') && !empty($request->deporte) && $request->deporte !== 'TODOS') {
            $query->where('deporte', $request->deporte);
        }

        if ($request->has('paginate') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 15);
            $torneos = $query->paginate($perPage);
        } else {
            $torneos = $query->get();
        }

        return response()->json($torneos);
    }

    public function show(Torneo $torneo): JsonResponse
    {
        $data = $torneo->load(['sede', 'canchas', 'franjasHorarias', 'listasBuenaFe.equipo']);
        return $this->successResponse($data, 'Detalle de torneo obtenido exitosamente.');
    }

    public function inscribirEquipo(Request $request, Torneo $torneo): JsonResponse
    {
        $validated = $request->validate([
            'equipo_id' => 'required|uuid|exists:equipos,id',
        ]);

        $equipo = Equipo::findOrFail($validated['equipo_id']);

        try {
            $listaFe = $this->equipoService->inscribirATorneo($equipo, $torneo);
            return $this->successResponse([
                'lista_fe' => $listaFe,
                'equipo' => $equipo->only(['id', 'nombre']),
            ], "Equipo {$equipo->nombre} inscripto exitosamente en el torneo {$torneo->nombre}.", 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function tablaPosiciones(Torneo $torneo): JsonResponse
    {
        // Cachear la tabla por 1 hora (se invalida automáticamente cuando se cierra un acta de partido)
        $cacheKey = "torneo:{$torneo->id}:tabla";

        $tablaData = Cache::remember($cacheKey, 3600, function () use ($torneo) {
            $equipos = $torneo->equipos;
            
            // Carga optimizada en batch (2 consultas en total para todo el torneo)
            $partidos = $torneo->partidos()
                ->whereIn('estado', ['FINALIZADO_EN_REVISION', 'CERRADO'])
                ->get();

            $partidosIds = $partidos->pluck('id');

            $tarjetas = TarjetaPartido::whereIn('partido_id', $partidosIds)
                ->get()
                ->groupBy('equipo_id');

            $stats = [];
            foreach ($equipos as $eq) {
                $stats[$eq->id] = [
                    'equipo_id' => $eq->id,
                    'equipo_nombre' => $eq->nombre,
                    'pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0,
                    'gf' => 0, 'gc' => 0, 'dg' => 0,
                    'amarillas' => 0, 'rojas' => 0,
                    'pts' => 0,
                ];
            }

            foreach ($partidos as $p) {
                $locId = $p->equipo_local_id;
                $visId = $p->equipo_visitante_id;
                $gLoc = (int) $p->goles_local;
                $gVis = (int) $p->goles_visitante;

                if (isset($stats[$locId])) {
                    $stats[$locId]['pj']++;
                    $stats[$locId]['gf'] += $gLoc;
                    $stats[$locId]['gc'] += $gVis;
                    if ($gLoc > $gVis) { $stats[$locId]['pg']++; $stats[$locId]['pts'] += 3; }
                    elseif ($gLoc === $gVis) { $stats[$locId]['pe']++; $stats[$locId]['pts'] += 1; }
                    else { $stats[$locId]['pp']++; }
                }

                if (isset($stats[$visId])) {
                    $stats[$visId]['pj']++;
                    $stats[$visId]['gf'] += $gVis;
                    $stats[$visId]['gc'] += $gLoc;
                    if ($gVis > $gLoc) { $stats[$visId]['pg']++; $stats[$visId]['pts'] += 3; }
                    elseif ($gVis === $gLoc) { $stats[$visId]['pe']++; $stats[$visId]['pts'] += 1; }
                    else { $stats[$visId]['pp']++; }
                }
            }

            // Calcular diferencia de gol y tarjetas por equipo
            foreach ($stats as $eqId => &$st) {
                $st['dg'] = $st['gf'] - $st['gc'];
                if (isset($tarjetas[$eqId])) {
                    $st['amarillas'] = $tarjetas[$eqId]->where('tipo_tarjeta', 'AMARILLA')->count();
                    $st['rojas'] = $tarjetas[$eqId]->where('tipo_tarjeta', 'ROJA')->count();
                }
            }
            unset($st);

            $tabla = array_values($stats);

            // Ordenar por Puntos DESC y luego por Diferencia de Gol DESC
            usort($tabla, function ($a, $b) {
                if ($a['pts'] === $b['pts']) {
                    return $b['dg'] <=> $a['dg'];
                }
                return $b['pts'] <=> $a['pts'];
            });

            return [
                'torneo' => $torneo->only(['id', 'nombre', 'categoria', 'formato_juego', 'foto_url', 'ubicacion']),
                'tabla' => $tabla,
                'total_equipos' => count($tabla),
            ];
        });

        return $this->successResponse($tablaData, 'Tabla de posiciones obtenida con éxito.');
    }
}
