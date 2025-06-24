<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    public function run()
    {
        DB::table('provincias')->insert([
            ['prov_cod' => '01', 'prov_nomnbre' => 'Azuay'],
            ['prov_cod' => '02', 'prov_nomnbre' => 'Bolívar'],
            ['prov_cod' => '03', 'prov_nomnbre' => 'Cañar'],
            ['prov_cod' => '04', 'prov_nomnbre' => 'Carchi'],
            ['prov_cod' => '05', 'prov_nomnbre' => 'Cotopaxi'],
            ['prov_cod' => '06', 'prov_nomnbre' => 'Chimborazo'],
            ['prov_cod' => '07', 'prov_nomnbre' => 'El Oro'],
            ['prov_cod' => '08', 'prov_nomnbre' => 'Esmeraldas'],
            ['prov_cod' => '09', 'prov_nomnbre' => 'Guayas'],
            ['prov_cod' => '10', 'prov_nomnbre' => 'Imbabura'],
            ['prov_cod' => '11', 'prov_nomnbre' => 'Loja'],
            ['prov_cod' => '12', 'prov_nomnbre' => 'Los Ríos'],
            ['prov_cod' => '13', 'prov_nomnbre' => 'Manabí'],
            ['prov_cod' => '14', 'prov_nomnbre' => 'Morona Santiago'],
            ['prov_cod' => '15', 'prov_nomnbre' => 'Napo'],
            ['prov_cod' => '16', 'prov_nomnbre' => 'Pastaza'],
            ['prov_cod' => '17', 'prov_nomnbre' => 'Pichincha'],
            ['prov_cod' => '18', 'prov_nomnbre' => 'Tungurahua'],
            ['prov_cod' => '19', 'prov_nomnbre' => 'Zamora Chinchipe'],
            ['prov_cod' => '20', 'prov_nomnbre' => 'Galápagos'],
            ['prov_cod' => '21', 'prov_nomnbre' => 'Sucumbíos'],
            ['prov_cod' => '22', 'prov_nomnbre' => 'Orellana'],
            ['prov_cod' => '23', 'prov_nomnbre' => 'Santo Domingo de los Tsáchilas'],
            ['prov_cod' => '24', 'prov_nomnbre' => 'Santa Elena'],
        ]);
    }
}