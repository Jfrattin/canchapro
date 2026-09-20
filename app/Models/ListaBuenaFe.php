<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaBuenaFe extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'listas_buena_fe';

    protected $fillable = [
        'torneo_id',
        'equipo_id',
        'estado_inscripcion',
        'fecha_inscripcion',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function jugadores()
    {
        return $this->hasMany(ListaFeJugador::class, 'lista_buena_fe_id');
    }
}
