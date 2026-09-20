<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaPartido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'actas_partidos';

    protected $fillable = [
        'partido_id',
        'arbitro_persona_id',
        'fecha_cierre',
        'firma_digital_hash',
        'informe_arbitral',
    ];

    protected $casts = [
        'fecha_cierre' => 'datetime',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }

    public function arbitro()
    {
        return $this->belongsTo(Persona::class, 'arbitro_persona_id');
    }

    public function goles()
    {
        return $this->hasMany(GolPartido::class, 'acta_partido_id');
    }

    public function tarjetas()
    {
        return $this->hasMany(TarjetaPartido::class, 'acta_partido_id');
    }
}
