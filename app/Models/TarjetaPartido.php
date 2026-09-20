<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarjetaPartido extends Model
{
    use HasFactory, HasUuids;

    protected $table = "tarjetas_partidos";

    protected $fillable = [
        "acta_partido_id",
        "partido_id",
        "persona_id",
        "equipo_id",
        "tipo_tarjeta", // AMARILLA, ROJA
        "minuto",
        "motivo",
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, "partido_id");
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, "equipo_id");
    }
}
