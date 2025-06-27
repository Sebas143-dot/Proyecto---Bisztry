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
        // 1. Nos aseguramos de que haya un usuario logueado.
        if (!Auth::check()) {
            return redirect('login');
        }

        // --- LÍNEA DE DEPURACIÓN ELIMINADA ---
        // La línea dd(Auth::user()->getRoleNames()); ha sido borrada.

        // 2. Ahora, el código continuará y ejecutará esta comprobación:
        // Si el usuario tiene el rol 'Super-Admin', lo deja pasar.
        if (Auth::user()->hasRole('Super-Admin')) {
            return $next($request);
        }

        // 3. Si no tiene el rol, le niega el acceso.
        abort(403, 'Acceso No Autorizado.');
    }
}
