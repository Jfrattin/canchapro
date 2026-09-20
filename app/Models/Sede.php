<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'telefono',
    ];

    public function canchas()
    {
        return $this->hasMany(Cancha::class, 'sede_id');
    }

    public function torneos()
    {
        return $this->hasMany(Torneo::class, 'sede_id');
    }
}
