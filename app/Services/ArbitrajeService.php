<?php

namespace App\Services;

use App\Models\Partido;
use App\Models\Persona;
use App\Models\ActaPartido;
use App\Models\GolPartido;
use App\Models\TarjetaPartido;
use App\Models\ReclamoGol;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArbitrajeService
{
    /**
     * Cierre de Partido por el Árbitro (Acta Digital Oficial)
     */
    public function cerrarPartido(Partido $partido, Persona $arbitro, array $actaData): ActaPartido
    {
        return DB::transaction(function () use ($partido, $arbitro, $actaData) {
            $golesLocal = (int) $actaData['goles_local'];
            $golesVisitante = (int) $actaData['goles_visitante'];

            // 1. Actualizar estado del Partido a CERRADO
            $partido->update([
                'goles_local' => $golesLocal,
                'goles_visitante' => $golesVisitante,
                'arbitro_persona_id' => $arbitro->id,
                'estado' => 'CERRADO',
            ]);

            // 2. Crear Acta Digital
            $acta = ActaPartido::create([
                'partido_id' => $partido->id,
                'arbitro_persona_id' => $arbitro->id,
                'fecha_cierre' => Carbon::now(),
                'firma_digital_hash' => hash('sha256', "ACTA_{$partido->id}_{$arbitro->id}_" . time()),
                'informe_arbitral' => $actaData['observaciones'] ?? 'Partido finalizado reglamentariamente.',
            ]);

            // 3. Registrar Goles (Con autor o "Sin Dueño")
            if (!empty($actaData['goles'])) {
                foreach ($actaData['goles'] as $gol) {
                    GolPartido::create([
                        'acta_partido_id' => $acta->id,
                        'partido_id' => $partido->id,
                        'equipo_id' => $gol['equipo_id'],
                        'autor_persona_id' => $gol['autor_persona_id'] ?? null, // NULL = Sin Dueño
                        'minuto' => $gol['minuto'] ?? 0,
                    ]);
                }
            }

            // 4. Registrar Tarjetas
            if (!empty($actaData['tarjetas'])) {
                foreach ($actaData['tarjetas'] as $t) {
                    TarjetaPartido::create([
                        'acta_partido_id' => $acta->id,
                        'partido_id' => $partido->id,
                        'persona_id' => $t['persona_id'],
                        'equipo_id' => $t['equipo_id'],
                        'tipo_tarjeta' => $t['tipo'] ?? 'AMARILLA',
                        'minuto' => $t['minuto'] ?? 0,
                        'motivo' => $t['motivo'] ?? null,
                    ]);
                }
            }

            return $acta->load(['goles', 'tarjetas']);
        });
    }

    /**
     * Capitán reclama un Gol Sin Dueño
     */
    public function reclamarGolSinDueño(GolPartido $gol, Persona $capitanSolicitante, Persona $jugadorAutor, Persona $capitanRival): ReclamoGol
    {
        if ($gol->tieneAutorAsignado()) {
            throw new \Exception("Este gol ya tiene un autor asignado oficialmente.");
        }

        return ReclamoGol::create([
            'gol_partido_id' => $gol->id,
            'capitan_solicitante_id' => $capitanSolicitante->id,
            'jugador_reclamado_id' => $jugadorAutor->id,
            'capitan_validador_id' => $capitanRival->id,
            'estado' => 'PENDIENTE',
            'fecha_limite_timeout' => Carbon::now()->addHours(48),
        ]);
    }

    /**
     * Capitán Rival Valida / Aprueba el Gol (Fair Play)
     */
    public function responderReclamoRival(ReclamoGol $reclamo, bool $aprobar): ReclamoGol
    {
        return DB::transaction(function () use ($reclamo, $aprobar) {
            if ($aprobar) {
                $reclamo->update([
                    'estado' => 'APROBADO_RIVAL',
                    'fecha_resolucion' => Carbon::now(),
                ]);

                // Asignar el autor al gol oficial
                $reclamo->gol->update([
                    'autor_persona_id' => $reclamo->jugador_reclamado_id,
                    'es_reclamado_por_capitan' => true,
                ]);
            } else {
                $reclamo->update([
                    'estado' => 'RECHAZADO_TRIBUNAL',
                    'fecha_resolucion' => Carbon::now(),
                ]);
            }

            return $reclamo;
        });
    }
}
