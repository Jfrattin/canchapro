<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EquipoController;
use App\Http\Controllers\Api\TorneoController;
use App\Http\Controllers\Api\AdminTorneoController;
use App\Http\Controllers\Api\PartidoController;
use App\Http\Controllers\Api\ArbitroController;
use App\Http\Controllers\Api\EncuentroCasualController;

/*
|--------------------------------------------------------------------------
| CanchaPro Suite - REST API Routes
|--------------------------------------------------------------------------
*/

// 1. Autenticación
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth.api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/apto-medico', [AuthController::class, 'subirAptoMedico']);
    });
});

// 2. Torneos (Público & Jugadores)
Route::prefix('torneos')->group(function () {
    Route::get('/', [TorneoController::class, 'index']);
    Route::get('/{torneo}', [TorneoController::class, 'show']);
    Route::get('/{torneo}/tabla', [TorneoController::class, 'tablaPosiciones']);
    Route::middleware('auth.api')->post('/{torneo}/inscribir', [TorneoController::class, 'inscribirEquipo']);
});

// 3. Equipos & Links de Invitación
Route::prefix('equipos')->group(function () {
    Route::get('/{equipo}/roster', [EquipoController::class, 'showRoster']);
    Route::middleware('auth.api')->group(function () {
        Route::get('/mi-equipo', [EquipoController::class, 'miEquipo']);
        Route::get('/mis-estadisticas', [EquipoController::class, 'misEstadisticas']);
        Route::post('/', [EquipoController::class, 'store']);
        Route::post('/join/{token}', [EquipoController::class, 'joinByLink']);
    });
});

// 4. Partidos, Asistencias & Convocatorias de 11
Route::prefix('partidos')->middleware('auth.api')->group(function () {
    Route::get('/proximo', [PartidoController::class, 'proximoPartido']);
    Route::get('/notificaciones', [PartidoController::class, 'misNotificaciones']);
    Route::post('/notificaciones/{notificacion}/leer', [PartidoController::class, 'marcarNotificacionLeida']);
    Route::post('/{partido}/asistencia', [PartidoController::class, 'marcarAsistencia']);
    Route::post('/{partido}/convocatoria-11', [PartidoController::class, 'confirmarConvocatoria11']);
});

// 4.5 Encuentros Casuales / Partiditos entre Amigos
Route::prefix('encuentros-casuales')->group(function () {
    Route::get('/', [EncuentroCasualController::class, 'index']);
    Route::get('/token/{token}', [EncuentroCasualController::class, 'showByToken']);
    Route::middleware('auth.api')->group(function () {
        Route::post('/', [EncuentroCasualController::class, 'store']);
        Route::post('/token/{token}/unirse', [EncuentroCasualController::class, 'unirse']);
        Route::post('/token/{token}/desafiar', [EncuentroCasualController::class, 'desafiar']);
        Route::delete('/{encuentro}', [EncuentroCasualController::class, 'destroy']);
        Route::delete('/{encuentro}/jugadores/{personaId}', [EncuentroCasualController::class, 'expulsarJugador']);
    });
});

// 5. Arbitraje, Cierre de Actas & Disputas de Goles (Fair Play)
Route::prefix('arbitro')->middleware('auth.api')->group(function () {
    Route::get('/mis-partidos', [ArbitroController::class, 'misPartidosAsignados']);
    Route::post('/partidos/{partido}/cerrar', [ArbitroController::class, 'cerrarActa']);
    Route::post('/goles/reclamar', [ArbitroController::class, 'reclamarGol']);
    Route::post('/goles/reclamos/{reclamo}/responder', [ArbitroController::class, 'responderReclamo']);
});

// 6. Super Admin & Backoffice
Route::prefix('admin')->middleware('auth.api')->group(function () {
    Route::post('/torneos', [AdminTorneoController::class, 'store']);
    Route::delete('/torneos/{torneo}', [AdminTorneoController::class, 'destroy']);
    Route::post('/canchas', [AdminTorneoController::class, 'storeCancha']);
    Route::delete('/canchas/{cancha}', [AdminTorneoController::class, 'destroyCancha']);
    Route::post('/sponsors', [AdminTorneoController::class, 'storeSponsor']);
    Route::delete('/sponsors/{sponsor}', [AdminTorneoController::class, 'destroySponsor']);
    Route::post('/torneos/{torneo}/horarios', [AdminTorneoController::class, 'addHorarios']);
    Route::post('/torneos/{torneo}/sortear-fixture', [AdminTorneoController::class, 'sortearFixture']);
    // Gestión de Usuarios, Roles & Designación Arbitral
    Route::get('/usuarios', [AdminTorneoController::class, 'usuariosIndex']);
    Route::put('/usuarios/{user}/rol', [AdminTorneoController::class, 'actualizarRol']);
    Route::post('/personas/{persona}/apto-medico', [AdminTorneoController::class, 'aprobarAptoMedico']);
    Route::get('/partidos-programados', [AdminTorneoController::class, 'partidosProgramados']);
    Route::post('/partidos/{partido}/asignar-arbitro', [AdminTorneoController::class, 'asignarArbitroPartido']);
});

// 7. Sedes y Canchas (Públicas)
Route::get('/sedes', function () {
    return \App\Models\Sede::with('canchas')->get();
});

Route::get('/canchas', function () {
    return \App\Models\Cancha::with('sede')->where('activa', true)->get();
});

// 8. Sponsors y Publicidad (Monetización)
Route::get('/sponsors', function () {
    return \App\Models\Sponsor::where('activo', true)->get();
});
