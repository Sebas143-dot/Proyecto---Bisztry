<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // === LÍNEA CRUCIAL: Resetea la caché de roles y permisos ===
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear Permisos
        $permissions = [
            'gestionar-usuarios', 'gestionar-roles', 'ver-reportes',
            'gestionar-clientes', 'gestionar-proveedores', 'gestionar-productos',
            'crear-pedidos', 'gestionar-pedidos',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Crear Roles
        $superAdminRole = Role::create(['name' => 'Super-Admin']);
        $vendedorRole = Role::create(['name' => 'Vendedor']);

        // 3. Asignar Permisos a Roles
        $superAdminRole->givePermissionTo(Permission::all()); // El Super-Admin puede hacer todo
        $vendedorRole->givePermissionTo(['crear-pedidos', 'gestionar-clientes']); // El Vendedor solo puede hacer esto

        // 4. Crear el Usuario Administrador
        $adminUser = User::create([
            'name' => 'Juan Administrador',
            'email' => 'jdipialesi@utn.edu.ec',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // 5. Asignar el Rol de Super-Admin al usuario recién creado
        $adminUser->assignRole($superAdminRole);
    }
}
