<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuentros_casuales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('creador_persona_id');
            $table->string('titulo');
            $table->string('cancha_nombre');
            $table->string('ubicacion');
            $table->dateTime('fecha_hora');
            $table->string('formato')->default('F5');
            $table->enum('modalidad', ['JUGADORES_SUELTOS', 'DESAFIO_EQUIPOS'])->default('JUGADORES_SUELTOS');
            $table->integer('max_jugadores')->default(10);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->decimal('precio_por_jugador', 10, 2)->default(0);
            $table->enum('estado', ['ABIERTO', 'CONFIRMADO', 'FINALIZADO', 'CANCELADO'])->default('ABIERTO');
            $table->string('share_token')->unique();
            $table->string('equipo_rival_nombre')->nullable();
            $table->uuid('equipo_rival_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('creador_persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('equipo_rival_id')->references('id')->on('equipos')->onDelete('set null');
        });

        Schema::create('encuentro_casual_jugadores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuentro_casual_id');
            $table->uuid('persona_id');
            $table->integer('equipo_num')->default(1); // 1 = Equipo A / Creador, 2 = Equipo B / Rival
            $table->enum('estado_pago', ['PENDIENTE', 'PAGADO'])->default('PENDIENTE');
            $table->boolean('asistencia_confirmada')->default(true);
            $table->timestamps();

            $table->foreign('encuentro_casual_id')->references('id')->on('encuentros_casuales')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->unique(['encuentro_casual_id', 'persona_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuentro_casual_jugadores');
        Schema::dropIfExists('encuentros_casuales');
    }
};
