<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioEntregaSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de servicios_entrega está vacía
        if (DB::table('servicios_entrega')->count() === 0) {
            DB::table('servicios_entrega')->insert([
                ['serv_nombre' => 'Servientrega', 'serv_costo' => 5.50],
                ['serv_nombre' => 'Urbano Express', 'serv_costo' => 4.75],
                ['serv_nombre' => 'Recoger en Tienda', 'serv_costo' => 0.00],
            ]);
        } else {
            $this->command->info('Servicios de entrega ya existentes. Saltando la inserción.');
        }
    }
}