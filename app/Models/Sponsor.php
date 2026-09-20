<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sponsors';

    protected $fillable = [
        'marca',
        'titulo',
        'descripcion',
        'banner_url',
        'link_destino',
        'posicion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
