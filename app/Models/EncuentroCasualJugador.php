<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuentroCasualJugador extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'encuentro_casual_jugadores';

    protected $fillable = [
        'encuentro_casual_id',
        'persona_id',
        'equipo_num',
        'estado_pago',
        'asistencia_confirmada',
    ];

    protected $casts = [
        'equipo_num' => 'integer',
        'asistencia_confirmada' => 'boolean',
    ];

    public function encuentro()
    {
        return $this->belongsTo(EncuentroCasual::class, 'encuentro_casual_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
