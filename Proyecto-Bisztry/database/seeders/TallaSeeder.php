<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Añade esta línea!

class TallaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Solo inserta si la tabla de tallas está vacía
        if (DB::table('tallas')->count() === 0) {
            DB::table('tallas')->insert([
                ['tall_detalle' => 'Small', 'talla_cod' => 'S'],
                ['tall_detalle' => 'Medium', 'talla_cod' => 'M'],
                ['tall_detalle' => 'Large', 'talla_cod' => 'L'],
                ['tall_detalle' => 'Extra Large', 'talla_cod' => 'X'],
            ]);
        } else {
            $this->command->info('Tallas ya existentes. Saltando la inserción.');
        }
    }
}