<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'torneos';

    protected $fillable = [
        'nombre',
        'deporte',
        'sede_id',
        'categoria',
        'descripcion',
        'ubicacion',
        'foto_url',
        'formato_juego',
        'sistema_torneo',
        'max_equipos',
        'max_jugadores_lista_fe',
        'titulares_por_partido',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'max_equipos' => 'integer',
        'max_jugadores_lista_fe' => 'integer',
        'titulares_por_partido' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function canchas()
    {
        return $this->belongsToMany(Cancha::class, 'torneo_canchas', 'torneo_id', 'cancha_id');
    }

    public function franjasHorarias()
    {
        return $this->hasMany(FranjaHoraria::class, 'torneo_id');
    }

    public function listasBuenaFe()
    {
        return $this->hasMany(ListaBuenaFe::class, 'torneo_id');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'listas_buena_fe', 'torneo_id', 'equipo_id');
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'torneo_id');
    }

    public function tieneCuposDisponibles(): bool
    {
        return $this->listasBuenaFe()->count() < $this->max_equipos;
    }
}
