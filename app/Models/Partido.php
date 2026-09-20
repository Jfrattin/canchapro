<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'partidos';

    protected $fillable = [
        'torneo_id',
        'jornada',
        'equipo_local_id',
        'equipo_visitante_id',
        'cancha_id',
        'arbitro_persona_id',
        'fecha_hora',
        'estado',
        'goles_local',
        'goles_visitante',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'jornada' => 'integer',
        'goles_local' => 'integer',
        'goles_visitante' => 'integer',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'cancha_id');
    }

    public function arbitro()
    {
        return $this->belongsTo(Persona::class, 'arbitro_persona_id');
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaPartido::class, 'partido_id');
    }

    public function convocatorias()
    {
        return $this->hasMany(ConvocatoriaPartido::class, 'partido_id');
    }

    public function acta()
    {
        return $this->hasOne(ActaPartido::class, 'partido_id');
    }
}
