<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DebugRoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        Log::info('--- DebugRoleMiddleware INICIADO ---');

        if (Auth::guard($guard)->guest()) {
            Log::error('FALLO: No hay ningún usuario autenticado (Auth::guest() es true).');
            throw UnauthorizedException::notLoggedIn();
        }

        $user = Auth::guard($guard)->user();
        $roles = $user->getRoleNames();

        Log::info('Usuario autenticado ID: ' . $user->id);
        Log::info('Roles del usuario: ' . $roles->implode(', '));
        Log::info('Rol requerido por la ruta: ' . $role);
        
        if (! $user->hasRole($role)) {
            Log::error('FALLO: El usuario NO tiene el rol requerido.');
            Log::info('--- DebugRoleMiddleware TERMINADO ---');
            throw UnauthorizedException::forRoles([$role]);
        }
        
        Log::info('ÉXITO: El usuario tiene el rol requerido.');
        Log::info('--- DebugRoleMiddleware TERMINADO ---');

        return $next($request);
    }
}