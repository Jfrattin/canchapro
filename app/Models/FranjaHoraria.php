<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranjaHoraria extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'franjas_horarias';

    protected $fillable = [
        'torneo_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'duracion_partido_minutos',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }
}
