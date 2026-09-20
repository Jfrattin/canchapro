<?php

namespace App\Services;

use App\Models\Torneo;
use App\Models\Partido;
use App\Models\Cancha;
use App\Models\FranjaHoraria;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FixtureGeneratorService
{
    /**
     * Sorteo y Generador de Enfrentamientos Automático (Algoritmo Round Robin Berger)
     */
    public function sortearYGenerarFixture(Torneo $torneo): array
    {
        return DB::transaction(function () use ($torneo) {
            $equipos = $torneo->equipos()->get()->shuffle(); // Sorteo aleatorio inicial
            $numEquipos = $equipos->count();

            if ($numEquipos < 2) {
                throw new \Exception("Se necesitan al menos 2 equipos para generar el fixture.");
            }

            $canchas = $torneo->canchas()->get();
            if ($canchas->isEmpty()) {
                throw new \Exception("El torneo no tiene canchas habilitadas asignadas.");
            }

            $franjas = $torneo->franjasHorarias()->get();
            if ($franjas->isEmpty()) {
                // Crear franja por defecto si no existe (Domingos 14:00 a 22:00)
                $franjas = collect([
                    FranjaHoraria::create([
                        'torneo_id' => $torneo->id,
                        'dia_semana' => 'Domingo',
                        'hora_inicio' => '14:00:00',
                        'hora_fin' => '22:00:00',
                        'duracion_partido_minutos' => 60,
                    ])
                ]);
            }

            // Si es impar, agregar equipo "Libre" (Dummy)
            $esImpar = ($numEquipos % 2 !== 0);
            $listaIds = $equipos->pluck('id')->toArray();
            if ($esImpar) {
                $listaIds[] = null; // Representa fecha libre
                $numEquipos++;
            }

            $totalJornadas = $numEquipos - 1;
            $partidosPorJornada = $numEquipos / 2;
            $partidosCreados = [];

            // Fecha base de inicio (Próximo domingo a las 14:00)
            $fechaBaseJornada = Carbon::now()->next(Carbon::SUNDAY);

            // Algoritmo Round-Robin
            for ($jornada = 1; $jornada <= $totalJornadas; $jornada++) {
                $fechaJornadaActual = $fechaBaseJornada->copy()->addWeeks($jornada - 1);
                $slotHorarioIndex = 0;
                $canchaIndex = 0;

                for ($match = 0; $match < $partidosPorJornada; $match++) {
                    $localId = $listaIds[$match];
                    $visitanteId = $listaIds[$numEquipos - 1 - $match];

                    // Si alguno es NULL, es fecha libre
                    if (!$localId || !$visitanteId) {
                        continue;
                    }

                    // Alternar localía
                    if ($jornada % 2 === 0) {
                        $temp = $localId;
                        $localId = $visitanteId;
                        $visitanteId = $temp;
                    }

                    // Asignar Cancha y Horario
                    $canchaAsignada = $canchas[$canchaIndex % $canchas->count()];
                    
                    // Calcular hora del partido
                    $horaInicio = Carbon::parse('14:00:00')->addMinutes($slotHorarioIndex * 60);
                    $fechaHoraPartido = $fechaJornadaActual->copy()->setTimeFromTimeString($horaInicio->toTimeString());

                    $partido = Partido::create([
                        'torneo_id' => $torneo->id,
                        'jornada' => $jornada,
                        'equipo_local_id' => $localId,
                        'equipo_visitante_id' => $visitanteId,
                        'cancha_id' => $canchaAsignada->id,
                        'fecha_hora' => $fechaHoraPartido,
                        'estado' => 'PROGRAMADO',
                    ]);

                    $partidosCreados[] = $partido;

                    $canchaIndex++;
                    if ($canchaIndex % $canchas->count() === 0) {
                        $slotHorarioIndex++;
                    }
                }

                // Rotación de equipos para la siguiente fecha (fijando el primer equipo)
                $ultimo = array_pop($listaIds);
                array_splice($listaIds, 1, 0, [$ultimo]);
            }

            // Actualizar estado del Torneo a EN_JUEGO
            $torneo->update([
                'estado' => 'EN_JUEGO',
                'fecha_inicio' => $fechaBaseJornada,
                'fecha_fin' => $fechaBaseJornada->copy()->addWeeks($totalJornadas),
            ]);

            return [
                'torneo' => $torneo,
                'total_jornadas' => $totalJornadas,
                'total_partidos' => count($partidosCreados),
                'partidos' => $partidosCreados,
            ];
        });
    }
}
