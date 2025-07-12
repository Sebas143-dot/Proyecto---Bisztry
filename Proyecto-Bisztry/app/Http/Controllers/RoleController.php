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
    $permissions = Permission::all()
        ->groupBy(function ($permission) {
            // Extrae el "módulo" del permiso, por ejemplo: 'gestionar_usuarios' → 'usuarios'
            $parts = explode('_', $permission->name);
            return $parts[1] ?? 'otros';
        });

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

    public function edit(Role $role)
{
    // Agrupamos los permisos como en 'create'
    $permissions = Permission::all()->groupBy(function ($permission) {
        $parts = explode('_', $permission->name);
        return $parts[1] ?? 'otros';
    });

    // Obtenemos los nombres de los permisos que ya tiene este rol
    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
}

public function update(Request $request, Role $role)
{
    $request->validate([
        'name' => 'required|string|unique:roles,name,' . $role->id,
        'permissions' => 'nullable|array'
    ]);

    // Actualizamos el nombre
    $role->name = $request->name;
    $role->save();

    // Sincronizamos los permisos
    $role->syncPermissions($request->permissions ?? []);

    return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
}

public function destroy(Role $role)
{
    $role->delete();

    return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
}

}