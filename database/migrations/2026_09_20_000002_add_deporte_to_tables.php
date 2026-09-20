<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('torneos') && !Schema::hasColumn('torneos', 'deporte')) {
            Schema::table('torneos', function (Blueprint $table) {
                $table->string('deporte')->default('FUTBOL')->after('nombre');
            });
        }

        if (Schema::hasTable('canchas') && !Schema::hasColumn('canchas', 'deporte')) {
            Schema::table('canchas', function (Blueprint $table) {
                $table->string('deporte')->default('FUTBOL')->after('nombre');
            });
        }

        if (Schema::hasTable('encuentros_casuales') && !Schema::hasColumn('encuentros_casuales', 'deporte')) {
            Schema::table('encuentros_casuales', function (Blueprint $table) {
                $table->string('deporte')->default('FUTBOL')->after('titulo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('torneos') && Schema::hasColumn('torneos', 'deporte')) {
            Schema::table('torneos', function (Blueprint $table) {
                $table->dropColumn('deporte');
            });
        }

        if (Schema::hasTable('canchas') && Schema::hasColumn('canchas', 'deporte')) {
            Schema::table('canchas', function (Blueprint $table) {
                $table->dropColumn('deporte');
            });
        }

        if (Schema::hasTable('encuentros_casuales') && Schema::hasColumn('encuentros_casuales', 'deporte')) {
            Schema::table('encuentros_casuales', function (Blueprint $table) {
                $table->dropColumn('deporte');
            });
        }
    }
};
