<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Acta Oficial del Partido (Firmada por el Árbitro)
        Schema::create('actas_partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partido_id')->unique();
            $table->uuid('arbitro_persona_id');
            $table->dateTime('fecha_cierre')->useCurrent();
            $table->string('firma_digital_hash', 128);
            $table->text('informe_arbitral')->nullable();
            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('arbitro_persona_id')->references('id')->on('personas')->onDelete('cascade');
        });

        // 2. Goles Oficiales Registrados en el Acta
        Schema::create('goles_partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('acta_partido_id');
            $table->uuid('partido_id');
            $table->uuid('equipo_id');
            $table->uuid('autor_persona_id')->nullable(); // NULL = Gol Sin Dueño
            $table->integer('minuto')->default(0);
            $table->boolean('es_gol_en_contra')->default(false);
            $table->boolean('es_reclamado_por_capitan')->default(false);
            $table->timestamps();

            $table->foreign('acta_partido_id')->references('id')->on('actas_partidos')->onDelete('cascade');
            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('autor_persona_id')->references('id')->on('personas')->onDelete('set null');
        });

        // 3. Tarjetas y Sanciones Disciplinarias
        Schema::create('tarjetas_partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('acta_partido_id');
            $table->uuid('partido_id');
            $table->uuid('persona_id');
            $table->uuid('equipo_id');
            $table->enum('tipo_tarjeta', ['AMARILLA', 'AZUL', 'ROJA'])->default('AMARILLA');
            $table->integer('minuto')->default(0);
            $table->string('motivo', 200)->nullable();
            $table->timestamps();

            $table->foreign('acta_partido_id')->references('id')->on('actas_partidos')->onDelete('cascade');
            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
        });

        // 4. Disputa y Reclamo de Goles Sin Dueño (Fair Play con Timeout 48h)
        Schema::create('reclamos_goles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gol_partido_id')->unique();
            $table->uuid('capitan_solicitante_id');
            $table->uuid('jugador_reclamado_id');
            $table->uuid('capitan_validador_id'); // Capitán rival
            $table->enum('estado', ['PENDIENTE', 'APROBADO_RIVAL', 'AUTO_APROBADO_TIMEOUT', 'RECHAZADO_TRIBUNAL'])->default('PENDIENTE');
            $table->dateTime('fecha_solicitud')->useCurrent();
            $table->dateTime('fecha_limite_timeout'); // Now + 48 hours
            $table->dateTime('fecha_resolucion')->nullable();
            $table->timestamps();

            $table->foreign('gol_partido_id')->references('id')->on('goles_partidos')->onDelete('cascade');
            $table->foreign('capitan_solicitante_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('jugador_reclamado_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('capitan_validador_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamos_goles');
        Schema::dropIfExists('tarjetas_partidos');
        Schema::dropIfExists('goles_partidos');
        Schema::dropIfExists('actas_partidos');
    }
};
