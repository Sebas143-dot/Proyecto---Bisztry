<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de colores está vacía
        if (DB::table('colores')->count() === 0) {
            DB::table('colores')->insert([
                ['col_detalle' => 'Negro'],
                ['col_detalle' => 'Blanco'],
                ['col_detalle' => 'Rojo'],
                ['col_detalle' => 'Azul'],
                ['col_detalle' => 'Verde'],
                ['col_detalle' => 'Gris'],
            ]);
        } else {
            $this->command->info('Colores ya existentes. Saltando la inserción.');
        }
    }
}