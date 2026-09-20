<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReclamoGol extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'reclamos_goles';

    protected $fillable = [
        'gol_partido_id',
        'capitan_solicitante_id',
        'jugador_reclamado_id',
        'capitan_validador_id',
        'estado', // PENDIENTE, APROBADO_RIVAL, AUTO_APROBADO_TIMEOUT, RECHAZADO_TRIBUNAL
        'fecha_solicitud',
        'fecha_limite_timeout',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_limite_timeout' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    public function gol()
    {
        return $this->belongsTo(GolPartido::class, 'gol_partido_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Persona::class, 'capitan_solicitante_id');
    }

    public function jugador()
    {
        return $this->belongsTo(Persona::class, 'jugador_reclamado_id');
    }

    public function validador()
    {
        return $this->belongsTo(Persona::class, 'capitan_validador_id');
    }
}
