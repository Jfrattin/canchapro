<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvocatoriaPartido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'convocatorias_partidos';

    protected $fillable = [
        'partido_id',
        'equipo_id',
        'persona_id',
        'dorsal',
        'es_titular',
    ];

    protected $casts = [
        'es_titular' => 'boolean',
        'dorsal' => 'integer',
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
