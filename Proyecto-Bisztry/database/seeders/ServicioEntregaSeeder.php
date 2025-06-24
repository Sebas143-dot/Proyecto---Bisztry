<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioEntregaSeeder extends Seeder
{
    public function run()
    {
        DB::table('servicios_entrega')->insert([
            ['serv_nombre' => 'Servientrega', 'serv_costo' => 5.50],
            ['serv_nombre' => 'Urbano Express', 'serv_costo' => 4.75],
            ['serv_nombre' => 'Recoger en Tienda', 'serv_costo' => 0.00],
        ]);
    }
}