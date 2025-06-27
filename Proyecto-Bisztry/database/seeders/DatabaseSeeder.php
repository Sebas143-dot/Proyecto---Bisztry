<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // Catálogos
            ProvinciaSeeder::class,
            CategoriaSeeder::class,
            TallaSeeder::class,
            ColorSeeder::class,
            EstadoPedidoSeeder::class,
            MetodoPagoSeeder::class,
            ServicioEntregaSeeder::class,
            ProveedorSeeder::class,
            CiudadSeeder::class,
            
            // Seeder que crea usuarios, roles y asigna permisos
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
