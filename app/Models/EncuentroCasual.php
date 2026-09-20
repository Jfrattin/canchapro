<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuentroCasual extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'encuentros_casuales';

    protected $fillable = [
        'creador_persona_id',
        'titulo',
        'cancha_nombre',
        'ubicacion',
        'fecha_hora',
        'formato',
        'modalidad',
        'max_jugadores',
        'precio_total',
        'precio_por_jugador',
        'estado',
        'share_token',
        'equipo_rival_nombre',
        'equipo_rival_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'max_jugadores' => 'integer',
        'precio_total' => 'float',
        'precio_por_jugador' => 'float',
    ];

    protected $appends = ['share_url'];

    public function getShareUrlAttribute(): string
    {
        $origin = request()->getSchemeAndHttpHost();
        return "{$origin}/app?casual_invite={$this->share_token}";
    }

    public function creador()
    {
        return $this->belongsTo(Persona::class, 'creador_persona_id');
    }

    public function equipoRival()
    {
        return $this->belongsTo(Equipo::class, 'equipo_rival_id');
    }

    public function jugadores()
    {
        return $this->hasMany(EncuentroCasualJugador::class, 'encuentro_casual_id');
    }
}
