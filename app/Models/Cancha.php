<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'canchas';

    protected $fillable = [
        'sede_id',
        'nombre',
        'deporte',
        'descripcion',
        'ubicacion',
        'foto_url',
        'tipo_formato',
        'superficie',
        'tiene_iluminacion',
        'es_techada',
        'precio_por_hora',
        'activa',
    ];

    protected $casts = [
        'tiene_iluminacion' => 'boolean',
        'es_techada' => 'boolean',
        'activa' => 'boolean',
        'precio_por_hora' => 'decimal:2',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'cancha_id');
    }
}
