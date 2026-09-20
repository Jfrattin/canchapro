<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('persona_id'); // Destinatario (ej. Capitán)
            $table->string('tipo')->default('ASISTENCIA_JUGADOR'); // ASISTENCIA_JUGADOR, CONVOCATORIA, etc.
            $table->string('titulo');
            $table->text('mensaje');
            $table->json('datos_extra')->nullable(); // partido_id, jugador_id, accion_requerida, etc.
            $table->boolean('leida')->default(false);
            $table->timestamps();

            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
