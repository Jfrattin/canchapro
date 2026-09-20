<?php

namespace App\Services;

use App\Models\Partido;
use App\Models\Persona;
use App\Models\Equipo;
use App\Models\AsistenciaPartido;
use App\Models\ConvocatoriaPartido;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Notificacion;

class AsistenciaService
{
    /**
     * El jugador confirma si asistirá o no al partido
     */
    public function marcarAsistencia(Partido $partido, Persona $persona, Equipo $equipo, bool $asistira): AsistenciaPartido
    {
        $asistencia = AsistenciaPartido::updateOrCreate(
            [
                'partido_id' => $partido->id,
                'persona_id' => $persona->id,
            ],
            [
                'equipo_id' => $equipo->id,
                'estado_asistencia' => $asistira ? 'CONFIRMADO_ASISTE' : 'CONFIRMADO_NO_ASISTE',
                'fecha_confirmacion' => Carbon::now(),
            ]
        );

        // Notificar al capitán del equipo si quien marca no es el propio capitán
        if ($equipo->capitan_persona_id && $equipo->capitan_persona_id !== $persona->id) {
            $nombreJugador = $persona->nombre . ' ' . $persona->apellido;
            $titulo = $asistira ? "✅ Jugador confirmó asistencia: {$nombreJugador}" : "⚠️ Jugador NO asistirá: {$nombreJugador}";
            $mensaje = $asistira 
                ? "El jugador {$nombreJugador} confirmó que jugará el partido vs " . ($partido->equipo_local_id === $equipo->id ? ($partido->equipoVisitante->nombre ?? 'Rival') : ($partido->equipoLocal->nombre ?? 'Rival')) . "."
                : "¡Atención Capitán! {$nombreJugador} no podrá ir al próximo partido. Por favor realiza el cambio en tu alineación o convoca a otro jugador.";

            Notificacion::create([
                'persona_id' => $equipo->capitan_persona_id,
                'tipo' => $asistira ? 'ASISTENCIA_CONFIRMADA' : 'BAJA_JUGADOR',
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'datos_extra' => [
                    'partido_id' => $partido->id,
                    'persona_id' => $persona->id,
                    'equipo_id' => $equipo->id,
                    'asistira' => $asistira,
                    'requiere_cambio' => !$asistira,
                ],
                'leida' => false,
            ]);
        }

        return $asistencia;
    }

    /**
     * El Capitán confirma los 11 Titulares definitivos de la Lista de Buena Fe
     */
    public function confirmarConvocatoria11(Partido $partido, Equipo $equipo, array $jugadoresIds): array
    {
        return DB::transaction(function () use ($partido, $equipo, $jugadoresIds) {
            // Regla RN-06: Deben ser exactamente 11 jugadores
            if (count($jugadoresIds) !== 11) {
                throw new \Exception("Debes convocar exactamente a 11 jugadores para el partido.");
            }

            // Obtener Lista de Buena Fe del equipo en este torneo
            $listaFe = $equipo->listasBuenaFe()->where('torneo_id', $partido->torneo_id)->first();
            if (!$listaFe) {
                throw new \Exception("El equipo no está inscripto en la Lista de Buena Fe de este torneo.");
            }

            $jugadoresInscriptos = $listaFe->jugadores()->with('persona.fichaMedica')->get()->keyBy('persona_id');

            // Limpiar convocatoria previa
            ConvocatoriaPartido::where('partido_id', $partido->id)
                ->where('equipo_id', $equipo->id)
                ->delete();

            $convocados = [];

            foreach ($jugadoresIds as $personaId) {
                $jugadorFe = $jugadoresInscriptos->get($personaId);

                if (!$jugadorFe) {
                    throw new \Exception("El jugador ID {$personaId} no pertenece a la Lista de Buena Fe del equipo.");
                }

                // Validar Apto Médico vigente
                if (!$jugadorFe->persona->tieneAptoMedicoVigente()) {
                    throw new \Exception("El jugador {$jugadorFe->persona->nombre_completo} tiene el Apto Médico vencido.");
                }

                $convocado = ConvocatoriaPartido::create([
                    'partido_id' => $partido->id,
                    'equipo_id' => $equipo->id,
                    'persona_id' => $personaId,
                    'dorsal' => $jugadorFe->dorsal,
                    'es_titular' => true,
                ]);

                $convocados[] = $convocado;
            }

            return [
                'partido_id' => $partido->id,
                'equipo_id' => $equipo->id,
                'total_convocados' => count($convocados),
                'convocados' => $convocados,
                'mensaje' => "Planilla oficial de 11 titulares confirmada para el domingo.",
            ];
        });
    }
}
