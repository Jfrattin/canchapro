<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Equipo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'equipos';

    protected $fillable = [
        'nombre',
        'capitan_persona_id',
        'invite_token',
        'color_primario',
        'color_secundario',
        'logo_url',
    ];

    protected $appends = ['invite_url'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($equipo) {
            if (empty($equipo->invite_token)) {
                $equipo->invite_token = Str::slug($equipo->nombre) . '-' . Str::random(8);
            }
        });
    }

    public function capitan()
    {
        return $this->belongsTo(Persona::class, 'capitan_persona_id');
    }

    public function listasBuenaFe()
    {
        return $this->hasMany(ListaBuenaFe::class, 'equipo_id');
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'listas_buena_fe', 'equipo_id', 'torneo_id');
    }

    public function getInviteUrlAttribute(): string
    {
        return url("/join/{$this->invite_token}");
    }
}
