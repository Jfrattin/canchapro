<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GolPartido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'goles_partidos';

    protected $fillable = [
        'acta_partido_id',
        'partido_id',
        'equipo_id',
        'autor_persona_id',
        'minuto',
        'es_gol_en_contra',
        'es_reclamado_por_capitan',
    ];

    protected $casts = [
        'minuto' => 'integer',
        'es_gol_en_contra' => 'boolean',
        'es_reclamado_por_capitan' => 'boolean',
    ];

    public function acta()
    {
        return $this->belongsTo(ActaPartido::class, 'acta_partido_id');
    }

    public function autor()
    {
        return $this->belongsTo(Persona::class, 'autor_persona_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function reclamo()
    {
        return $this->hasOne(ReclamoGol::class, 'gol_partido_id');
    }

    public function tieneAutorAsignado(): bool
    {
        return !is_null($this->autor_persona_id);
    }
}
