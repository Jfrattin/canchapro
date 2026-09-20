<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'personas';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'telefono',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Relación Trinidad: 1 Persona pertenece a 1 Usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 1 Persona tiene 1 Ficha Médica obligatoria
    public function fichaMedica()
    {
        return $this->hasOne(FichaMedica::class, 'persona_id');
    }

    // Equipos donde es Capitán
    public function equiposCapitan()
    {
        return $this->hasMany(Equipo::class, 'capitan_persona_id');
    }

    // Inscripciones en Listas de Buena Fe como Jugador
    public function listaFeInscripciones()
    {
        return $this->hasMany(ListaFeJugador::class, 'persona_id');
    }

    // Partidos que dirigió como Árbitro
    public function partidosArbitrados()
    {
        return $this->hasMany(Partido::class, 'arbitro_persona_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function tieneAptoMedicoVigente(): bool
    {
        return $this->fichaMedica && $this->fichaMedica->estaVigente();
    }
}
