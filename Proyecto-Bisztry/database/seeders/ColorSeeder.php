<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run()
    {
        DB::table('colores')->insert([
            ['col_detalle' => 'Negro'],
            ['col_detalle' => 'Blanco'],
            ['col_detalle' => 'Rojo'],
            ['col_detalle' => 'Azul'],
            ['col_detalle' => 'Verde'],
            ['col_detalle' => 'Gris'],
        ]);
    }
}