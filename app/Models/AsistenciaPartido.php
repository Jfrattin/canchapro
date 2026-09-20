<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaPartido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asistencias_partidos';

    protected $fillable = [
        'partido_id',
        'persona_id',
        'equipo_id',
        'estado_asistencia', // PENDIENTE, CONFIRMADO_ASISTE, CONFIRMADO_NO_ASISTE
        'fecha_confirmacion',
    ];

    protected $casts = [
        'fecha_confirmacion' => 'datetime',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }
}
