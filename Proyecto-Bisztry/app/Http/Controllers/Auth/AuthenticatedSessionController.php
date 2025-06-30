<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->redirectToByRole());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirige según el rol del usuario autenticado.
     */
    protected function redirectToByRole(): string
    {
        $user = Auth::user();
        $role = $user->rol; // Asegúrate de tener este campo en la tabla users

        return match ($role) {
            'ventas' => route('ventas.dashboard'),
            'contabilidad' => route('contabilidad.dashboard'),
            'publicidad' => route('publicidad.dashboard'),
            'produccion' => route('produccion.dashboard'),
            'logistica' => route('logistica.dashboard'),
            'auditoria' => route('auditoria.dashboard'),
            default => route('admin.dashboard'),
        };
    }
}
