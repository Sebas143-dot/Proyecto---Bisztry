<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // El orden es CRÍTICO para que las claves foráneas se creen correctamente.
        // Primero se llenan las tablas que no dependen de ninguna otra.
        $this->call([
            // --- CATÁLOGOS SIN DEPENDENCIAS ---
            ProvinciaSeeder::class,
            CategoriaSeeder::class,
            TallaSeeder::class,
            ColorSeeder::class,
            EstadoPedidoSeeder::class,
            MetodoPagoSeeder::class,
            ServicioEntregaSeeder::class,
            ProveedorSeeder::class,

            // --- CATÁLOGOS CON DEPENDENCIAS ---
            // CiudadSeeder necesita que la tabla 'provincias' ya tenga datos.
            CiudadSeeder::class,

            // --- (Opcional) DATOS DE PRUEBA ---
            // Si en el futuro creas seeders para generar datos falsos de clientes o productos,
            // los llamarías aquí, al final de todo. Por ejemplo:
            // ClienteSeeder::class,
            // ProductoSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}