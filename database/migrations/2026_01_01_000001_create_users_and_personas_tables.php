<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Usuarios (Credenciales de Acceso & Auth)
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['super_admin', 'organizador', 'capitan', 'jugador', 'arbitro'])->default('jugador');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabla de Personas (Datos Civiles del Patrón Trinidad)
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->unique(); // Nullable para soporte de Lazy Linking por DNI
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni', 20)->unique()->index();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 3. Tabla de Fichas Médicas (Apto Físico Obligatorio)
        Schema::create('fichas_medicas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('persona_id')->unique();
            $table->string('grupo_sanguineo', 10)->default('O+');
            $table->boolean('apto_fisico_aprobado')->default(false);
            $table->date('fecha_vencimiento')->nullable();
            $table->string('contacto_emergencia', 100);
            $table->string('obra_social_prepaga', 100)->nullable();
            $table->text('observaciones_medicas')->nullable();
            $table->timestamps();

            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_medicas');
        Schema::dropIfExists('personas');
        Schema::dropIfExists('users');
    }
};
