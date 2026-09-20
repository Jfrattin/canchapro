<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Partidos (Fixture Generado por el Sorteo)
        Schema::create('partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('torneo_id');
            $table->integer('jornada'); // Fecha 1, Fecha 2...
            $table->uuid('equipo_local_id');
            $table->uuid('equipo_visitante_id');
            $table->uuid('cancha_id');
            $table->uuid('arbitro_persona_id')->nullable();
            $table->dateTime('fecha_hora');
            $table->enum('estado', ['PROGRAMADO', 'EN_JUEGO', 'FINALIZADO_EN_REVISION', 'CERRADO'])->default('PROGRAMADO');
            $table->integer('goles_local')->nullable();
            $table->integer('goles_visitante')->nullable();
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
            $table->foreign('equipo_local_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('equipo_visitante_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('cancha_id')->references('id')->on('canchas')->onDelete('cascade');
            $table->foreign('arbitro_persona_id')->references('id')->on('personas')->onDelete('set null');
        });

        // 2. Confirmación de Asistencia del Jugador ("Asistiré / No podré ir")
        Schema::create('asistencias_partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partido_id');
            $table->uuid('persona_id');
            $table->uuid('equipo_id');
            $table->enum('estado_asistencia', ['PENDIENTE', 'CONFIRMADO_ASISTE', 'CONFIRMADO_NO_ASISTE'])->default('PENDIENTE');
            $table->dateTime('fecha_confirmacion')->nullable();
            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->unique(['partido_id', 'persona_id']);
        });

        // 3. Convocatoria Oficial del Capitán (Exactamente 11 Titulares)
        Schema::create('convocatorias_partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partido_id');
            $table->uuid('equipo_id');
            $table->uuid('persona_id');
            $table->integer('dorsal');
            $table->boolean('es_titular')->default(true); // 11 titulares
            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->unique(['partido_id', 'equipo_id', 'persona_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convocatorias_partidos');
        Schema::dropIfExists('asistencias_partidos');
        Schema::dropIfExists('partidos');
    }
};
