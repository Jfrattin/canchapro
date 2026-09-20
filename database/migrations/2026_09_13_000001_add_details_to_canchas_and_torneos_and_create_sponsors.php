<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ampliar tabla de Canchas
        Schema::table('canchas', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('nombre');
            $table->string('ubicacion')->nullable()->after('descripcion');
            $table->text('foto_url')->nullable()->after('ubicacion');
        });

        // 2. Ampliar tabla de Torneos
        Schema::table('torneos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('categoria');
            $table->string('ubicacion')->nullable()->after('descripcion');
            $table->text('foto_url')->nullable()->after('ubicacion');
        });

        // 3. Tabla de Sponsors y Publicidad (Monetización App Store / Play Store)
        Schema::create('sponsors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('marca');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->text('banner_url')->nullable();
            $table->string('link_destino')->nullable();
            $table->enum('posicion', ['header', 'feed', 'footer', 'popup'])->default('feed');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');

        Schema::table('torneos', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'ubicacion', 'foto_url']);
        });

        Schema::table('canchas', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'ubicacion', 'foto_url']);
        });
    }
};
