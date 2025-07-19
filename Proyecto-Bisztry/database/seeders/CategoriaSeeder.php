<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de categorías está vacía
        if (DB::table('categorias')->count() === 0) {
            DB::table('categorias')->insert([
                ['cate_detalle' => 'Camisetas'],
                ['cate_detalle' => 'Sudaderas'],
                ['cate_detalle' => 'Gorras'],
                ['cate_detalle' => 'Pantalones'],
                ['cate_detalle' => 'Accesorios'],
            ]);
        } else {
            $this->command->info('Categorías ya existentes. Saltando la inserción.');
        }
    }
}