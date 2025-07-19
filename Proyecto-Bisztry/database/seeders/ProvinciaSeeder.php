<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Asegúrate de añadir esta línea!

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Solo inserta si la tabla de provincias está vacía
        if (DB::table('provincias')->count() === 0) {
            DB::table('provincias')->insert([
                ['prov_cod' => '01', 'prov_nombre' => 'Azuay'],
                ['prov_cod' => '02', 'prov_nombre' => 'Bolívar'],
                ['prov_cod' => '03', 'prov_nombre' => 'Cañar'],
                ['prov_cod' => '04', 'prov_nombre' => 'Carchi'],
                ['prov_cod' => '05', 'prov_nombre' => 'Cotopaxi'],
                ['prov_cod' => '06', 'prov_nombre' => 'Chimborazo'],
                ['prov_cod' => '07', 'prov_nombre' => 'El Oro'],
                ['prov_cod' => '08', 'prov_nombre' => 'Esmeraldas'],
                ['prov_cod' => '09', 'prov_nombre' => 'Guayas'],
                ['prov_cod' => '10', 'prov_nombre' => 'Imbabura'],
                ['prov_cod' => '11', 'prov_nombre' => 'Loja'],
                ['prov_cod' => '12', 'prov_nombre' => 'Los Ríos'],
                ['prov_cod' => '13', 'prov_nombre' => 'Manabí'],
                ['prov_cod' => '14', 'prov_nombre' => 'Morona Santiago'],
                ['prov_cod' => '15', 'prov_nombre' => 'Napo'],
                ['prov_cod' => '16', 'prov_nombre' => 'Pastaza'],
                ['prov_cod' => '17', 'prov_nombre' => 'Pichincha'],
                ['prov_cod' => '18', 'prov_nombre' => 'Tungurahua'],
                ['prov_cod' => '19', 'prov_nombre' => 'Zamora Chinchipe'],
                ['prov_cod' => '20', 'prov_nombre' => 'Galápagos'],
                ['prov_cod' => '21', 'prov_nombre' => 'Sucumbíos'],
                ['prov_cod' => '22', 'prov_nombre' => 'Orellana'],
                ['prov_cod' => '23', 'prov_nombre' => 'Santo Domingo de los Tsáchilas'],
                ['prov_cod' => '24', 'prov_nombre' => 'Santa Elena'],
            ]);
        } else {
            $this->command->info('Provincias ya existentes. Saltando la inserción.');
        }
    }
}