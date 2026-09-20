<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sedes / Complejos Deportivos
        Schema::create('sedes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('ciudad')->default('Buenos Aires');
            $table->string('telefono', 50)->nullable();
            $table->timestamps();
        });

        // 2. Canchas del Complejo
        Schema::create('canchas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sede_id');
            $table->string('nombre'); // Ej: Cancha 1, Cancha Techada 2
            $table->string('tipo_formato')->default('F7');
            $table->enum('superficie', ['Sintetico', 'Cesped Natural', 'Parquet', 'Cemento'])->default('Sintetico');
            $table->boolean('tiene_iluminacion')->default(true);
            $table->boolean('es_techada')->default(false);
            $table->decimal('precio_por_hora', 10, 2)->default(15000.00);
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canchas');
        Schema::dropIfExists('sedes');
    }
};
