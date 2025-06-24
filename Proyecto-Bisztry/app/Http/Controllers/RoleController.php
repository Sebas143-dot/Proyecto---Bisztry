<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        // Para manejar roles y permisos, se recomienda un paquete como "spatie/laravel-permission".
        // Por ahora, listaremos los usuarios existentes.
        $usuarios = User::paginate(10);
        return view('roles.index', compact('usuarios'));
    }
}