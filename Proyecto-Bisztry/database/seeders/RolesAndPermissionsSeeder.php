<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Asegúrate de que este modelo no se use para crear usuarios aquí
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Añadido para verificaciones

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // === LÍNEA CRUCIAL: Resetea la caché de roles y permisos ===
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear Permisos (solo si no existen)
        $permissions = [
            'gestionar-usuarios', 'gestionar-roles', 'ver-reportes',
            'gestionar-clientes', 'gestionar-proveedores', 'gestionar-productos',
            'crear-pedidos', 'gestionar-pedidos',
            // Añadir permiso para ver auditoría (si aún no lo tienes)
            'view audit logs',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }
        $this->command->info('Permisos creados/verificados.');


        // 2. Crear Roles (solo si no existen)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super-Admin', 'guard_name' => 'web']);
        $vendedorRole = Role::firstOrCreate(['name' => 'Vendedor', 'guard_name' => 'web']);
        $this->command->info('Roles creados/verificados.');


        // 3. Asignar Permisos a Roles (asegurar que se asignen correctamente)
        // El Super-Admin puede hacer todo
        $superAdminRole->givePermissionTo(Permission::all());
        $this->command->info('Permisos asignados al rol Super-Admin.');

        // El Vendedor solo puede hacer esto
        $vendedorRole->givePermissionTo(['crear-pedidos', 'gestionar-clientes']);
        $this->command->info('Permisos asignados al rol Vendedor.');

        // NOTA: La creación del usuario administrador se moverá al DatabaseSeeder para evitar duplicados
        // y centralizar la creación de usuarios con factories.
    }
}