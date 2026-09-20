<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FichaMedica extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fichas_medicas';

    protected $fillable = [
        'persona_id',
        'grupo_sanguineo',
        'apto_fisico_aprobado',
        'fecha_vencimiento',
        'contacto_emergencia',
        'obra_social_prepaga',
        'observaciones_medicas',
    ];

    protected $casts = [
        'apto_fisico_aprobado' => 'boolean',
        'fecha_vencimiento' => 'date',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function estaVigente(): bool
    {
        if (!$this->apto_fisico_aprobado || !$this->fecha_vencimiento) {
            return false;
        }
        return $this->fecha_vencimiento->isFuture();
    }
}
