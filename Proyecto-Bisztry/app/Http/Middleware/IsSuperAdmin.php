<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Comprueba si el usuario está logueado Y tiene el rol 'Super-Admin'
        if (Auth::check() && Auth::user()->hasRole('Super-Admin')) {
            // Si cumple, déjalo pasar a la siguiente petición.
            return $next($request);
        }

        // Si no cumple, aborta la petición y muestra un error 403 (Acceso Prohibido).
        abort(403, 'Acceso No Autorizado.');
    }
}