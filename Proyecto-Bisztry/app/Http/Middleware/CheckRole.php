<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role // Aquí recibiremos el rol (ej: 'superadmin')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Primero, verifica si el usuario está autenticado.
        if (!Auth::check()) {
            return redirect('login');
        }

        // Asumiremos que 'superadmin' significa que la columna 'is_admin' es verdadera.
        // Puedes ajustar esta lógica según cómo gestiones los roles.
        if ($role === 'superadmin' && $request->user()->is_admin) {
            return $next($request);
        }

        // Si el usuario no tiene el rol requerido, redirígelo o muestra un error.
        // Por ejemplo, puedes redirigirlo a la página de inicio.
        return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');

        // O, si prefieres, puedes mostrar una página de error 403 (Prohibido).
        // abort(403, 'Acceso no autorizado.');
    }
}