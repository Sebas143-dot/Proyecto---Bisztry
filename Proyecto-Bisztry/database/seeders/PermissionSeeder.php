<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Rol de Super-Admin
        $superAdminRole = Role::create(['name' => 'Super-Admin']);
        
        // Crear Permisos
        $permissions = [
            'gestionar_usuarios', 'gestionar_roles',
            'ver_reportes', 'exportar_reportes',
            'gestionar_clientes', 'gestionar_proveedores',
            'gestionar_productos', 'gestionar_categorias', 'gestionar_variantes',
            'crear_pedidos', 'gestionar_pedidos', 'cancelar_pedidos',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar todos los permisos al rol de Super-Admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Asignar el rol de Super-Admin a tu usuario
        $user = User::where('email', 'jdipialesi@utn.edu.ec')->first();
        if ($user) {
            $user->assignRole($superAdminRole);
        }
    }
}