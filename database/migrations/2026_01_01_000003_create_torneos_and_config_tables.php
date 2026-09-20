<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Torneos
        Schema::create('torneos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre'); // Ej: Torneo Apertura 2026
            $table->uuid('sede_id');
            $table->string('categoria'); // Ej: Libre A, Libre B, Senior +35, Femenino
            $table->enum('formato_juego', ['F5', 'F7', 'F8', 'F11'])->default('F11');
            $table->enum('sistema_torneo', ['LIGA_ROUND_ROBIN', 'GRUPOS_Y_PLAYOFFS', 'ELIMINACION_DIRECTA'])->default('LIGA_ROUND_ROBIN');
            $table->integer('max_equipos')->default(16);
            $table->integer('max_jugadores_lista_fe')->default(20);
            $table->integer('titulares_por_partido')->default(11);
            $table->enum('estado', ['CONFIGURACION', 'INSCRIPCIONES_ABIERTAS', 'CUPOS_COMPLETOS', 'EN_JUEGO', 'FINALIZADO'])->default('INSCRIPCIONES_ABIERTAS');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('cascade');
        });

        // 2. Canchas Habilitadas para el Torneo
        Schema::create('torneo_canchas', function (Blueprint $table) {
            $table->uuid('torneo_id');
            $table->uuid('cancha_id');
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
            $table->foreign('cancha_id')->references('id')->on('canchas')->onDelete('cascade');
            $table->primary(['torneo_id', 'cancha_id']);
        });

        // 3. Franjas Horarias Disponibles para el Sorteo de Partidos
        Schema::create('franjas_horarias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('torneo_id');
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'])->default('Domingo');
            $table->time('hora_inicio'); // Ej: 14:00
            $table->time('hora_fin');    // Ej: 22:00
            $table->integer('duracion_partido_minutos')->default(60); // Duración de cada slot
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franjas_horarias');
        Schema::dropIfExists('torneo_canchas');
        Schema::dropIfExists('torneos');
    }
};
