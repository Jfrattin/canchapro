<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaFeJugador extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lista_fe_jugadores';

    protected $fillable = [
        'lista_buena_fe_id',
        'persona_id',
        'dorsal',
        'posicion',
        'estado_habilitacion',
    ];

    public function listaBuenaFe()
    {
        return $this->belongsTo(ListaBuenaFe::class, 'lista_buena_fe_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function estaHabilitadoParaJugar(): bool
    {
        return $this->estado_habilitacion === 'HABILITADO' && $this->persona->tieneAptoMedicoVigente();
    }
}
