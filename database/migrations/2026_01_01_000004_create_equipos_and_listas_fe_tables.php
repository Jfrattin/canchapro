<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Equipos
        Schema::create('equipos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->uuid('capitan_persona_id'); // Responsable legal del equipo
            $table->string('invite_token', 64)->unique(); // Token para el link "canchapro.app/join/{token}"
            $table->string('color_primario', 20)->default('#00ff88');
            $table->string('color_secundario', 20)->default('#00e5ff');
            $table->string('logo_url')->nullable();
            $table->timestamps();

            $table->foreign('capitan_persona_id')->references('id')->on('personas')->onDelete('cascade');
        });

        // 2. Inscripción de Equipo a un Torneo (Padrón / Lista de Buena Fe)
        Schema::create('listas_buena_fe', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('torneo_id');
            $table->uuid('equipo_id');
            $table->enum('estado_inscripcion', ['PENDIENTE_PAGO', 'APROBADA_OFICIAL', 'RECHAZADA'])->default('APROBADA_OFICIAL');
            $table->dateTime('fecha_inscripcion')->useCurrent();
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->unique(['torneo_id', 'equipo_id']);
        });

        // 3. Jugadores inscriptos en la Lista de Buena Fe (Hasta 20 jugadores)
        Schema::create('lista_fe_jugadores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lista_buena_fe_id');
            $table->uuid('persona_id');
            $table->integer('dorsal')->default(99);
            $table->enum('posicion', ['Arquero', 'Defensor', 'Mediocampista', 'Delantero'])->default('Mediocampista');
            $table->enum('estado_habilitacion', ['HABILITADO', 'INHABILITADO_MEDICO', 'SANCIONADO'])->default('HABILITADO');
            $table->timestamps();

            $table->foreign('lista_buena_fe_id')->references('id')->on('listas_buena_fe')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->unique(['lista_buena_fe_id', 'persona_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_fe_jugadores');
        Schema::dropIfExists('listas_buena_fe');
        Schema::dropIfExists('equipos');
    }
};
