<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'email',
        'password',
        'role', // super_admin, organizador, capitan, jugador, arbitro
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación Trinidad: 1 Usuario tiene 1 Persona (Datos civiles)
    public function persona()
    {
        return $this->hasOne(Persona::class, 'user_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCapitan(): bool
    {
        return $this->role === 'capitan';
    }

    public function isArbitro(): bool
    {
        return $this->role === 'arbitro';
    }
}
