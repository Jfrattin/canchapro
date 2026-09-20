<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory, HasUuids;

    protected $table = "notificaciones";

    protected $fillable = [
        "persona_id",
        "tipo",
        "titulo",
        "mensaje",
        "datos_extra",
        "leida",
    ];

    protected $casts = [
        "datos_extra" => "array",
        "leida" => "boolean",
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }
}
