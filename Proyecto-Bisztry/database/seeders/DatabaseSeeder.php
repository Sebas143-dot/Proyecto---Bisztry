<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;    // ¡Asegúrate de importar el modelo User!
use App\Models\Cliente; // ¡Asegúrate de importar el modelo Cliente!

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // --- Catálogos (ejecutar primero, ya que otros datos pueden depender de ellos) ---
            ProvinciaSeeder::class,
            CategoriaSeeder::class,
            TallaSeeder::class,
            ColorSeeder::class,
            EstadoPedidoSeeder::class,
            MetodoPagoSeeder::class,
            ServicioEntregaSeeder::class,
            ProveedorSeeder::class,
            CiudadSeeder::class,

            // --- Seeder que crea usuarios, roles y asigna permisos (¡DEBE IR ANTES DE CREAR EL SUPER-ADMIN!) ---
            RolesAndPermissionsSeeder::class,
        ]);

        // --- Inserción de usuarios y clientes (después de que los roles existan) ---

        // 1. Crea O RECUPERA el usuario Super-Admin para evitar duplicados
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@bizstry.com'], // Busca un usuario con este email
            [
                'name' => 'Super Admin Bizstry',
                'password' => bcrypt('password'), // Contraseña: 'password'
                'is_admin' => true,
                'email_verified_at' => now(), // Añadido para consistencia si el usuario es nuevo
            ]
        );

        // Asegúrate de que el usuario tiene el rol de Super-Admin
        // Esto es importante si el usuario ya existía pero no tenía el rol asignado
        if (!$adminUser->hasRole('Super-Admin')) {
            $adminUser->assignRole('Super-Admin');
        }
        $this->command->info('Usuario Super Admin creado/verificado y rol asignado.');


        // 2. Crea 9 usuarios aleatorios con la Factory (estos tendrán is_admin = false)
        User::factory(9)->create();
        $this->command->info('9 usuarios aleatorios creados.');


        // 3. Crea 90 clientes aleatorios con la Factory
        Cliente::factory(90)->create();
        $this->command->info('90 clientes aleatorios creados.');

        // Puedes añadir más inserciones con Factories aquí para otros modelos
        // Ejemplo: \App\Models\Producto::factory(50)->create();
    }
}