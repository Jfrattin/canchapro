<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $request->setUserResolver(fn () => $user);

            if (($request->is('api/admin/*') || $request->is('admin/*')) && !in_array($user->role, ['super_admin', 'organizador'])) {
                return response()->json(['error' => 'Acceso denegado. Se requieren permisos de Administrador.'], 403);
            }

            if (($request->is('api/arbitro/*') || $request->is('arbitro/*')) && !in_array($user->role, ['arbitro', 'super_admin'])) {
                return response()->json(['error' => 'Acceso denegado. Se requieren permisos de Árbitro.'], 403);
            }

            return $next($request);
        }

        $token = $request->bearerToken() ?? $request->header('X-User-Id') ?? $request->header('Authorization');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $userId = str_replace(['jwt_token_', 'jwt_mock_token_'], '', $token);

            $user = User::find($userId);
            if ($user) {
                Auth::login($user);
                $request->setUserResolver(fn () => $user);

                if (($request->is('api/admin/*') || $request->is('admin/*')) && !in_array($user->role, ['super_admin', 'organizador'])) {
                    return response()->json(['error' => 'Acceso denegado. Se requieren permisos de Administrador.'], 403);
                }

                if (($request->is('api/arbitro/*') || $request->is('arbitro/*')) && !in_array($user->role, ['arbitro', 'super_admin'])) {
                    return response()->json(['error' => 'Acceso denegado. Se requieren permisos de Árbitro.'], 403);
                }

                return $next($request);
            }
        }

        // Rutas de administración y arbitraje requieren token válido
        if ($request->is('api/admin/*') || $request->is('admin/*') || $request->is('api/arbitro/*') || $request->is('arbitro/*')) {
            return response()->json(['error' => 'No autorizado. Debes iniciar sesión con las credenciales correspondientes.'], 401);
        }

        return response()->json(['error' => 'No autorizado. Debes iniciar sesión o registrarte primero.'], 401);
    }
}
