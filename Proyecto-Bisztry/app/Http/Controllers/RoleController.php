<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Obtenemos todos los roles y contamos cuántos usuarios tiene cada uno
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Pasamos todos los permisos existentes a la vista de creación
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        // Creamos el nuevo rol
        $role = Role::create(['name' => $request->name]);

        // Si se seleccionaron permisos, se los asignamos al nuevo rol
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    // Los métodos edit, update y destroy los añadiremos si los necesitas.
    // Por ahora, nos centramos en crear.
}